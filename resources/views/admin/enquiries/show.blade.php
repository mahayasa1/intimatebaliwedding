@extends('admin.layouts.app')

@section('title', 'View Enquiry')
@section('page-title', 'Enquiry Details')

@push('styles')
<style>
    .detail-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .detail-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .detail-meta {
        color: #999;
        font-size: 0.9rem;
    }

    .badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-new {
        background: #fff3cd;
        color: #856404;
    }

    .badge-contacted {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-in_progress {
        background: #cce5ff;
        color: #004085;
    }

    .badge-completed {
        background: #d4edda;
        color: #155724;
    }

    .badge-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .detail-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 500;
    }

    .message-section {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #3498db;
    }

    .message-label {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .message-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #2c3e50;
        white-space: pre-wrap;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
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
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background: #c0392b;
    }

    @media (max-width: 768px) {
        .detail-card {
            padding: 1.5rem;
        }

        .detail-header {
            flex-direction: column;
            gap: 1rem;
        }

        .detail-grid {
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
<div class="detail-card">
    <div class="detail-header">
        <div>
            <h1 class="detail-title">Enquiry from {{ $enquiry->name }}</h1>
            <div class="detail-meta">
                Received on {{ $enquiry->created_at->format('F d, Y \a\t h:i A') }}
            </div>
        </div>
        <div>
            <span class="badge badge-{{ $enquiry->status }}">
                {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
            </span>
        </div>
    </div>

    <!-- Customer Information -->
    <h3 style="margin-bottom: 1rem; color: #2c3e50;">Customer Information</h3>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Full Name</div>
            <div class="detail-value">{{ $enquiry->name }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Email Address</div>
            <div class="detail-value">
                <a href="mailto:{{ $enquiry->email }}" style="color: #3498db; text-decoration: none;">
                    {{ $enquiry->email }}
                </a>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Phone Number</div>
            <div class="detail-value">
                @if($enquiry->phone)
                <a href="tel:{{ $enquiry->phone }}" style="color: #3498db; text-decoration: none;">
                    {{ $enquiry->phone }}
                </a>
                @else
                <span style="color: #999;">Not provided</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Wedding Details -->
    <h3 style="margin: 2rem 0 1rem; color: #2c3e50;">Wedding Details</h3>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Wedding Date</div>
            <div class="detail-value">
                {{ $enquiry->wedding_date ?: 'Not specified' }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Wedding Type</div>
            <div class="detail-value">
                {{ $enquiry->wedding_type ?: 'Not specified' }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Guest Count</div>
            <div class="detail-value">
                {{ $enquiry->guest_count ? $enquiry->guest_count . ' guests' : 'Not specified' }}
            </div>
        </div>
    </div>

    <!-- Message -->
    <h3 style="margin: 2rem 0 1rem; color: #2c3e50;">Message</h3>
    <div class="message-section">
        <div class="message-content">{{ $enquiry->message }}</div>
    </div>

    <!-- Timeline -->
    <h3 style="margin: 2rem 0 1rem; color: #2c3e50;">Timeline</h3>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Created At</div>
            <div class="detail-value">{{ $enquiry->created_at->format('M d, Y h:i A') }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Last Updated</div>
            <div class="detail-value">{{ $enquiry->updated_at->format('M d, Y h:i A') }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Time Since Received</div>
            <div class="detail-value">{{ $enquiry->created_at->diffForHumans() }}</div>
        </div>
    </div>

    <!-- Actions -->
    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #f0f0f0;">
        <div class="action-buttons">
            <a href="{{ route('admin.enquiries.index') }}" class="btn btn-secondary">
                ← Back to List
            </a>
            <a href="{{ route('admin.enquiries.edit', $enquiry) }}" class="btn btn-primary">
                ✏️ Edit Status
            </a>
            <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this enquiry? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection