<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource (Public).
     */
    public function index()
    {
        $packages = Package::latest()->paginate(12);
        return view('packages.index', compact('packages'));
    }

    /**
     * Display the specified resource (Public).
     */
    public function show($id)
    {
        $package = Package::findOrFail($id);
        
        // Get other packages (exclude current package, limit to 3)
        $otherPackages = Package::where('id', '!=', $id)
                            ->latest()
                            ->take(3)
                            ->get();
        
        return view('packages.show', compact('package', 'otherPackages'));
    }

    /**
     * Display a listing for admin.
     */
    public function adminIndex()
    {
        $packages = Package::latest()->paginate(20);
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        // Upload main image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('packages', 'public');
        }

        // Upload multiple photos and store as array
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('packages/photos', 'public');
                $photos[] = $path;
            }
        }
        
        // Store photos as 'photo' field (matching database column name)
        $validated['photo'] = $photos;
        
        // Remove 'photos' from validated if it exists
        unset($validated['photos']);

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource (Admin).
     */
    public function adminShow(Package $package)
    {
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        // Update main image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $validated['image'] = $request->file('image')->store('packages', 'public');
        }

        // Handle photos update
        $existingPhotos = $package->photo ?? [];
        
        // Remove deleted photos
        if ($request->has('removed_photos')) {
            $removedPhotos = json_decode($request->removed_photos, true);
            if (is_array($removedPhotos)) {
                foreach ($removedPhotos as $photoPath) {
                    Storage::disk('public')->delete($photoPath);
                    $existingPhotos = array_filter($existingPhotos, fn($p) => $p !== $photoPath);
                }
            }
        }

        // Add new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('packages/photos', 'public');
                $existingPhotos[] = $path;
            }
        }

        // Store as 'photo' field with array values reset
        $validated['photo'] = array_values($existingPhotos);
        
        // Remove 'photos' from validated if it exists
        unset($validated['photos']);

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        // Delete main image if exists
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        // Delete all photos
        if ($package->photo && is_array($package->photo)) {
            foreach ($package->photo as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}