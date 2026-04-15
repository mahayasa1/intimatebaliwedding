<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Package;
use App\Models\Subpackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource (Public).
     */
    public function index()
    {
        $packages = Package::with('subpackages')->latest()->paginate(12);
        $categories = Package::distinct()->pluck('category')->filter();
        return view('packages.index', compact('packages', 'categories'));
    }

    /**
     * Display the specified resource (Public).
     */
    public function show($id)
    {
        $package = Package::with('subpackages')->findOrFail($id);

        $otherPackages = Package::where('id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return view('packages.show', compact('package', 'otherPackages'));
    }

    /**
     * Display subpackage detail page (Public).
     */
    public function showSubpackage($packageId, $subpackageId)
    {
        $package    = Package::with('subpackages')->findOrFail($packageId);
        $subpackage = $package->subpackages->firstOrFail(fn($s) => $s->id === $subpackageId);

        return view('packages.subpackage', compact('package', 'subpackage'));
    }

    /**
     * Display a listing for admin.
     */
    public function adminIndex()
    {
        $packages = Package::with('subpackages')->latest()->paginate(20);
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Package::distinct()->pluck('category')->filter();
        return view('admin.packages.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                        => 'required|string|max:255',
            'description'                 => 'nullable|string',
            'category'                    => 'nullable|string|max:255',
            'image'                       => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.name'          => 'required|string|max:255',
            'subpackages.*.description'   => 'nullable|string',
            'subpackages.*.image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.photos.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        DB::transaction(function () use ($request, $validated) {
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

            $validated['photo'] = $photos;
            unset($validated['photos'], $validated['subpackages']);

            $package = Package::create($validated);

            // Simpan subpackages
            if ($request->has('subpackages')) {
                foreach ($request->subpackages as $index => $sub) {
                    if (!empty($sub['name'])) {
                        // Upload subpackage main image
                        $subImage = null;
                        if (!empty($sub['image']) && $sub['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $subImagePath = $sub['image']->store('packages/subpackages', 'public');
                            ImageHelper::createThumbnail($subImagePath);
                            $subImage = $subImagePath;
                        }

                        // Upload subpackage photos
                        $subPhotos = [];
                        if (!empty($sub['photos']) && is_array($sub['photos'])) {
                            foreach ($sub['photos'] as $subPhoto) {
                                if ($subPhoto instanceof \Illuminate\Http\UploadedFile) {
                                    $p = $subPhoto->store('packages/subpackages/photos', 'public');
                                    ImageHelper::createThumbnail($p);
                                    $subPhotos[] = $p;
                                }
                            }
                        }

                        Subpackage::create([
                            'package_id'  => $package->id,
                            'name'        => $sub['name'],
                            'description' => $sub['description'] ?? null,
                            'image'       => $subImage,
                            'photo'       => $subPhotos,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource (Admin).
     */
    public function adminShow(Package $package)
    {
        $package->load('subpackages');
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        $package->load('subpackages');
        $categories = Package::distinct()->pluck('category')->filter();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'                        => 'required|string|max:255',
            'description'                 => 'nullable|string',
            'category'                    => 'nullable|string|max:255',
            'image'                       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'photos.*'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.name'          => 'required_with:subpackages|string|max:255',
            'subpackages.*.description'   => 'nullable|string',
            'subpackages.*.image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'subpackages.*.photos.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        DB::transaction(function () use ($request, $validated, $package) {
            // Update main image jika ada file baru
            if ($request->hasFile('image')) {
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
            unset($validated['photos'], $validated['subpackages']);

            $package->update($validated);

            // Update subpackages: hapus semua lalu buat ulang
            foreach ($package->subpackages as $oldSub) {
                if ($oldSub->image) {
                    Storage::disk('public')->delete($oldSub->image);
                    ImageHelper::deleteThumb($oldSub->image);
                }
                if ($oldSub->photo && is_array($oldSub->photo)) {
                    foreach ($oldSub->photo as $p) {
                        Storage::disk('public')->delete($p);
                        ImageHelper::deleteThumb($p);
                    }
                }
            }
            $package->subpackages()->delete();

            if ($request->has('subpackages')) {
                foreach ($request->subpackages as $index => $sub) {
                    if (!empty($sub['name'])) {
                        $subImage = $sub['existing_image'] ?? null;
                        if (!empty($sub['image']) && $sub['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $subImagePath = $sub['image']->store('packages/subpackages', 'public');
                            ImageHelper::createThumbnail($subImagePath);
                            $subImage = $subImagePath;
                        }

                        $subPhotos = !empty($sub['existing_photos']) ? (array) $sub['existing_photos'] : [];
                        if (!empty($sub['photos']) && is_array($sub['photos'])) {
                            foreach ($sub['photos'] as $subPhoto) {
                                if ($subPhoto instanceof \Illuminate\Http\UploadedFile) {
                                    $p = $subPhoto->store('packages/subpackages/photos', 'public');
                                    ImageHelper::createThumbnail($p);
                                    $subPhotos[] = $p;
                                }
                            }
                        }

                        Subpackage::create([
                            'package_id'  => $package->id,
                            'name'        => $sub['name'],
                            'description' => $sub['description'] ?? null,
                            'image'       => $subImage,
                            'photo'       => $subPhotos,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
            ImageHelper::deleteThumb($package->image);
        }

        if ($package->photo && is_array($package->photo)) {
            foreach ($package->photo as $photo) {
                Storage::disk('public')->delete($photo);
                ImageHelper::deleteThumb($photo);
            }
        }

        $package->subpackages()->delete();
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}