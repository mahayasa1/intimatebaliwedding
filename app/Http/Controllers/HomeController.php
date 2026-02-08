<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Service;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;

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
        // Fetch featured packages (limit to 4 for homepage)
        $packages = Package::withCount('services')
                    ->latest()
                    ->take(4)
                    ->get();

        // Fetch latest gallery images (limit to 6)
        $galleries = Gallery::orderBy('order')
                    ->latest()
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
                        ->take(3)
                        ->get();

        // If no hero slides, use default images
        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([
                (object)['image' => 'assets/intimate/web/Background/home_1.jpg'],
                (object)['image' => 'assets/intimate/web/Background/home_2.jpg'],
                (object)['image' => 'assets/intimate/web/Background/home_3.jpg'],
            ]);
        }

        return view('welcome', compact(
            'packages',
            'galleries',
            'blogs',
            'googleReviews',
            'businessStats',
            'heroSlides'
        ));
    }
}