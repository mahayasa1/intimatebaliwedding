@extends('admin.layouts.app')

@section('title', 'View Enquiry')
@section('page-title', 'Enquiry Details')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header Card */
    .detail-header-card {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(139, 115, 85, 0.25);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .detail-header-card::before {
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

    .detail-header-content {
        position: relative;
        z-index: 1;
    }

    .detail-header-top {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1.5rem;
    }

    .detail-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .detail-meta {
        color: rgba(255,255,255,0.8);
        font-family: 'Work Sans', sans-serif;
        font-size: 0.95rem;
    }

    .status-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: 'Work Sans', sans-serif;
        letter-spacing: 0.3px;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .status-badge-large::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }

    .status-badge-large.status-new {
        color: #f57f17;
    }

    .status-badge-large.status-new::before {
        background: #f57f17;
    }

    .status-badge-large.status-contacted {
        color: #0d47a1;
    }

    .status-badge-large.status-contacted::before {
        background: #0d47a1;
    }

    .status-badge-large.status-in_progress {
        color: #311b92;
    }

    .status-badge-large.status-in_progress::before {
        background: #311b92;
    }

    .status-badge-large.status-completed {
        color: #1b5e20;
    }

    .status-badge-large.status-completed::before {
        background: #1b5e20;
    }

    .status-badge-large.status-cancelled {
        color: #880e4f;
    }

    .status-badge-large.status-cancelled::before {
        background: #880e4f;
    }

    .detail-quick-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .quick-action-btn {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-size: 0.9rem;
    }

    .quick-action-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .detail-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-title-icon {
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

    /* Info Grid */
    .info-grid {
        display: grid;
        gap: 1.5rem;
    }

    .info-item {
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .info-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.8rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .info-value {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.1rem;
        color: #1a1a1a;
        font-weight: 500;
        word-break: break-word;
    }

    .info-value a {
        color: #8B7355;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .info-value a:hover {
        color: #6B5644;
        text-decoration: underline;
    }

    /* Message Section */
    .message-card {
        grid-column: 1 / -1;
        padding: 2.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 16px;
        border-left: 4px solid #8B7355;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .message-content {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        white-space: pre-wrap;
    }

    /* Timeline */
    .timeline-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        border-radius: 10px;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .timeline-value {
        font-family: 'Work Sans', sans-serif;
        font-size: 1rem;
        color: #1a1a1a;
        font-weight: 500;
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
        padding: 0.85rem 1.75rem;
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

    .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    /* Responsive */
    @media (max-width: 968px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .message-card {
            grid-column: 1;
        }
    }

    @media (max-width: 768px) {
        .detail-header-card {
            padding: 2rem 1.5rem;
        }

        .detail-title {
            font-size: 1.5rem;
        }

        .detail-header-top {
            flex-direction: column;
            gap: 1rem;
        }

        .detail-quick-actions {
            flex-direction: column;
        }

        .quick-action-btn {
            width: 100%;
            justify-content: center;
        }

        .detail-card {
            padding: 1.5rem;
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
<div class="detail-container">
    <!-- Header Card -->
    <div class="detail-header-card">
        <div class="detail-header-content">
            <div class="detail-header-top">
                <div>
                    <h1 class="detail-title">{{ $enquiry->name }}</h1>
                    <div class="detail-meta">
                        📅 Received on {{ $enquiry->created_at->format('F d, Y \a\t h:i A') }}
                    </div>
                </div>
                <div>
                    <span class="status-badge-large status-{{ $enquiry->status }}">
                        {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                    </span>
                </div>
            </div>
            
            <div class="detail-quick-actions">
                <a href="mailto:{{ $enquiry->email }}" class="quick-action-btn">
                    ✉️ Email Customer
                </a>
                @if($enquiry->phone)
                <a href="tel:{{ $enquiry->phone }}" class="quick-action-btn">
                    📞 Call Customer
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Customer Information -->
        <div class="detail-card">
            <h3 class="card-title">
                <span class="card-title-icon">👤</span>
                Customer Information
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $enquiry->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">
                        <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">
                        @if($enquiry->phone)
                        <a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
                        @else
                        <span style="color: #999;">Not provided</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Wedding Details -->
        <div class="detail-card">
            <h3 class="card-title">
                <span class="card-title-icon">💍</span>
                Wedding Details
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Wedding Date</div>
                    <div class="info-value">
                        {{ $enquiry->wedding_date ?: 'Not specified' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Wedding Type</div>
                    <div class="info-value">
                        {{ $enquiry->wedding_type ?: 'Not specified' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Guest Count</div>
                    <div class="info-value">
                        {{ $enquiry->guest_count ? $enquiry->guest_count . ' guests' : 'Not specified' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Message -->
        <div class="message-card">
            <h3 class="card-title">
                <span class="card-title-icon">💬</span>
                Customer Message
            </h3>
            <div class="message-content">{{ $enquiry->message }}</div>
        </div>

        <!-- Timeline -->
        <div class="detail-card" style="grid-column: 1 / -1;">
            <h3 class="card-title">
                <span class="card-title-icon">⏱️</span>
                Timeline
            </h3>
            <div>
                <div class="timeline-item">
                    <div class="timeline-icon">📝</div>
                    <div class="timeline-content">
                        <div class="timeline-label">Created At</div>
                        <div class="timeline-value">{{ $enquiry->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">🔄</div>
                    <div class="timeline-content">
                        <div class="timeline-label">Last Updated</div>
                        <div class="timeline-value">{{ $enquiry->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">⏳</div>
                    <div class="timeline-content">
                        <div class="timeline-label">Time Since Received</div>
                        <div class="timeline-value">{{ $enquiry->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="action-section">
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