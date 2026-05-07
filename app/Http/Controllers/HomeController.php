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
                    ->take(4)
                    ->get();

        // Fetch gallery images (randomized, exclude hero & video)
        $galleries = Gallery::where('category', '!=', 'Hero')
                    ->inRandomOrder()
                    ->take(6)
                    ->get();

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
        ));
    }
}