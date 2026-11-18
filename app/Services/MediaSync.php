<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Subpackage;
use Illuminate\Support\Facades\DB;

/**
 * MediaSync
 * ============================================================
 * Menjembatani foto Package / Subpackage dengan Gallery.
 *
 *  - Package/Subpackage  → Gallery : mirror otomatis (source = package|subpackage)
 *  - Gallery (manual)    → Package/Subpackage : foto ditambahkan ke photo[] target
 *  - File fisik dipakai bersama (tidak diduplikasi). File hanya dihapus dari
 *    disk kalau sudah tidak dipakai oleh Package, Subpackage, maupun Gallery.
 * ============================================================
 */
class MediaSync
{
    /** Label NEW tampil selama sekian hari */
    public const NEW_DAYS = 14;

    /* ─── PATH HELPERS ─────────────────────────────────────── */

    public static function galleryPaths(Gallery $gallery): array
    {
        return array_values(array_unique(array_filter(
            array_merge([$gallery->image], $gallery->photo ?? [])
        )));
    }

    /** Semua path milik package + seluruh subpackage-nya */
    public static function packagePaths(Package $package): array
    {
        $package->loadMissing('subpackages');

        $paths = array_merge([$package->image], $package->photo ?? []);
        foreach ($package->subpackages as $sub) {
            $paths = array_merge($paths, [$sub->image], $sub->photo ?? []);
        }

        return array_values(array_unique(array_filter($paths)));
    }

    /* ─── HAPUS FILE DENGAN AMAN ───────────────────────────── */

    /** Semua path yang masih direferensikan di database. */
    public static function usedPaths(): array
    {
        $used = [];

        $collect = function ($rows) use (&$used) {
            foreach ($rows as $row) {
                if (!empty($row->image)) {
                    $used[$row->image] = true;
                }
                $photos = is_string($row->photo) ? json_decode($row->photo, true) : $row->photo;
                if (is_array($photos)) {
                    foreach ($photos as $p) {
                        if ($p) {
                            $used[$p] = true;
                        }
                    }
                }
            }
        };

        $collect(DB::table('packages')->select('image', 'photo')->get());
        $collect(DB::table('subpackages')->select('image', 'photo')->get());
        $collect(DB::table('galleries')->whereNull('deleted_at')->select('image', 'photo')->get());

        return $used;
    }

    /**
     * Hapus file fisik (+ thumbnail) HANYA jika sudah tidak dipakai di mana pun.
     * Panggil SETELAH perubahan database selesai.
     */
    public static function release(array $paths): void
    {
        $paths = array_values(array_unique(array_filter($paths)));
        if (!$paths) {
            return;
        }

        $used = self::usedPaths();
        foreach ($paths as $path) {
            if (!isset($used[$path])) {
                ImageHelper::delete($path);
            }
        }
    }

    /* ─── PACKAGE / SUBPACKAGE → GALLERY (MIRROR) ──────────── */

    /** Sinkronkan mirror untuk package + semua subpackage-nya. */
    public static function syncAll(Package $package, bool $markNew = true): void
    {
        $package->refresh()->load('subpackages');

        self::syncPackage($package, $markNew);

        foreach ($package->subpackages as $sub) {
            self::syncSubpackage($sub, $markNew);
        }

        // Mirror milik subpackage yang sudah dihapus → buang
        Gallery::where('source', 'subpackage')
            ->where('package_id', $package->id)
            ->whereNotIn('subpackage_id', $package->subpackages->pluck('id')->all())
            ->get()
            ->each->forceDelete();
    }

    public static function syncPackage(Package $package, bool $markNew = true): void
    {
        // Foto package (gambar utama) TIDAK masuk gallery, hanya foto subpackage.
        // Di sini hanya membersihkan item gallery otomatis package yang mungkin
        // sudah terbentuk sebelumnya.
        Gallery::where('source', 'package')
            ->where('package_id', $package->id)
            ->get()
            ->each->forceDelete();
    }

    public static function syncSubpackage(Subpackage $sub, bool $markNew = true): void
    {
        $sub->loadMissing('package');
        $package = $sub->package;

        self::syncMirror(
            ['source' => 'subpackage', 'package_id' => $sub->package_id, 'subpackage_id' => $sub->id],
            $package->name . ' – ' . $sub->name,
            $package->category,
            $sub->description,
            $sub->image,
            $sub->photo ?? [],
            self::manualPaths('subpackage', $sub->id),
            $markNew
        );
    }

