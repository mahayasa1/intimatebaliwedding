<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', [ServiceController::class, 'index'])->name('services.public');
Route::get('/packages', [PackageController::class, 'index'])->name('packages.public');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.public');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.public');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Enquiry Routes
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');

// Admin Routes (you can add authentication middleware later)
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Services CRUD
    Route::resource('services', ServiceController::class);
    
    // Packages CRUD
    Route::resource('packages', PackageController::class);
    
    // Galleries CRUD
    Route::resource('galleries', GalleryController::class);
    
    // Blogs CRUD
    Route::resource('blogs', BlogController::class);
    
    // Enquiries CRUD
    Route::resource('enquiries', EnquiryController::class);
});