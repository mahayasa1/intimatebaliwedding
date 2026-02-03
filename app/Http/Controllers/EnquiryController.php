<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enquiries = Enquiry::latest()->paginate(20);
        return view('enquiries.index', compact('enquiries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('enquiries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'wedding_date' => 'nullable|string|max:255',
            'wedding_type' => 'nullable|string|max:255',
            'guest_count' => 'nullable|integer',
            'message' => 'required|string',
        ]);

        Enquiry::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your enquiry. We will contact you soon!',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Thank you for your enquiry. We will contact you soon!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enquiry $enquiry)
    {
        return view('enquiries.show', compact('enquiry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enquiry $enquiry)
    {
        return view('enquiries.edit', compact('enquiry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'wedding_date' => 'nullable|string|max:255',
            'wedding_type' => 'nullable|string|max:255',
            'guest_count' => 'nullable|integer',
            'message' => 'required|string',
            'status' => 'required|in:new,contacted,in_progress,completed,cancelled',
        ]);

        $enquiry->update($validated);

        return redirect()->route('enquiries.index')
            ->with('success', 'Enquiry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('enquiries.index')
            ->with('success', 'Enquiry deleted successfully.');
    }
}