    /** Hapus semua mirror milik sebuah package (dipakai saat package dihapus). */
    public static function deleteMirrorsOfPackage(string $packageId): void
    {
        Gallery::where('source', '!=', 'manual')
            ->where('package_id', $packageId)
            ->get()
            ->each->forceDelete();
    }

    private static function syncMirror(
        array $keys,
        string $title,
        ?string $category,
        ?string $description,
        ?string $image,
        array $photos,
        array $excludePaths,
        bool $markNew
    ): void {
        $exclude = array_flip($excludePaths);

        if ($image && isset($exclude[$image])) {
            $image = null;
        }
        $photos = array_values(array_filter(
            array_unique($photos),
            fn($p) => $p && $p !== $image && !isset($exclude[$p])
        ));

        // Tidak ada cover tapi masih ada foto → foto pertama jadi cover
        if (!$image && $photos) {
            $image = array_shift($photos);
        }

        $gallery = Gallery::where('source', $keys['source'])
            ->where('package_id', $keys['package_id'])
            ->when(
                $keys['subpackage_id'],
                fn($q) => $q->where('subpackage_id', $keys['subpackage_id']),
                fn($q) => $q->whereNull('subpackage_id')
            )
            ->first();

        // Tidak ada foto sama sekali → tidak perlu item gallery
        if (!$image && !$photos) {
            $gallery?->forceDelete();
            return;
        }

        if (!$gallery) {
            Gallery::create($keys + [
                'type'        => 'photo',
                'title'       => $title,
                'description' => $description,
                'category'    => $category,
                'order'       => 0,
                'image'       => $image,
                'photo'       => $photos,
                'new_until'   => $markNew ? now()->addDays(self::NEW_DAYS) : null,
            ]);
            return;
        }

        $oldPaths = self::galleryPaths($gallery);
        $newPaths = array_values(array_filter(array_merge([$image], $photos)));
        $added    = array_diff($newPaths, $oldPaths);

        $data = [
            'title'    => $title,
            'category' => $category,
            'image'    => $image,
            'photo'    => $photos,
        ];
        if ($markNew && $added) {
            $data['new_until'] = now()->addDays(self::NEW_DAYS);
        }

        $gallery->update($data);
    }

    /**
     * Path foto dari item gallery MANUAL yang sudah di-include ke target ini.
     * Foto tersebut tidak dimasukkan lagi ke mirror supaya tidak tampil dobel.
     */
    private static function manualPaths(string $type, string $id): array
    {
        return Gallery::where('source', 'manual')
            ->whereNotNull('linked_targets')
            ->get()
            ->filter(function (Gallery $g) use ($type, $id) {
                $t = $g->linked_targets ?? [];

                return $type === 'package'
                    ? (!empty($t['include_package']) && ($t['package_id'] ?? null) === $id)
                    : in_array($id, $t['subpackage_ids'] ?? [], true);
            })
            ->flatMap(fn(Gallery $g) => self::galleryPaths($g))
            ->unique()
            ->values()
            ->all();
    }

    /* ─── GALLERY (MANUAL) → PACKAGE / SUBPACKAGE ──────────── */

    /**
     * Tambahkan foto item gallery ke package dan/atau subpackage pilihan.
     *
     * @param array|null $onlyPaths  kalau diisi, hanya path ini yang ditambahkan
     */
    public static function includeInto(
        Gallery $gallery,
        ?string $packageId,
        bool $includePackage,
        array $subpackageIds,
        ?array $onlyPaths = null
    ): void {
        if (!$packageId) {
            return;
        }

        $package = Package::with('subpackages')->find($packageId);
        if (!$package) {
            return;
        }

        $paths = $onlyPaths ?? self::galleryPaths($gallery);
        if (!$paths) {
            return;
        }

        // Subpackage harus milik package yang dipilih
        $subpackageIds = array_values(array_intersect(
            $subpackageIds,
            $package->subpackages->pluck('id')->all()
        ));

        if (!$includePackage && !$subpackageIds) {
            return;
        }

        if ($includePackage) {
            $package->photo = self::mergePaths($package->photo ?? [], $paths, $package->image);
            $package->save();
        }

        $targetSubs = $package->subpackages->whereIn('id', $subpackageIds);
        foreach ($targetSubs as $sub) {
            $sub->photo = self::mergePaths($sub->photo ?? [], $paths, $sub->image);
            $sub->save();
        }

        $gallery->update([
            'linked_targets' => [
                'package_id'      => $package->id,
                'include_package' => $includePackage,
                'subpackage_ids'  => $subpackageIds,
            ],
        ]);

        // Rapikan mirror (foto yang dimiliki item manual dikeluarkan dari mirror)
        self::resyncTargets($gallery->linked_targets);
    }

