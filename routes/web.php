<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES (Public Website)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.public');
Route::get('/packages/{id}', [PackageController::class, 'show'])->name('packages.show');

// Gallery with Google Maps Reviews Integration
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.public');

Route::get('/blog', [BlogController::class, 'index'])->name('blogs.public');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Enquiry Submission
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Admin Panel)
|--------------------------------------------------------------------------
| Access: http://yoursite.com/admin/login
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Guest Routes (Not Authenticated)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest')->group(function () {
        Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.page');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    /*
    |--------------------------------------------------------------------------
    | Protected Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Packages Management
        Route::prefix('packages')->name('packages.')->group(function () {
            Route::get('/', [PackageController::class, 'adminIndex'])->name('index');
            Route::get('/create', [PackageController::class, 'create'])->name('create');
            Route::post('/', [PackageController::class, 'store'])->name('store');
            Route::get('/{package}', [PackageController::class, 'adminShow'])->name('show');
            Route::get('/{package}/edit', [PackageController::class, 'edit'])->name('edit');
            Route::put('/{package}', [PackageController::class, 'update'])->name('update');
            Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
        });
        
        // Galleries Management
        Route::prefix('galleries')->name('galleries.')->group(function () {
            Route::get('/', [GalleryController::class, 'adminIndex'])->name('index');
            Route::get('/create', [GalleryController::class, 'create'])->name('create');
            Route::post('/', [GalleryController::class, 'store'])->name('store');
            Route::get('/{gallery}', [GalleryController::class, 'show'])->name('show');
            Route::get('/{gallery}/edit', [GalleryController::class, 'edit'])->name('edit');
            Route::put('/{gallery}', [GalleryController::class, 'update'])->name('update');
            Route::delete('/{gallery}', [GalleryController::class, 'destroy'])->name('destroy');
            
            // Google Maps Reviews - Refresh Cache (Admin Only)
            Route::post('/refresh-reviews', [GalleryController::class, 'refreshReviews'])->name('galleries.refresh-reviews');
        });
        
        // Blogs Management
        Route::prefix('blogs')->name('blogs.')->group(function () {
            Route::get('/', [BlogController::class, 'adminIndex'])->name('index');
            Route::get('/create', [BlogController::class, 'create'])->name('create');
            Route::post('/', [BlogController::class, 'store'])->name('store');
            Route::get('/{blog}', [BlogController::class, 'adminShow'])->name('show');
            Route::get('/{blog}/edit', [BlogController::class, 'edit'])->name('edit');
            Route::put('/{blog}', [BlogController::class, 'update'])->name('update');
            Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');
        });
        
        // Enquiries Management
        Route::prefix('enquiries')->name('enquiries.')->group(function () {
            Route::get('/', [EnquiryController::class, 'adminIndex'])->name('index');
            Route::get('/{enquiry}', [EnquiryController::class, 'adminShow'])->name('show');
            Route::get('/{enquiry}/edit', [EnquiryController::class, 'adminEdit'])->name('edit');
            Route::put('/{enquiry}', [EnquiryController::class, 'adminUpdate'])->name('update');
            Route::delete('/{enquiry}', [EnquiryController::class, 'adminDestroy'])->name('destroy');
        });
        
        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            // Main CRUD Routes
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            
            // Additional User Actions
            Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/{user}/change-role', [UserController::class, 'changeRole'])->name('change-role');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            
            // AJAX & Utility Routes
            Route::get('/search/query', [UserController::class, 'search'])->name('search');
            Route::get('/export/csv', [UserController::class, 'export'])->name('export');
            Route::get('/statistics/data', [UserController::class, 'statistics'])->name('statistics');
        });
    });
});

/*
|--------------------------------------------------------------------------
| User Profile Routes (Accessible by all authenticated users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

require __DIR__.'/auth.php';