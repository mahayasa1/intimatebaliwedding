<?php

namespace App\Http\Controllers;

use App\Services\InstagramFeedService;

class InstagramController extends Controller
{
    protected $instagramService;

    public function __construct(InstagramFeedService $instagramService)
    {
        $this->instagramService = $instagramService;
    }

    public function index()
    {
        $posts = $this->instagramService->getFeed(9);
        $profile = $this->instagramService->getProfile();
        
        return view('instagram.index', compact('posts', 'profile'));
    }

    public function clearCache()
    {
        $this->instagramService->clearCache();
        
        return redirect()->back()->with('success', 'Instagram cache cleared!');
    }
}