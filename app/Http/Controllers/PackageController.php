<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Package;
use App\Models\Subpackage;
use App\Services\MediaSync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $packages   = Package::with('subpackages')->orderBy('name', 'asc')->paginate(12);
        $categories = Package::orderBy('category')->distinct()->pluck('category')->filter()->sort()->values();
        return view('packages.index', compact('packages', 'categories'));
    }

    public function show($id)
    {
        $package = Package::with('subpackages')->findOrFail($id);

        $otherPackages = Package::where('id', '!=', $id)
            ->orderBy('name', 'asc')
            ->take(3)
            ->get();

        return view('packages.show', compact('package', 'otherPackages'));
    }

    public function showSubpackage($packageId, $subpackageId)
    {
        $package    = Package::with('subpackages')->findOrFail($packageId);
        $subpackage = $package->subpackages->firstWhere('id', $subpackageId) ?? abort(404);
        return view('packages.subpackage', compact('package', 'subpackage'));
    }

    public function adminIndex()
    {
        $packages = Package::with('subpackages')->orderBy('name', 'asc')->paginate(20);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = Package::orderBy('category')->distinct()->pluck('category')->filter()->sort()->values();
        return view('admin.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                        => 'required|string|max:255',
            'description'                 => 'nullable|string',
            'category'                    => 'nullable|string|max:255',
            'image'                       => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.name'          => 'required|string|max:255',
            'subpackages.*.description'   => 'nullable|string',
            'subpackages.*.image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.photos.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        $package = DB::transaction(function () use ($request, $validated) {

            if ($request->hasFile('image')) {
                $result = ImageHelper::storeAndCompress($request->file('image'), 'packages');
                $validated['image'] = $result['path'];
            }

            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result   = ImageHelper::storeAndCompress($photo, 'packages/photos');
                    $photos[] = $result['path'];
                }
            }

            $validated['photo'] = $photos;
            unset($validated['photos'], $validated['subpackages']);

            $package = Package::create($validated);

            $this->saveSubpackages($request, $package);

            return $package;
        });

        // Foto otomatis masuk ke Gallery dengan label NEW
        MediaSync::syncAll($package);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function adminShow(Package $package)
    {
        $package->load('subpackages');
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        $package->load('subpackages');
        $categories = Package::orderBy('category')->distinct()->pluck('category')->filter()->sort()->values();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'                        => 'required|string|max:255',
            'description'                 => 'nullable|string',
            'category'                    => 'nullable|string|max:255',
            'image'                       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.name'          => 'required_with:subpackages|string|max:255',
            'subpackages.*.description'   => 'nullable|string',
            'subpackages.*.image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.photos.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        // Catat semua file SEBELUM perubahan, untuk dibersihkan setelahnya
        $oldPaths = MediaSync::packagePaths($package);

        DB::transaction(function () use ($request, $validated, $package) {

            if ($request->hasFile('image')) {
                $result = ImageHelper::storeAndCompress($request->file('image'), 'packages');
                $validated['image'] = $result['path'];
            } else {
                unset($validated['image']);
            }

            $existingPhotos = $package->photo ?? [];

            if ($request->has('removed_photos')) {
                $removedPhotos = json_decode($request->removed_photos, true);
                if (is_array($removedPhotos)) {
                    $existingPhotos = array_filter(
                        $existingPhotos,
                        fn($p) => !in_array($p, $removedPhotos, true)
                    );
                }
            }

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result           = ImageHelper::storeAndCompress($photo, 'packages/photos');
                    $existingPhotos[] = $result['path'];
                }
            }

            $validated['photo'] = array_values($existingPhotos);
            unset($validated['photos'], $validated['subpackages']);

            $package->update($validated);

            $this->saveSubpackages($request, $package);
        });

        $package->refresh()->load('subpackages');
        $removed = array_values(array_diff($oldPaths, MediaSync::packagePaths($package)));

        // 1) Foto yang dihapus di sini ikut dihapus dari item gallery manual yang tertaut
        MediaSync::pruneLinkedGalleries($removed);
        // 2) Sinkron mirror gallery (subpackage)
        MediaSync::syncAll($package);
        // 3) Baru hapus file yang benar-benar tidak dipakai lagi
        MediaSync::release($removed);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $paths = MediaSync::packagePaths($package);

        MediaSync::deleteMirrorsOfPackage($package->id);
        $package->subpackages()->delete();
        $package->delete();

        // File yang masih dipakai item gallery manual tidak ikut terhapus
        MediaSync::release($paths);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    /**
     * Simpan subpackage dari form (create & update).
     *
     * Subpackage yang sudah ada di-UPDATE di tempat (ID tetap, sehingga
     * tautan ke gallery tidak putus). Yang tidak ada lagi di form dihapus.
     * File TIDAK dihapus di sini; pembersihan dilakukan oleh MediaSync::release().
     */
    private function saveSubpackages(Request $request, Package $package): void
    {
        $existing = $package->subpackages()->get()->keyBy('id');
        $keptIds  = [];

        foreach ((array) ($request->subpackages ?? []) as $sub) {
            if (empty($sub['name'])) {
                continue;
            }

            $model = !empty($sub['id']) ? $existing->get($sub['id']) : null;

            // Gambar: pakai yang lama hanya jika memang milik subpackage ini
            $subImage = null;
            if ($model && !empty($sub['existing_image']) && $sub['existing_image'] === $model->image) {
                $subImage = $model->image;
            }
            if (!empty($sub['image']) && $sub['image'] instanceof \Illuminate\Http\UploadedFile) {
                $result   = ImageHelper::storeAndCompress($sub['image'], 'packages/subpackages');
                $subImage = $result['path'];
            }

            $subPhotos = [];
            if ($model && !empty($sub['existing_photos'])) {
                $subPhotos = array_values(array_intersect(
                    (array) $sub['existing_photos'],
                    $model->photo ?? []
                ));
            }
            if (!empty($sub['photos']) && is_array($sub['photos'])) {
                foreach ($sub['photos'] as $subPhoto) {
                    if ($subPhoto instanceof \Illuminate\Http\UploadedFile) {
                        $result      = ImageHelper::storeAndCompress($subPhoto, 'packages/subpackages/photos');
                        $subPhotos[] = $result['path'];
                    }
                }
            }

            $data = [
                'name'        => $sub['name'],
                'description' => $sub['description'] ?? null,
                'image'       => $subImage,
                'photo'       => $subPhotos,
            ];

            if ($model) {
                $model->update($data);
                $keptIds[] = $model->id;
            } else {
                $created   = Subpackage::create($data + ['package_id' => $package->id]);
                $keptIds[] = $created->id;
            }
        }

        foreach ($existing as $id => $old) {
            if (!in_array($id, $keptIds, true)) {
                $old->delete();
            }
        }
    }
}
