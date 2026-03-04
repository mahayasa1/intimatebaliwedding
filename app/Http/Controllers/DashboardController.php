<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Enquiry;
use App\Models\Gallery;
use App\Models\Package;
class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'packages' => Package::count(),
            'galleries' => Gallery::count(),
            'blogs' => Blog::count(),
            'enquiries' => Enquiry::count(),
            'new_enquiries' => Enquiry::where('status', 'new')->count(),
        ];

        $recentEnquiries = Enquiry::latest()->take(5)->get();
        $recentBlogs = Blog::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEnquiries', 'recentBlogs'));
    }
}