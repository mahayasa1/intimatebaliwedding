<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Gallery;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    protected $googleMapsService;

    public function __construct(GoogleMapsService $googleMapsService)
    {
        $this->googleMapsService = $googleMapsService;
    }

    /**
     * Display a listing of the resource (Frontend).
     */
    public function index()
    {
        $allGalleries = Gallery::orderBy('order')->latest()->get();

        $photoGalleries = $allGalleries->filter(fn($g) => !$g->isVideo());
        $videoGalleries = $allGalleries->filter(fn($g) => $g->isVideo());

        $sortedPhotos = $photoGalleries->sortByDesc(function ($gallery) {
            if (!$gallery->image) return 0;
            $imagePath = storage_path('app/public/' . $gallery->image);
            if (file_exists($imagePath)) {
                [$width, $height] = getimagesize($imagePath);
                return $width / $height;
            }
            return 0;
        });

        $page    = request()->get('page', 1);
        $perPage = 12;

        $photos = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedPhotos->forPage($page, $perPage),
            $sortedPhotos->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $categories = Gallery::where(function ($q) {
                            $q->whereNull('video_url')->orWhere('video_url', '');
                        })
                        ->where('type', '!=', 'video')
                        ->distinct()
                        ->pluck('category')
                        ->filter();

        $googleReviews  = $this->googleMapsService->getReviews(6);
        $businessStats  = $this->googleMapsService->getBusinessStats();

        return view('gallery.index', compact(
            'photos',
            'videoGalleries',
            'categories',
            'googleReviews',
            'businessStats'
        ));
    }

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);

        $additionalPhotos = is_array($gallery->photo) ? $gallery->photo : [];

        $allPhotos = [];
        if ($gallery->image) $allPhotos[] = $gallery->image;
        $allPhotos = array_merge($allPhotos, $additionalPhotos);

        $allPhotosUrl = array_map(fn($p) => asset('storage/' . $p), $allPhotos);

        $totalPhotos = count($allPhotos);

        return view('gallery.show', compact('gallery', 'allPhotos', 'allPhotosUrl', 'totalPhotos'));
    }

    public function refreshReviews()
    {
        $this->googleMapsService->clearCache();
        return redirect()->route('gallery.index')
            ->with('success', 'Google Maps reviews refreshed successfully!');
    }

    public function adminIndex()
    {
        $galleries = Gallery::orderBy('order')->latest()->paginate(20);
        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     * All images are compressed + thumbnailed via ImageHelper::storeAndCompress().
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'photo');

        if ($type === 'video') {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'video_url'   => 'required|url',
                'description' => 'nullable|string',
                'category'    => 'nullable|string|max:255',
                'order'       => 'nullable|integer',
            ]);

            $validated['type']  = 'video';
            $validated['image'] = '';
            $validated['photo'] = [];

            Gallery::create($validated);
        } else {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'foto'        => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
                'photos.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
                'description' => 'nullable|string',
                'category'    => 'nullable|string|max:255',
                'order'       => 'nullable|integer',
            ]);

            $validated['type'] = 'photo';

            // Upload main image: compress + create thumbnail
            if ($request->hasFile('foto')) {
                $result = ImageHelper::storeAndCompress($request->file('foto'), 'gallery');
                $validated['image'] = $result['path'];
            }

            // Upload additional photos: compress + create thumbnail each
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result   = ImageHelper::storeAndCompress($photo, 'gallery/photos');
                    $photos[] = $result['path'];
                }
            }
            $validated['photo'] = $photos;

            unset($validated['foto'], $validated['photos']);
            Gallery::create($validated);
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item created successfully.');
    }

    public function adminShow(Gallery $gallery)
    {
        return view('admin.galleries.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     * All new images are compressed + thumbnailed.
     */
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

            // Replace main image if new file provided
            if ($request->hasFile('foto')) {
                if ($gallery->image) {
                    Storage::disk('public')->delete($gallery->image);
                    ImageHelper::deleteThumb($gallery->image);
                }
                $result = ImageHelper::storeAndCompress($request->file('foto'), 'gallery');
                $validated['image'] = $result['path'];
            }

            // Handle removed existing photos
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

            // Add new photos: compress + create thumbnail
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

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item updated successfully.');
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

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item deleted successfully.');
    }
}