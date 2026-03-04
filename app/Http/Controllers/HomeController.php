<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\Package;
use App\Services\GoogleMapsService;
use App\Services\InstagramFeedService;

class HomeController extends Controller
{
    protected $googleMapsService;
    protected $instagramService;

    public function __construct(GoogleMapsService $googleMapsService, InstagramFeedService $instagramService)
    {
        $this->googleMapsService = $googleMapsService;
        $this->instagramService = $instagramService;
    }

    /**
     * Display the home page
     */
    public function index()
    {
        // Fetch featured packages (limit to 4 for homepage)
        $packages = Package::orderBy('created_at', 'desc')
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
                (object)['image' => 'assets/background/home_1.jpg'],
                (object)['image' => 'assets/background/home_2.jpg'],
                (object)['image' => 'assets/background/home_3.jpg'],
            ]);
        }

        // Fetch Instagram posts
        $instagramPosts = $this->instagramService->getFeed(9);
        $instagramProfile = $this->instagramService->getProfile();

        return view('welcome', compact(
            'packages',
            'galleries',
            'blogs',
            'googleReviews',
            'businessStats',
            'heroSlides',
            'instagramPosts',
            'instagramProfile',
        ));
    }
}