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

        $sortedGalleries = $allGalleries->sortByDesc(function ($gallery) {
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
        $galleries = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedGalleries->forPage($page, $perPage),
            $sortedGalleries->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $categories = Gallery::distinct()->pluck('category')->filter();

        $googleReviews  = $this->googleMapsService->getReviews(6);
        $businessStats  = $this->googleMapsService->getBusinessStats();

        return view('gallery.index', compact('galleries', 'categories', 'googleReviews', 'businessStats'));
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

    /**
     * Refresh Google Maps reviews cache.
     */
    public function refreshReviews()
    {
        $this->googleMapsService->clearCache();
        return redirect()->route('gallery.index')
            ->with('success', 'Google Maps reviews refreshed successfully!');
    }

    /**
     * Display a listing for admin.
     */
    public function adminIndex()
    {
        $galleries = Gallery::orderBy('order')->latest()->paginate(20);
        return view('admin.galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'foto'        => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:255',
            'order'       => 'nullable|integer',
        ]);

        // Upload main image + thumbnail
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('gallery', 'public');
            ImageHelper::createThumbnail($path);
            $validated['image'] = $path;
        }

        // Upload additional photos + thumbnails
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('gallery/photos', 'public');
                ImageHelper::createThumbnail($path);
                $photos[] = $path;
            }
        }
        $validated['photo'] = $photos;

        unset($validated['foto'], $validated['photos']);
        Gallery::create($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function adminShow(Gallery $gallery)
    {
        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:255',
            'order'       => 'nullable|integer',
        ]);

        // Update main image jika ada file baru
        if ($request->hasFile('foto')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
                ImageHelper::deleteThumb($gallery->image);
            }
            $path = $request->file('foto')->store('gallery', 'public');
            ImageHelper::createThumbnail($path);
            $validated['image'] = $path;
        }

        // Ambil foto-foto yang masih ada
        $existingPhotos = $gallery->photo ?? [];

        // Hapus foto yang di-remove oleh admin
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

        // Upload foto baru + buat thumbnail
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('gallery/photos', 'public');
                ImageHelper::createThumbnail($path);
                $existingPhotos[] = $path;
            }
        }

        $validated['photo'] = array_values($existingPhotos);
        unset($validated['foto'], $validated['photos']);

        $gallery->update($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
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