<?php

namespace App\Http\Controllers;

use App\Mail\EnquiryAutoReply;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | USER METHODS (Frontend/Public)
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of the resource (User).
     */
    public function index()
    {
        $enquiries = Enquiry::latest()->paginate(20);
        return view('enquiries.index', compact('enquiries'));
    }

    /**
     * Show the form for creating a new resource (User).
     */
    public function create()
    {
        return view('contact');
    }

    /**
     * Store a newly created resource in storage (User/Public).
     * This is used by the contact form on the frontend.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:255',
            'wedding_date' => 'nullable|string|max:255',
            'wedding_type' => 'nullable|string|max:255',
            'guest_count'  => 'nullable|integer',
            'message'      => 'required|string',
        ]);

        $enquiry = Enquiry::create($validated);

        // Send emails
        $this->sendEnquiryEmails($enquiry);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your enquiry. We will contact you soon!',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Thank you for your enquiry! We\'ve sent a confirmation to your email and will be in touch soon.');
    }

    /**
     * Send notification to admin and auto-reply to user.
     */
    private function sendEnquiryEmails(Enquiry $enquiry): void
    {
        $adminEmail = config('mail.admin_email', env('MAIL_FROM_ADDRESS'));

        // 1. Notify admin
        try {
            Mail::to($adminEmail)->send(new EnquiryReceived($enquiry));
        } catch (\Exception $e) {
            Log::error('Failed to send admin enquiry notification: ' . $e->getMessage(), [
                'enquiry_id' => $enquiry->id,
            ]);
        }

        // 2. Auto-reply to the enquirer
        try {
            Mail::to($enquiry->email)->send(new EnquiryAutoReply($enquiry));
        } catch (\Exception $e) {
            Log::error('Failed to send enquiry auto-reply: ' . $e->getMessage(), [
                'enquiry_id' => $enquiry->id,
                'recipient'  => $enquiry->email,
            ]);
        }
    }

    /**
     * Display the specified resource (User).
     */
    public function show(Enquiry $enquiry)
    {
        return view('enquiries.show', compact('enquiry'));
    }

    /**
     * Show the form for editing the specified resource (User).
     */
    public function edit(Enquiry $enquiry)
    {
        return view('enquiries.edit', compact('enquiry'));
    }

    /**
     * Update the specified resource in storage (User).
     */
    public function update(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:255',
            'wedding_date' => 'nullable|string|max:255',
            'wedding_type' => 'nullable|string|max:255',
            'guest_count'  => 'nullable|integer',
            'message'      => 'required|string',
            'status'       => 'required|in:new,contacted,in_progress,completed,cancelled',
        ]);

        $enquiry->update($validated);

        return redirect()->route('enquiries.index')
            ->with('success', 'Enquiry updated successfully.');
    }

    /**
     * Remove the specified resource from storage (User).
     */
    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('enquiries.index')
            ->with('success', 'Enquiry deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN METHODS (Backend/Admin Panel)
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of the resource (Admin).
     * Supports filtering by status.
     */
    public function adminIndex(Request $request)
    {
        $query = Enquiry::latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $enquiries = $query->paginate(20)->withQueryString();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    /**
     * Display the specified resource (Admin).
     */
    public function adminShow(Enquiry $enquiry)
    {
        return view('admin.enquiries.show', compact('enquiry'));
    }

    /**
     * Show the form for editing the specified resource (Admin).
     */
    public function adminEdit(Enquiry $enquiry)
    {
        return view('admin.enquiries.edit', compact('enquiry'));
    }

    /**
     * Update the specified resource in storage (Admin).
     */
    public function adminUpdate(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:255',
            'wedding_date' => 'nullable|string|max:255',
            'wedding_type' => 'nullable|string|max:255',
            'guest_count'  => 'nullable|integer',
            'message'      => 'required|string',
            'status'       => 'required|in:new,contacted,in_progress,completed,cancelled',
        ]);

        $enquiry->update($validated);

        return redirect()->route('admin.enquiries.index')
            ->with('success', 'Enquiry updated successfully.');
    }

    /**
     * Remove the specified resource from storage (Admin).
     */
    public function adminDestroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')
            ->with('success', 'Enquiry deleted successfully.');
    }
}