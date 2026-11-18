<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Subpackage;
use App\Services\MediaSync;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    protected $googleMapsService;

    public function __construct(GoogleMapsService $googleMapsService)
    {
        $this->googleMapsService = $googleMapsService;
    }

    public function index(Request $request)
    {
        // Ambil semua video (tidak dipaginasi)
        $videoGalleries = Gallery::where(function ($q) {
                $q->where('type', 'video')
                  ->orWhereNotNull('video_url');
            })
            ->orderBy('title')
            ->orderBy('order')
            ->get();

        // Pagination langsung dari database (tidak lagi ->get() + sortBy manual)
        $photos = $this->buildPhotoQuery($request->get('category'))
            ->paginate(12)
            ->withQueryString();

        // Semua kategori
        $categories = Gallery::where(function ($q) {
                $q->whereNull('video_url')
                  ->orWhere('video_url', '');
            })
            ->where(function ($q) {
                $q->whereNull('type')
                  ->orWhere('type', 'photo');
            })
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $googleReviews = $this->googleMapsService->getReviews(6);
        $businessStats = $this->googleMapsService->getBusinessStats();

        return view('gallery.index', compact(
            'photos',
            'videoGalleries',
            'categories',
            'googleReviews',
            'businessStats'
        ));
    }

    public function filterAjax(Request $request)
    {
        $category = $request->get('category', 'all');

        $photos = $this->buildPhotoQuery($category)
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'success'     => true,
            'html'        => $this->renderPhotoGridHtml($photos),
            'pagination'  => $this->renderPaginationHtml($photos),
            'total'       => $photos->total(),
            'currentPage' => $photos->currentPage(),
            'category'    => $category,
        ])->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    }

    /**
     * Query dasar untuk gallery bertipe foto, dipakai bersama
     * oleh index() dan filterAjax() agar tidak duplikat.
     */
    private function buildPhotoQuery(?string $category = null)
    {
        $query = Gallery::where(function ($q) {
                $q->whereNull('video_url')
                  ->orWhere('video_url', '');
            })
            ->where(function ($q) {
                $q->whereNull('type')
                  ->orWhere('type', 'photo');
            });

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        return $query->orderBy('title')->orderBy('order');
    }

    /**
     * Render markup grid foto (identik dengan markup di gallery/index.blade.php)
     * murni menggunakan PHP string building, tanpa Blade::render().
     */
    private function renderPhotoGridHtml($photos): string
    {
        if ($photos->isEmpty()) {
            return '<div class="empty-state">'
                . '<div class="empty-icon"><i class="fas fa-images"></i></div>'
                . '<p>Belum ada foto di gallery.</p>'
                . '</div>';
        }
    
        $html = '';
    
        foreach ($photos as $gallery) {
            $additionalPhotos = is_array($gallery->photo) ? $gallery->photo : [];
            $totalPhotos      = ($gallery->image ? 1 : 0) + count($additionalPhotos);
            $category         = $gallery->category ?? 'Other';
            $previewImage     = $gallery->image ?: ($additionalPhotos[0] ?? null);
            $photoLabel       = $totalPhotos == 1 ? 'Image' : 'Images';
    
            $html .= '<a href="' . e(route('gallery.show', $gallery->id)) . '" class="gallery-item" data-category="' . e($category) . '">';
    
            if ($gallery->category) {
                $html .= '<span class="top-badge"><i class="fas fa-tag"></i> ' . e($gallery->category) . '</span>';
            }

            if ($gallery->is_new) {
                $html .= '<span class="new-badge">NEW</span>';
            }
    
            if ($previewImage) {
                $thumbUrl = asset('storage/' . ImageHelper::thumb($previewImage));
                $fullUrl  = asset('storage/' . $previewImage);
                $html .= '<img src="' . e($thumbUrl) . '" alt="' . e($gallery->title) . '" '
                    . 'class="gallery-img" data-fallback="' . e($fullUrl) . '">';
            } else {
                $html .= '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0f0f0;">'
                    . '<i class="fas fa-image" style="font-size:3rem;color:#ccc;"></i></div>';
            }
    
            $html .= '<div class="card-overlay"><div class="card-content">';
            $html .= '<div class="card-title">' . e($gallery->title) . '</div>';
    
            if (!empty($gallery->description)) {
                $html .= '<div class="card-description">' . e(Str::limit($gallery->description, 100)) . '</div>';
            }
    
            $html .= '<div class="card-cta-badge"><i class="fas fa-images"></i> View ' . $totalPhotos . ' ' . $photoLabel . '</div>';
            $html .= '</div></div></a>';
        }
    
        return $html;
    }

    /**
     * Render markup pagination (identik dengan gallery/index.blade.php),
     * link berupa tombol AJAX (data-page), murni PHP string building.
     */
    private function renderPaginationHtml($photos): string
    {
        if (!$photos->hasPages()) {
            return '';
        }

        $currentPage = $photos->currentPage();
        $lastPage    = $photos->lastPage();

        $html = '<div class="simple-pagination" id="galleryPagination">';

        if ($photos->onFirstPage()) {
            $html .= '<span class="btn-page" style="opacity:.5;"><i class="fas fa-angle-left"></i> Previous</span>';
        } else {
            $html .= '<a href="#" class="btn-page ajax-page-link" data-page="' . ($currentPage - 1) . '">'
                . '<i class="fas fa-angle-left"></i> Previous</a>';
        }

        for ($page = 1; $page <= $lastPage; $page++) {
            if ($page == $currentPage) {
                $html .= '<span class="btn-page" style="background:#8B7355;color:#fff;border-color:#8B7355;">' . $page . '</span>';
            } else {
                $html .= '<a href="#" class="btn-page ajax-page-link" data-page="' . $page . '">' . $page . '</a>';
            }
        }

        if ($photos->hasMorePages()) {
            $html .= '<a href="#" class="btn-page ajax-page-link" data-page="' . ($currentPage + 1) . '">'
                . 'Next <i class="fas fa-angle-right"></i></a>';
        } else {
            $html .= '<span class="btn-page" style="opacity:.5;">Next <i class="fas fa-angle-right"></i></span>';
        }

        $html .= '</div>';

        return $html;
    }

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);

        $additionalPhotos = is_array($gallery->photo) ? $gallery->photo : [];

        $allPhotos = [];
        if ($gallery->image) {
            $allPhotos[] = $gallery->image;
        }
        $allPhotos = array_merge($allPhotos, $additionalPhotos);

        $allPhotosUrl = array_map(fn($p) => asset('storage/' . $p), $allPhotos);
        $totalPhotos  = count($allPhotos);

        return view('gallery.show', compact(
            'gallery',
            'allPhotos',
            'allPhotosUrl',
            'totalPhotos'
        ));
    }

    public function refreshReviews()
    {
        $this->googleMapsService->clearCache();
        return redirect()->route('gallery.index')->with('success', 'Google Maps reviews refreshed successfully!');
    }

    public function adminIndex(Request $request)
    {
        $query = Gallery::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'photo') {
                $query->where(function ($q) {
                    $q->whereNull('type')->orWhere('type', 'photo');
                });
            }
            if ($request->type === 'video') {
                $query->where('type', 'video');
            }
        }

        // Urutan: item NEW paling atas (yang terbaru dulu), sisanya sesuai abjad
        $galleries = $query
            ->orderByRaw('CASE WHEN new_until IS NOT NULL AND new_until > ? THEN 0 ELSE 1 END', [now()])
            ->orderByRaw('CASE WHEN new_until IS NOT NULL AND new_until > ? THEN new_until END DESC', [now()])
            ->orderBy('title', 'asc')
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        // Untuk pilihan "Include ke Package / Subpackage"
        $packages = Package::with(['subpackages' => fn($q) => $q->select('id', 'package_id', 'name')->orderBy('name')])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.galleries.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'photo');

        $validated = $request->validate([
            'type'            => 'required|in:photo,video',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'category'        => 'nullable|string|max:255',
            'order'           => 'nullable|integer',
            'foto'            => 'required_if:type,photo|nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'video_url'       => 'required_if:type,video|nullable|url',
            'package_id'      => 'nullable|exists:packages,id',
            'include_package' => 'nullable|boolean',
            'subpackage_ids'  => 'nullable|array',
            'subpackage_ids.*'=> 'exists:subpackages,id',
        ]);

        if ($type === 'video') {
            Gallery::create([
                'type'        => 'video',
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category'    => $validated['category'] ?? null,
                'order'       => $validated['order'] ?? 0,
                'video_url'   => $validated['video_url'],
                'image'       => null,
                'photo'       => [],
                'source'      => 'manual',
            ]);
        } else {
            $image = null;
            if ($request->hasFile('foto')) {
                $result = ImageHelper::storeAndCompress($request->file('foto'), 'gallery');
                $image  = $result['path'];
            }

            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result   = ImageHelper::storeAndCompress($photo, 'gallery/photos');
                    $photos[] = $result['path'];
                }
            }

            $gallery = Gallery::create([
                'type'        => 'photo',
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category'    => $validated['category'] ?? null,
                'order'       => $validated['order'] ?? 0,
                'video_url'   => null,
                'image'       => $image,
                'photo'       => $photos,
                'source'      => 'manual',
                'new_until'   => now()->addDays(MediaSync::NEW_DAYS),
            ]);

            // Include foto ke Package / Subpackage yang dipilih
            if (!empty($validated['package_id'])) {
                MediaSync::includeInto(
                    $gallery,
                    $validated['package_id'],
                    $request->boolean('include_package'),
                    $validated['subpackage_ids'] ?? []
                );
            }
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item created successfully.');
    }

    public function adminShow(Gallery $gallery)
    {
        return view('admin.galleries.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        // Item otomatis dari Subpackage: hanya deskripsi, urutan, dan HAPUS foto
        if ($gallery->isMirror()) {
            return $this->updateMirror($request, $gallery);
        }

        $type = $request->input('type', $gallery->type ?? 'photo');

        if ($type === 'video') {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'video_url'   => 'required|url',
                'description' => 'nullable|string',
                'category'    => 'nullable|string|max:255',
                'order'       => 'nullable|integer',
            ]);
            $validated['type'] = 'video';
            $gallery->update($validated);
        } else {
            $validated = $request->validate([
                'title'             => 'required|string|max:255',
                'foto'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
                'photos.*'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
                'description'       => 'nullable|string',
                'category'          => 'nullable|string|max:255',
                'order'             => 'nullable|integer',
                'remove_main_image' => 'nullable|boolean',
            ]);
            $validated['type'] = 'photo';

            // Foto yang diminta dihapus
            $removedList = [];
            if ($request->filled('removed_photos')) {
                $decoded = json_decode($request->removed_photos, true);
                if (is_array($decoded)) {
                    $removedList = $decoded;
                }
            }

            // Item harus tetap punya minimal 1 foto
            $coverCount = $request->hasFile('foto')
                ? 1
                : (($request->boolean('remove_main_image') || !$gallery->image) ? 0 : 1);
            $remaining = $coverCount
                + count(array_diff($gallery->photo ?? [], $removedList))
                + count((array) $request->file('photos', []));

            if ($remaining < 1) {
                return redirect()->back()->withInput()
                    ->with('error', 'Item gallery harus punya minimal 1 foto. Untuk menghapus seluruh item, gunakan tombol Delete.');
            }

            $oldPaths   = MediaSync::galleryPaths($gallery);
            $newlyAdded = [];

            // Cover
            $image = $gallery->image;
            if ($request->hasFile('foto')) {
                $result       = ImageHelper::storeAndCompress($request->file('foto'), 'gallery');
                $image        = $result['path'];
                $newlyAdded[] = $result['path'];
            } elseif ($request->boolean('remove_main_image')) {
                $image = null;
            }
            if ($image && in_array($image, $removedList, true)) {
                $image = null;
            }

            // Foto tambahan
            $existingPhotos = array_values(array_diff($gallery->photo ?? [], $removedList));

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result           = ImageHelper::storeAndCompress($photo, 'gallery/photos');
                    $existingPhotos[] = $result['path'];
                    $newlyAdded[]     = $result['path'];
                }
            }

            // Cover kosong → foto pertama naik jadi cover
            if (!$image && $existingPhotos) {
                $image = array_shift($existingPhotos);
            }

            $validated['image'] = $image;
            $validated['photo'] = array_values($existingPhotos);
            unset($validated['foto'], $validated['photos'], $validated['remove_main_image']);

            if ($newlyAdded) {
                $validated['new_until'] = now()->addDays(MediaSync::NEW_DAYS);
            }

            $gallery->update($validated);
            $gallery->refresh();

            $removedPaths = array_values(array_diff($oldPaths, MediaSync::galleryPaths($gallery)));

            // Foto baru ikut ke package/subpackage tertaut; foto dihapus ikut dihapus di sana
            if ($newlyAdded) {
                MediaSync::pushToLinkedTargets($gallery, $newlyAdded);
            }
            MediaSync::removeFromTargets($gallery->linked_targets, $removedPaths);

            // File dihapus hanya jika tidak dipakai Package/Subpackage/Gallery lain
            MediaSync::release($removedPaths);
            MediaSync::resyncTargets($gallery->linked_targets);
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Item gallery otomatis dari Subpackage.
     * Menghapus foto di sini = menghapus foto itu dari Subpackage-nya juga.
     */
    private function updateMirror(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        $gallery->update([
            'description' => $validated['description'] ?? null,
            'order'       => $validated['order'] ?? 0,
        ]);

        $removed = [];
        if ($request->filled('removed_photos')) {
            $decoded = json_decode($request->removed_photos, true);
            if (is_array($decoded)) {
                $removed = $decoded;
            }
        }
        if ($request->boolean('remove_main_image') && $gallery->image) {
            $removed[] = $gallery->image;
        }

        // Hanya foto milik item ini yang boleh dihapus
        $removed = array_values(array_intersect($removed, MediaSync::galleryPaths($gallery)));

        if ($removed && $gallery->source === 'subpackage' && $gallery->subpackage_id) {
            $sub = Subpackage::find($gallery->subpackage_id);

            if ($sub) {
                $photos = array_values(array_diff($sub->photo ?? [], $removed));
                $image  = ($sub->image && in_array($sub->image, $removed, true)) ? null : $sub->image;

                // Cover terhapus → foto pertama naik jadi cover
                if (!$image && $photos) {
                    $image = array_shift($photos);
                }

                $sub->update(['image' => $image, 'photo' => $photos]);

                MediaSync::pruneLinkedGalleries($removed);
                MediaSync::syncSubpackage($sub->fresh());   // memperbarui / menghapus item ini
                MediaSync::release($removed);
            }
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item updated. Foto yang dihapus juga terhapus dari Subpackage.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->isMirror()) {
            return redirect()->route('admin.galleries.index')
                ->with('error', 'Item ini otomatis dari Package/Subpackage. Hapus fotonya lewat Package/Subpackage terkait.');
        }

        $paths   = MediaSync::galleryPaths($gallery);
        $targets = $gallery->linked_targets;

        $gallery->delete();

        // Package/Subpackage TIDAK berubah; file tetap ada selama masih dipakai di sana
        MediaSync::release($paths);
        MediaSync::resyncTargets($targets);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item deleted successfully.');
    }
}
