@extends('admin.layouts.app')

@section('title', 'Edit Enquiry')
@section('page-title', 'Edit Enquiry')

@push('styles')
<style>
    .form-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        max-width: 900px;
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: #999;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label .required {
        color: #e74c3c;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .form-control.error {
        border-color: #e74c3c;
    }

    .form-control:disabled {
        background: #f8f9fa;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .error-message {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-help {
        color: #999;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-box {
        padding: 1rem;
        background: #e8f4f8;
        border-left: 4px solid #3498db;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .info-box-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .info-box-text {
        color: #666;
        font-size: 0.9rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        display: inline-block;
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .status-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .status-option {
        position: relative;
    }

    .status-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .status-option label {
        display: block;
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }

    .status-option input[type="radio"]:checked + label {
        border-color: #3498db;
        background: #e8f4f8;
        color: #2c3e50;
    }

    .status-option label:hover {
        border-color: #3498db;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .status-options {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="form-card">
    <div class="form-header">
        <h1 class="form-title">Edit Enquiry</h1>
        <p class="form-subtitle">Update enquiry status and information</p>
    </div>

    <div class="info-box">
        <div class="info-box-title">Customer: {{ $enquiry->name }}</div>
        <div class="info-box-text">
            <strong>Email:</strong> {{ $enquiry->email }}<br>
            <strong>Received:</strong> {{ $enquiry->created_at->format('M d, Y \a\t h:i A') }}
        </div>
    </div>

    <form action="{{ route('admin.enquiries.update', $enquiry) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Customer Information (Read-only) -->
        <h3 style="margin-bottom: 1rem; color: #2c3e50;">Customer Information</h3>
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

        <!-- Wedding Details (Editable) -->
        <h3 style="margin: 2rem 0 1rem; color: #2c3e50;">Wedding Details</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="wedding_date" class="form-label">Wedding Date</label>
                <input 
                    type="text" 
                    id="wedding_date" 
                    name="wedding_date" 
                    class="form-control @error('wedding_date') error @enderror"
                    value="{{ old('wedding_date', $enquiry->wedding_date) }}"
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
                >
                @error('guest_count')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Message (Read-only) -->
        <div class="form-group">
            <label class="form-label">Customer Message</label>
            <textarea class="form-control" rows="5" disabled>{{ $enquiry->message }}</textarea>
        </div>

        <!-- Status (Editable) -->
        <h3 style="margin: 2rem 0 1rem; color: #2c3e50;">Status <span class="required">*</span></h3>
        <div class="status-options">
            <div class="status-option">
                <input 
                    type="radio" 
                    id="status_new" 
                    name="status" 
                    value="new" 
                    {{ old('status', $enquiry->status) == 'new' ? 'checked' : '' }}
                >
                <label for="status_new">🆕 New</label>
            </div>

            <div class="status-option">
                <input 
                    type="radio" 
                    id="status_contacted" 
                    name="status" 
                    value="contacted" 
                    {{ old('status', $enquiry->status) == 'contacted' ? 'checked' : '' }}
                >
                <label for="status_contacted">📞 Contacted</label>
            </div>

            <div class="status-option">
                <input 
                    type="radio" 
                    id="status_in_progress" 
                    name="status" 
                    value="in_progress" 
                    {{ old('status', $enquiry->status) == 'in_progress' ? 'checked' : '' }}
                >
                <label for="status_in_progress">⏳ In Progress</label>
            </div>

            <div class="status-option">
                <input 
                    type="radio" 
                    id="status_completed" 
                    name="status" 
                    value="completed" 
                    {{ old('status', $enquiry->status) == 'completed' ? 'checked' : '' }}
                >
                <label for="status_completed">✅ Completed</label>
            </div>

            <div class="status-option">
                <input 
                    type="radio" 
                    id="status_cancelled" 
                    name="status" 
                    value="cancelled" 
                    {{ old('status', $enquiry->status) == 'cancelled' ? 'checked' : '' }}
                >
                <label for="status_cancelled">❌ Cancelled</label>
            </div>
        </div>
        @error('status')
        <div class="error-message">{{ $message }}</div>
        @enderror
        <div class="form-help">Select the current status of this enquiry</div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                💾 Save Changes
            </button>
        </div>
    </form>
</div>
@endsection