<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
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
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        // Upload main image + buat thumbnail
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('packages', 'public');
            ImageHelper::createThumbnail($path);
            $validated['image'] = $path;
        }

        // Upload multiple photos + buat thumbnail masing-masing
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('packages/photos', 'public');
                ImageHelper::createThumbnail($path);
                $photos[] = $path;
            }
        }

        // Simpan sebagai field 'photo' (sesuai kolom database)
        $validated['photo'] = $photos;
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
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        // Update main image jika ada file baru
        if ($request->hasFile('image')) {
            // Hapus file lama + thumbnail-nya
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
                ImageHelper::deleteThumb($package->image);
            }
            $path = $request->file('image')->store('packages', 'public');
            ImageHelper::createThumbnail($path);
            $validated['image'] = $path;
        }

        // Ambil foto-foto yang masih ada
        $existingPhotos = $package->photo ?? [];

        // Hapus foto yang di-remove oleh admin
        if ($request->has('removed_photos')) {
            $removedPhotos = json_decode($request->removed_photos, true);
            if (is_array($removedPhotos)) {
                foreach ($removedPhotos as $photoPath) {
                    Storage::disk('public')->delete($photoPath);
                    ImageHelper::deleteThumb($photoPath);
                    $existingPhotos = array_filter($existingPhotos, fn($p) => $p !== $photoPath);
                }
            }
        }

        // Tambahkan foto baru + buat thumbnail
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('packages/photos', 'public');
                ImageHelper::createThumbnail($path);
                $existingPhotos[] = $path;
            }
        }

        $validated['photo'] = array_values($existingPhotos);
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
        // Hapus main image + thumbnail
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
            ImageHelper::deleteThumb($package->image);
        }

        // Hapus semua gallery photos + thumbnail masing-masing
        if ($package->photo && is_array($package->photo)) {
            foreach ($package->photo as $photo) {
                Storage::disk('public')->delete($photo);
                ImageHelper::deleteThumb($photo);
            }
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}