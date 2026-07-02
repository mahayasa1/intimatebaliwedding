<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Gallery;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\LengthAwarePaginator;

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

    // Query foto
    $photoQuery = Gallery::where(function ($q) {
            $q->whereNull('video_url')
              ->orWhere('video_url', '');
        })
        ->where(function ($q) {
            $q->whereNull('type')
              ->orWhere('type', 'photo');
        });

    // Filter kategori
    if ($request->filled('category') && $request->category !== 'all') {
        $photoQuery->where('category', $request->category);
    }

    $photoGalleries = $photoQuery
        ->orderBy('title', 'asc')
        ->orderBy('order')
        ->get();

    // Tetap gunakan sorting berdasarkan rasio gambar
    $sortedPhotos = $photoGalleries->sortByDesc(function ($gallery) {
        if (!$gallery->image) {
            return 0;
        }

        $imagePath = storage_path('app/public/' . $gallery->image);

        if (file_exists($imagePath)) {
            [$width, $height] = getimagesize($imagePath);

            return $height > 0 ? ($width / $height) : 0;
        }

        return 0;
    });

    // Pagination
    $page = $request->get('page', 1);
    $perPage = 12;

    $photos = new LengthAwarePaginator(
        $sortedPhotos->forPage($page, $perPage)->values(),
        $sortedPhotos->count(),
        $perPage,
        $page,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );

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
        $page     = max((int) $request->get('page', 1), 1);
        $perPage  = 12;

        $photoQuery = Gallery::where(function ($q) {
                $q->whereNull('video_url')
                  ->orWhere('video_url', '');
            })
            ->where(function ($q) {
                $q->whereNull('type')
                  ->orWhere('type', 'photo');
            });

        if ($category && $category !== 'all') {
            $photoQuery->where('category', $category);
        }

        $photoGalleries = $photoQuery
            ->orderBy('title', 'asc')
            ->orderBy('order')
            ->get();

        // Sorting berdasarkan rasio gambar (sama seperti index())
        $sortedPhotos = $photoGalleries->sortByDesc(function ($gallery) {
            if (!$gallery->image) {
                return 0;
            }

            $imagePath = storage_path('app/public/' . $gallery->image);

            if (file_exists($imagePath)) {
                [$width, $height] = getimagesize($imagePath);
                return $height > 0 ? ($width / $height) : 0;
            }

            return 0;
        });

        $photos = new LengthAwarePaginator(
            $sortedPhotos->forPage($page, $perPage)->values(),
            $sortedPhotos->count(),
            $perPage,
            $page,
            [
                'path'  => route('gallery.filter'),
                'query' => ['category' => $category],
            ]
        );

        return response()->json([
            'success'     => true,
            'html'        => $this->renderGalleryGrid($photos),
            'pagination'  => $this->renderGalleryPagination($photos),
            'total'       => $photos->total(),
            'currentPage' => $photos->currentPage(),
            'category'    => $category,
        ]);
    }

    /**
     * Render markup grid foto (identik dengan markup di index.blade.php)
     * tanpa membuat file blade baru — dikompilasi inline via Blade::render().
     */
    protected function renderGalleryGrid($photos): string
    {
        $template = <<<'BLADE'
@php use App\Helpers\ImageHelper; @endphp
@forelse($photos as $gallery)
    @php
        $additionalPhotos = is_array($gallery->photo) ? $gallery->photo : [];
        $totalPhotos = ($gallery->image ? 1 : 0) + count($additionalPhotos);
    @endphp
    <a href="{{ route('gallery.show', $gallery->id) }}"
       class="gallery-item"
       data-category="{{ $gallery->category ?? 'Other' }}">

        @if($gallery->category)
        <span class="top-badge"><i class="fas fa-tag"></i> {{ $gallery->category }}</span>
        @endif

        <img src="{{ asset('storage/' . ImageHelper::thumb($gallery->image)) }}"
             alt="{{ $gallery->title }}"
             loading="lazy"
             onerror="this.onerror=null; this.src='{{ asset('storage/' . $gallery->image) }}';">

        <div class="card-overlay">
            <div class="card-content">
                <div class="card-title">{{ $gallery->title }}</div>

                @if(!empty($gallery->description))
                <div class="card-description">{{ Str::limit($gallery->description, 100) }}</div>
                @endif

                <div class="card-cta-badge">
                    <i class="fas fa-images"></i>
                    View {{ $totalPhotos }} {{ $totalPhotos == 1 ? 'Photo' : 'Photos' }}
                </div>
            </div>
        </div>
    </a>
@empty
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-images"></i></div>
        <p>Belum ada foto di gallery.</p>
    </div>
@endforelse
BLADE;

        return Blade::render($template, ['photos' => $photos]);
    }

    /**
     * Render markup pagination (identik dengan index.blade.php),
     * link diganti jadi tombol AJAX (data-page) bukan <a href> reload.
     */
    protected function renderGalleryPagination($photos): string
    {
        if (!$photos->hasPages()) {
            return '';
        }

        $template = <<<'BLADE'
<div class="simple-pagination" id="galleryPagination">
    @if($photos->onFirstPage())
        <span class="btn-page" style="opacity:.5;">
            <i class="fas fa-angle-left"></i> Previous
        </span>
    @else
        <a href="#" class="btn-page ajax-page-link" data-page="{{ $photos->currentPage() - 1 }}">
            <i class="fas fa-angle-left"></i> Previous
        </a>
    @endif

    @foreach($photos->getUrlRange(1, $photos->lastPage()) as $page => $url)
        @if($page == $photos->currentPage())
            <span class="btn-page" style="background:#8B7355;color:#fff;border-color:#8B7355;">
                {{ $page }}
            </span>
        @else
            <a href="#" class="btn-page ajax-page-link" data-page="{{ $page }}">{{ $page }}</a>
        @endif
    @endforeach

    @if($photos->hasMorePages())
        <a href="#" class="btn-page ajax-page-link" data-page="{{ $photos->currentPage() + 1 }}">
            Next <i class="fas fa-angle-right"></i>
        </a>
    @else
        <span class="btn-page" style="opacity:.5;">
            Next <i class="fas fa-angle-right"></i>
        </span>
    @endif
</div>
BLADE;

        return Blade::render($template, ['photos' => $photos]);
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

        $galleries = $query
            ->orderBy('title', 'asc')
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'photo');

        $validated = $request->validate([
            'type'        => 'required|in:photo,video',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:255',
            'order'       => 'nullable|integer',
            'foto'        => 'required_if:type,photo|nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'video_url'   => 'required_if:type,video|nullable|url',
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

            Gallery::create([
                'type'        => 'photo',
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category'    => $validated['category'] ?? null,
                'order'       => $validated['order'] ?? 0,
                'video_url'   => null,
                'image'       => $image,
                'photo'       => $photos,
            ]);
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
                'title'       => 'required|string|max:255',
                'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
                'photos.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
                'description' => 'nullable|string',
                'category'    => 'nullable|string|max:255',
                'order'       => 'nullable|integer',
            ]);
            $validated['type'] = 'photo';

            if ($request->hasFile('foto')) {
                if ($gallery->image) {
                    Storage::disk('public')->delete($gallery->image);
                    ImageHelper::deleteThumb($gallery->image);
                }
                $result              = ImageHelper::storeAndCompress($request->file('foto'), 'gallery');
                $validated['image']  = $result['path'];
            }

            $existingPhotos = $gallery->photo ?? [];

            if ($request->has('removed_photos')) {
                $removedPhotos = json_decode($request->removed_photos, true);
                if (is_array($removedPhotos)) {
                    foreach ($removedPhotos as $photoPath) {
                        Storage::disk('public')->delete($photoPath);
                        ImageHelper::deleteThumb($photoPath);
                        $existingPhotos = array_filter($existingPhotos, fn($p) => $p !== $photoPath);
                    }
                }
            }

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result           = ImageHelper::storeAndCompress($photo, 'gallery/photos');
                    $existingPhotos[] = $result['path'];
                }
            }

            $validated['photo'] = array_values($existingPhotos);
            unset($validated['foto'], $validated['photos']);
            $gallery->update($validated);
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
            ImageHelper::deleteThumb($gallery->image);
        }

        if ($gallery->photo && is_array($gallery->photo)) {
            foreach ($gallery->photo as $photo) {
                Storage::disk('public')->delete($photo);
                ImageHelper::deleteThumb($photo);
            }
        }

        $gallery->delete();
        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item deleted successfully.');
    }
}