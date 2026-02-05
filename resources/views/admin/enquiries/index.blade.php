@extends('layouts.admin')

@section('title', 'Enquiries')
@section('page-title', 'Customer Enquiries')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .page-header {
        margin-bottom: 3rem;
    }

    .page-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .page-header p {
        font-family: 'Work Sans', sans-serif;
        color: #666;
        font-size: 1rem;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 1.75rem;
        border-radius: 16px;
        border: 1px solid #e8e8e8;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #8B7355, #6B5644);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(139, 115, 85, 0.15);
        border-color: #8B7355;
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
    }

    .stat-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.85rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1;
    }

    /* Filter Tabs */
    .filter-section {
        margin-bottom: 2rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e8e8e8;
    }

    .filter-tab {
        font-family: 'Work Sans', sans-serif;
        padding: 0.65rem 1.25rem;
        border: none;
        background: transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-weight: 500;
        text-decoration: none;
        color: #666;
        font-size: 0.9rem;
        position: relative;
    }

    .filter-tab:hover {
        color: #8B7355;
        background: rgba(139, 115, 85, 0.08);
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.25);
        transform: translateY(-2px);
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'Work Sans', sans-serif;
    }

    table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    table th {
        text-align: left;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e8e8e8;
        white-space: nowrap;
    }

    table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    table tbody tr:hover {
        background: linear-gradient(135deg, #fafbfc 0%, #f8f9fa 100%);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    table td {
        padding: 1.25rem 1.5rem;
        vertical-align: top;
        color: #333;
    }

    /* Status Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        font-family: 'Work Sans', sans-serif;
        letter-spacing: 0.3px;
    }

    .badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }

    .badge-new {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        color: #f57f17;
        border: 1px solid #ffd54f;
    }

    .badge-new::before {
        background: #f57f17;
    }

    .badge-contacted {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #0d47a1;
        border: 1px solid #90caf9;
    }

    .badge-contacted::before {
        background: #0d47a1;
    }

    .badge-in_progress {
        background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
        color: #311b92;
        border: 1px solid #9fa8da;
    }

    .badge-in_progress::before {
        background: #311b92;
    }

    .badge-completed {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        color: #1b5e20;
        border: 1px solid #81c784;
    }

    .badge-completed::before {
        background: #1b5e20;
    }

    .badge-cancelled {
        background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
        color: #880e4f;
        border: 1px solid #f48fb1;
    }

    .badge-cancelled::before {
        background: #880e4f;
    }

    /* Contact Info */
    .contact-info {
        font-size: 0.9rem;
    }

    .contact-name {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
        font-family: 'Work Sans', sans-serif;
    }

    .contact-detail {
        color: #666;
        margin-bottom: 0.15rem;
    }

    /* Date Display */
    .date-display {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
        font-family: 'Work Sans', sans-serif;
    }

    .time-display {
        font-size: 0.85rem;
        color: #999;
    }

    /* Wedding Details */
    .wedding-details {
        font-size: 0.9rem;
    }

    .wedding-detail-item {
        margin-bottom: 0.35rem;
        color: #666;
    }

    .wedding-detail-label {
        font-weight: 600;
        color: #333;
    }

    /* Message Preview */
    .message-preview {
        max-width: 350px;
        font-size: 0.9rem;
        color: #666;
        line-height: 1.6;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn {
        font-family: 'Work Sans', sans-serif;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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

    .btn-success {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(46, 204, 113, 0.2);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
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

    .btn-sm {
        padding: 0.4rem 0.85rem;
        font-size: 0.8rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
        filter: grayscale(1);
    }

    .empty-state h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        color: #1a1a1a;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        font-size: 1rem;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
        padding: 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-tabs {
            padding: 0.25rem;
        }

        .filter-tab {
            font-size: 0.85rem;
            padding: 0.5rem 0.85rem;
        }

        table {
            font-size: 0.85rem;
        }

        table th,
        table td {
            padding: 1rem 0.75rem;
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
<div class="page-header">
    <h1>Customer Enquiries</h1>
    <p>Manage customer wedding enquiries and inquiries</p>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-label">Total Enquiries</div>
        <div class="stat-value">{{ $enquiries->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🆕</div>
        <div class="stat-label">New</div>
        <div class="stat-value">{{ $enquiries->where('status', 'new')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📞</div>
        <div class="stat-label">In Progress</div>
        <div class="stat-value">{{ $enquiries->where('status', 'in_progress')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Completed</div>
        <div class="stat-value">{{ $enquiries->where('status', 'completed')->count() }}</div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-section">
    <div class="filter-tabs">
        <a href="{{ route('admin.enquiries.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
            All ({{ $enquiries->total() }})
        </a>
        <a href="{{ route('admin.enquiries.index', ['status' => 'new']) }}" class="filter-tab {{ request('status') == 'new' ? 'active' : '' }}">
            New
        </a>
        <a href="{{ route('admin.enquiries.index', ['status' => 'contacted']) }}" class="filter-tab {{ request('status') == 'contacted' ? 'active' : '' }}">
            Contacted
        </a>
        <a href="{{ route('admin.enquiries.index', ['status' => 'in_progress']) }}" class="filter-tab {{ request('status') == 'in_progress' ? 'active' : '' }}">
            In Progress
        </a>
        <a href="{{ route('admin.enquiries.index', ['status' => 'completed']) }}" class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
            Completed
        </a>
        <a href="{{ route('admin.enquiries.index', ['status' => 'cancelled']) }}" class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
            Cancelled
        </a>
    </div>
</div>

<!-- Enquiries Table -->
<div class="table-card">
    @if($enquiries->count() > 0)
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer Info</th>
                    <th>Wedding Details</th>
                    <th>Status</th>
                    <th>Message Preview</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enquiries as $enquiry)
                <tr>
                    <td>
                        <div class="date-display">{{ $enquiry->created_at->format('M d, Y') }}</div>
                        <div class="time-display">{{ $enquiry->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="contact-info">
                            <div class="contact-name">{{ $enquiry->name }}</div>
                            <div class="contact-detail">{{ $enquiry->email }}</div>
                            @if($enquiry->phone)
                            <div class="contact-detail">{{ $enquiry->phone }}</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="wedding-details">
                            @if($enquiry->wedding_date)
                            <div class="wedding-detail-item">
                                <span class="wedding-detail-label">Date:</span> {{ $enquiry->wedding_date }}
                            </div>
                            @endif
                            @if($enquiry->wedding_type)
                            <div class="wedding-detail-item">
                                <span class="wedding-detail-label">Type:</span> {{ $enquiry->wedding_type }}
                            </div>
                            @endif
                            @if($enquiry->guest_count)
                            <div class="wedding-detail-item">
                                <span class="wedding-detail-label">Guests:</span> {{ $enquiry->guest_count }}
                            </div>
                            @endif
                            @if(!$enquiry->wedding_date && !$enquiry->wedding_type && !$enquiry->guest_count)
                            <span style="color: #999;">Not specified</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $enquiry->status }}">
                            {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                        </span>
                    </td>
                    <td>
                        <div class="message-preview">
                            {{ Str::limit($enquiry->message, 80) }}
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-primary btn-sm">
                                👁️ View
                            </a>
                            <a href="{{ route('admin.enquiries.edit', $enquiry) }}" class="btn btn-success btn-sm">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this enquiry?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($enquiries->hasPages())
    <div class="pagination">
        {{ $enquiries->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <h3>No Enquiries Found</h3>
        <p>There are no customer enquiries matching your filter.</p>
    </div>
    @endif
</div>
@endsection