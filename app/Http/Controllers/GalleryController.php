<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::orderBy('order')->latest()->paginate(12);
        $categories = Gallery::distinct()->pluck('category')->filter();
        return view('gallery.index', compact('galleries', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        Gallery::create($validated);

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $galleries)
    {
        return view('gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $galleries)
    {
        return view('gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $galleries)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($galleries->image) {
                Storage::disk('public')->delete($galleries->image);
            }
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        $galleries->update($validated);

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $galleries)
    {
        if ($galleries->image) {
            Storage::disk('public')->delete($galleries->image);
        }

        $galleries->delete();

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery item deleted successfully.');
    }
}