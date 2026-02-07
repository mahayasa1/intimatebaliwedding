<?php

namespace App\Http\Controllers;

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
        // Ambil semua galleries dan sort berdasarkan orientasi gambar
        $allGalleries = Gallery::orderBy('order')->latest()->get();
        
        // Pisahkan berdasarkan orientasi (horizontal terlebih dahulu, lalu vertikal)
        $sortedGalleries = $allGalleries->sortByDesc(function ($gallery) {
            if (!$gallery->image) return 0;
            
            $imagePath = storage_path('app/public/' . $gallery->image);
            
            if (file_exists($imagePath)) {
                list($width, $height) = getimagesize($imagePath);
                
                // Hitung rasio aspek
                $aspectRatio = $width / $height;
                
                // Return nilai tinggi untuk horizontal (landscape), rendah untuk vertical (portrait)
                return $aspectRatio;
            }
            
            return 0;
        });
        
        // Paginate hasil sorted
        $page = request()->get('page', 1);
        $perPage = 12;
        $galleries = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedGalleries->forPage($page, $perPage),
            $sortedGalleries->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $categories = Gallery::distinct()->pluck('category')->filter();
        
        // Fetch testimonials langsung dari Google Maps API
        $googleReviews = $this->googleMapsService->getReviews(6);
        $businessStats = $this->googleMapsService->getBusinessStats();
        
        return view('gallery.index', compact('galleries', 'categories', 'googleReviews', 'businessStats'));
    }

    /**
     * Refresh Google Maps reviews cache
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
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        Gallery::create($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
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
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

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
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery item deleted successfully.');
    }
}