    /** Foto baru yang ditambah ke item manual ikut masuk ke target-nya. */
    public static function pushToLinkedTargets(Gallery $gallery, array $newPaths): void
    {
        $t = $gallery->linked_targets;
        if (!$t || !$newPaths) {
            return;
        }

        self::includeInto(
            $gallery,
            $t['package_id'] ?? null,
            !empty($t['include_package']),
            $t['subpackage_ids'] ?? [],
            $newPaths
        );
    }

    /** Sinkron ulang mirror dari target-target milik item manual (tanpa label NEW). */
    public static function resyncTargets(?array $targets): void
    {
        if (!$targets || empty($targets['package_id'])) {
            return;
        }

        $package = Package::with('subpackages')->find($targets['package_id']);
        if (!$package) {
            return;
        }

        if (!empty($targets['include_package'])) {
            self::syncPackage($package, false);
        }

        foreach ($package->subpackages->whereIn('id', $targets['subpackage_ids'] ?? []) as $sub) {
            self::syncSubpackage($sub, false);
        }
    }

    /**
     * Item gallery MANUAL dihapus fotonya → foto itu juga dihapus dari
     * package/subpackage yang tertaut.
     */
    public static function removeFromTargets(?array $targets, array $paths): void
    {
        if (!$targets || empty($targets['package_id']) || !$paths) {
            return;
        }

        $package = Package::with('subpackages')->find($targets['package_id']);
        if (!$package) {
            return;
        }

        if (!empty($targets['include_package'])) {
            $package->photo = array_values(array_diff($package->photo ?? [], $paths));
            $package->save();
        }

        foreach ($package->subpackages->whereIn('id', $targets['subpackage_ids'] ?? []) as $sub) {
            $sub->photo = array_values(array_diff($sub->photo ?? [], $paths));
            $sub->save();
        }

        self::resyncTargets($targets);
    }

    /**
     * Foto dihapus dari package/subpackage → foto itu juga dihapus dari item
     * gallery MANUAL yang tertaut (kecuali masih ada di target lain miliknya).
     */
    public static function pruneLinkedGalleries(array $removedPaths): void
    {
        if (!$removedPaths) {
            return;
        }

        $removed = array_flip($removedPaths);

        Gallery::where('source', 'manual')
            ->whereNotNull('linked_targets')
            ->get()
            ->each(function (Gallery $g) use ($removed) {
                $gone = array_values(array_filter(
                    self::galleryPaths($g),
                    fn($p) => isset($removed[$p])
                ));
                if (!$gone) {
                    return;
                }

                // Masih dipakai oleh salah satu target miliknya? jangan dihapus
                $gone = array_values(array_diff($gone, self::targetPaths($g->linked_targets ?? [])));
                if ($gone) {
                    self::removePathsFromGallery($g, $gone);
                }
            });
    }

    /** Semua path (cover + foto) dari package/subpackage target. */
    private static function targetPaths(array $t): array
    {
        $paths = [];

        if (!empty($t['package_id']) && !empty($t['include_package'])) {
            $pkg = Package::find($t['package_id']);
            if ($pkg) {
                $paths = array_merge($paths, [$pkg->image], $pkg->photo ?? []);
            }
        }

        if (!empty($t['subpackage_ids'])) {
            foreach (Subpackage::whereIn('id', $t['subpackage_ids'])->get() as $sub) {
                $paths = array_merge($paths, [$sub->image], $sub->photo ?? []);
            }
        }

        return array_values(array_filter($paths));
    }

    /** Buang path dari sebuah item gallery; foto pertama naik jadi cover; kosong → item dihapus. */
    private static function removePathsFromGallery(Gallery $g, array $paths): void
    {
        $photos = array_values(array_diff($g->photo ?? [], $paths));
        $image  = ($g->image && in_array($g->image, $paths, true)) ? null : $g->image;

        if (!$image && $photos) {
            $image = array_shift($photos);
        }

        if (!$image && !$photos) {
            $g->delete();
            return;
        }

        $g->update(['image' => $image, 'photo' => $photos]);
    }

    private static function mergePaths(array $existing, array $new, ?string $cover = null): array
    {
        $merged = array_values(array_unique(array_merge($existing, $new)));

        return $cover ? array_values(array_filter($merged, fn($p) => $p !== $cover)) : $merged;
    }
}
