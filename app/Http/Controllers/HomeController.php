<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\Package;
use App\Services\GoogleMapsService;

class HomeController extends Controller
{
    protected $googleMapsService;

    public function __construct(GoogleMapsService $googleMapsService)
    {
        $this->googleMapsService = $googleMapsService;
    }

    /**
     * Display the home page
     */
    public function index()
    {
        // Fetch featured packages (randomized for homepage)
        $packages = Package::inRandomOrder()
                    ->take(8)
                    ->get();

        // Fetch gallery images (randomized, exclude hero & video)
        $galleries = Gallery::where('category', '!=', 'Hero')
                    ->where('type', '!=', 'Video')
                    ->inRandomOrder()
                    ->take(8)
                    ->get();

        // Foto wedding yang baru diupload (dari Subpackage maupun Gallery)
        $recentPhotos = collect();
        Gallery::where(function ($q) {
                    $q->whereNull('type')->orWhereRaw('LOWER(type) != ?', ['video']);
                })
                ->where(function ($q) {
                    $q->whereNull('category')->orWhere('category', '!=', 'Hero');
                })
                ->orderByDesc('new_until')
                ->orderByDesc('created_at')
                ->take(12)
                ->get()
                ->each(function ($g) use (&$recentPhotos) {
                    $paths = array_values(array_unique(array_filter(
                        array_merge([$g->image], $g->photo ?? [])
                    )));
                    foreach (array_slice($paths, 0, 4) as $path) {
                        $recentPhotos->push([
                            'path'   => $path,
                            'title'  => $g->title,
                            'id'     => $g->id,
                            'is_new' => $g->is_new,
                        ]);
                    }
                });
        $recentPhotos = $recentPhotos->take(16)->values();

        // Fetch latest published blogs (limit to 3)
        $blogs = Blog::where('is_published', true)
                    ->whereNotNull('published_at')
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get();

        // Fetch Google Maps reviews and business stats
        $googleReviews = $this->googleMapsService->getReviews(6); // Get 6 reviews for slider
        $businessStats = $this->googleMapsService->getBusinessStats();

        // Get hero slider images from gallery
        $heroSlides = Gallery::where('category', 'Hero')
                        ->orderBy('order')
                        ->take(5)
                        ->get();

        // If no hero slides, use default images
        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([
                (object)['image' => 'assets/background/home_1.jpg', 'is_public' => true],
                (object)['image' => 'assets/background/home_2.jpg', 'is_public' => true],
                (object)['image' => 'assets/background/home_3.jpg', 'is_public' => true],
                (object)['image' => 'assets/background/home_4.jpg', 'is_public' => true],
                (object)['image' => 'assets/background/home_5.jpg', 'is_public' => true],
            ]);
        }

        return view('welcome', compact(
            'packages',
            'galleries',
            'blogs',
            'googleReviews',
            'businessStats',
            'heroSlides',
            'recentPhotos'
        ));
    }
}