@extends('layouts.admin')

@section('title', 'Edit Enquiry')
@section('page-title', 'Edit Enquiry')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Header */
    .form-header {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(139, 115, 85, 0.25);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .form-header-content {
        position: relative;
        z-index: 1;
    }

    .form-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .form-subtitle {
        color: rgba(255,255,255,0.85);
        font-family: 'Work Sans', sans-serif;
        font-size: 1rem;
    }

    /* Info Box */
    .info-box {
        padding: 1.75rem;
        background: linear-gradient(135deg, #e8f4f8 0%, #d1ecf1 100%);
        border-left: 4px solid #8B7355;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .info-box-title {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }

    .info-box-text {
        font-family: 'Work Sans', sans-serif;
        color: #333;
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .info-box-text strong {
        color: #1a1a1a;
        font-weight: 600;
    }

    /* Form Card */
    .form-card {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 2rem;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        border-radius: 10px;
        font-size: 1.25rem;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-family: 'Work Sans', sans-serif;
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
    }

    .form-label .required {
        color: #e74c3c;
        margin-left: 0.25rem;
    }

    .form-control {
        font-family: 'Work Sans', sans-serif;
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
        transform: translateY(-2px);
    }

    .form-control.error {
        border-color: #e74c3c;
        background: #fff5f5;
    }

    .form-control:disabled {
        background: #f8f9fa;
        cursor: not-allowed;
        color: #999;
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
        line-height: 1.6;
    }

    .error-message {
        font-family: 'Work Sans', sans-serif;
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .error-message::before {
        content: '⚠️';
    }

    .form-help {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        font-style: italic;
    }

    /* Status Options */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .status-option {
        position: relative;
    }

    .status-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .status-option label {
        font-family: 'Work Sans', sans-serif;
        display: block;
        padding: 1.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-weight: 500;
        background: white;
        font-size: 0.95rem;
    }

    .status-option label:hover {
        border-color: #8B7355;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.15);
    }

    .status-option input[type="radio"]:checked + label {
        border-color: #8B7355;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.25);
    }

    .status-option label span {
        display: block;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    /* Action Buttons */
    .action-section {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(149, 165, 166, 0.2);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-header {
            padding: 2rem 1.5rem;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .form-card {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .status-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <!-- Header -->
    <div class="form-header">
        <div class="form-header-content">
            <h1 class="form-title">Edit Enquiry</h1>
            <p class="form-subtitle">Update enquiry status and wedding information</p>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="info-box">
        <div class="info-box-title">📋 Enquiry from {{ $enquiry->name }}</div>
        <div class="info-box-text">
            <strong>Email:</strong> {{ $enquiry->email }}<br>
            @if($enquiry->phone)
            <strong>Phone:</strong> {{ $enquiry->phone }}<br>
            @endif
            <strong>Received:</strong> {{ $enquiry->created_at->format('F d, Y \a\t h:i A') }}
        </div>
    </div>

    <form action="{{ route('admin.enquiries.update', $enquiry) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Customer Information (Read-only) -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">👤</span>
                Customer Information
            </h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" value="{{ $enquiry->name }}" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="{{ $enquiry->email }}" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" value="{{ $enquiry->phone ?: 'Not provided' }}" disabled>
                </div>
            </div>
        </div>

        <!-- Wedding Details (Editable) -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">💍</span>
                Wedding Details
            </h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="wedding_date" class="form-label">Wedding Date</label>
                    <input 
                        type="text" 
                        id="wedding_date" 
                        name="wedding_date" 
                        class="form-control @error('wedding_date') error @enderror"
                        value="{{ old('wedding_date', $enquiry->wedding_date) }}"
                        placeholder="e.g., December 25, 2024"
                    >
                    @error('wedding_date')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="wedding_type" class="form-label">Wedding Type</label>
                    <input 
                        type="text" 
                        id="wedding_type" 
                        name="wedding_type" 
                        class="form-control @error('wedding_type') error @enderror"
                        value="{{ old('wedding_type', $enquiry->wedding_type) }}"
                        placeholder="e.g., Beach Wedding, Traditional"
                    >
                    @error('wedding_type')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="guest_count" class="form-label">Guest Count</label>
                    <input 
                        type="number" 
                        id="guest_count" 
                        name="guest_count" 
                        class="form-control @error('guest_count') error @enderror"
                        value="{{ old('guest_count', $enquiry->guest_count) }}"
                        placeholder="e.g., 150"
                    >
                    @error('guest_count')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Message (Read-only) -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">💬</span>
                Customer Message
            </h3>
            <div class="form-group">
                <textarea class="form-control" rows="6" disabled>{{ $enquiry->message }}</textarea>
            </div>
        </div>

        <!-- Status (Editable) -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">📊</span>
                Enquiry Status <span class="required">*</span>
            </h3>
            <div class="status-grid">
                <div class="status-option">
                    <input 
                        type="radio" 
                        id="status_new" 
                        name="status" 
                        value="new" 
                        {{ old('status', $enquiry->status) == 'new' ? 'checked' : '' }}
                    >
                    <label for="status_new">
                        <span>🆕</span>
                        New
                    </label>
                </div>

                <div class="status-option">
                    <input 
                        type="radio" 
                        id="status_contacted" 
                        name="status" 
                        value="contacted" 
                        {{ old('status', $enquiry->status) == 'contacted' ? 'checked' : '' }}
                    >
                    <label for="status_contacted">
                        <span>📞</span>
                        Contacted
                    </label>
                </div>

                <div class="status-option">
                    <input 
                        type="radio" 
                        id="status_in_progress" 
                        name="status" 
                        value="in_progress" 
                        {{ old('status', $enquiry->status) == 'in_progress' ? 'checked' : '' }}
                    >
                    <label for="status_in_progress">
                        <span>⏳</span>
                        In Progress
                    </label>
                </div>

                <div class="status-option">
                    <input 
                        type="radio" 
                        id="status_completed" 
                        name="status" 
                        value="completed" 
                        {{ old('status', $enquiry->status) == 'completed' ? 'checked' : '' }}
                    >
                    <label for="status_completed">
                        <span>✅</span>
                        Completed
                    </label>
                </div>

                <div class="status-option">
                    <input 
                        type="radio" 
                        id="status_cancelled" 
                        name="status" 
                        value="cancelled" 
                        {{ old('status', $enquiry->status) == 'cancelled' ? 'checked' : '' }}
                    >
                    <label for="status_cancelled">
                        <span>❌</span>
                        Cancelled
                    </label>
                </div>
            </div>
            @error('status')
            <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-help">Select the current status of this enquiry to track its progress</div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-secondary">
                    ← Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    💾 Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection