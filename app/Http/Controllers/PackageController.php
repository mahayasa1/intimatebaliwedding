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
    public function index()
    {
        $packages   = Package::with('subpackages')->latest()->paginate(12);
        $categories = Package::distinct()->pluck('category')->filter();
        return view('packages.index', compact('packages', 'categories'));
    }

    public function show($id)
    {
        $package = Package::with('subpackages')->findOrFail($id);

        $otherPackages = Package::where('id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return view('packages.show', compact('package', 'otherPackages'));
    }

    public function showSubpackage($packageId, $subpackageId)
    {
        $package    = Package::with('subpackages')->findOrFail($packageId);
        $subpackage = $package->subpackages->firstWhere('id', $subpackageId) ?? abort(404);
        return view('packages.subpackage', compact('package', 'subpackage'));
    }

    public function adminIndex()
    {
        $packages = Package::with('subpackages')->latest()->paginate(20);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = Package::distinct()->pluck('category')->filter();
        return view('admin.packages.create', compact('categories'));
    }

    /**
     * Store a newly created package.
     * ALL images (main, photos, subpackage images) are compressed + thumbnailed.
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

            // Main image — compress + thumbnail
            if ($request->hasFile('image')) {
                $result = ImageHelper::storeAndCompress($request->file('image'), 'packages');
                $validated['image'] = $result['path'];
            }

            // Additional gallery photos — compress + thumbnail each
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result   = ImageHelper::storeAndCompress($photo, 'packages/photos');
                    $photos[] = $result['path'];
                }
            }

            $validated['photo'] = $photos;
            unset($validated['photos'], $validated['subpackages']);

            $package = Package::create($validated);

            // Subpackages
            if ($request->has('subpackages')) {
                foreach ($request->subpackages as $index => $sub) {
                    if (empty($sub['name'])) continue;

                    // Subpackage main image — compress + thumbnail
                    $subImage = null;
                    if (!empty($sub['image']) && $sub['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $result   = ImageHelper::storeAndCompress($sub['image'], 'packages/subpackages');
                        $subImage = $result['path'];
                    }

                    // Subpackage additional photos — compress + thumbnail each
                    $subPhotos = [];
                    if (!empty($sub['photos']) && is_array($sub['photos'])) {
                        foreach ($sub['photos'] as $subPhoto) {
                            if ($subPhoto instanceof \Illuminate\Http\UploadedFile) {
                                $result      = ImageHelper::storeAndCompress($subPhoto, 'packages/subpackages/photos');
                                $subPhotos[] = $result['path'];
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
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function adminShow(Package $package)
    {
        $package->load('subpackages');
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        $package->load('subpackages');
        $categories = Package::distinct()->pluck('category')->filter();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    /**
     * Update an existing package.
     * All NEW images are compressed + thumbnailed.
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

        // Replace main image if new file provided
        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
                ImageHelper::deleteThumb($package->image);
            }
    
            $result = ImageHelper::storeAndCompress(
                $request->file('image'),
                'packages'
            );
    
            $validated['image'] = $result['path'];
    
        } else {
            // IMPORTANT FIX:
            unset($validated['image']);
        }

            // Handle removed existing photos
            $existingPhotos = $package->photo ?? [];

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

            // Add new gallery photos — compress + thumbnail each
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $result           = ImageHelper::storeAndCompress($photo, 'packages/photos');
                    $existingPhotos[] = $result['path'];
                }
            }

            $validated['photo'] = array_values($existingPhotos);
            unset($validated['photos'], $validated['subpackages']);

            $package->update($validated);

            // Delete old subpackages (their images too)
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

            // Re-create subpackages with compressed images
            if ($request->has('subpackages')) {
                foreach ($request->subpackages as $index => $sub) {
                    if (empty($sub['name'])) continue;

                    $subImage = $sub['existing_image'] ?? null;
                    if (!empty($sub['image']) && $sub['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $result   = ImageHelper::storeAndCompress($sub['image'], 'packages/subpackages');
                        $subImage = $result['path'];
                    }

                    $subPhotos = !empty($sub['existing_photos']) ? (array) $sub['existing_photos'] : [];
                    if (!empty($sub['photos']) && is_array($sub['photos'])) {
                        foreach ($sub['photos'] as $subPhoto) {
                            if ($subPhoto instanceof \Illuminate\Http\UploadedFile) {
                                $result      = ImageHelper::storeAndCompress($subPhoto, 'packages/subpackages/photos');
                                $subPhotos[] = $result['path'];
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
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

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