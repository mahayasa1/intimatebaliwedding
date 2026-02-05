@extends('admin.layouts.app')

@section('title', 'Enquiries')
@section('page-title', 'Customer Enquiries')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 0.5rem 1rem;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        text-decoration: none;
        color: #666;
    }

    .filter-tab:hover {
        border-color: #3498db;
        color: #3498db;
    }

    .filter-tab.active {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }

    .card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        text-align: left;
        padding: 1rem 0.75rem;
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.875rem;
        color: #666;
        border-bottom: 2px solid #eee;
        white-space: nowrap;
    }

    table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: top;
    }

    table tr:hover {
        background: #f8f9fa;
    }

    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
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

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        display: inline-block;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .btn-success {
        background: #27ae60;
        color: white;
    }

    .btn-success:hover {
        background: #229954;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background: #c0392b;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #999;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .stats-mini {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .stat-mini {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stat-mini-icon {
        font-size: 1.5rem;
    }

    .stat-mini-label {
        font-size: 0.85rem;
        color: #999;
    }

    .stat-mini-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .contact-info {
        font-size: 0.9rem;
    }

    .contact-info div {
        margin-bottom: 0.25rem;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        table {
            font-size: 0.85rem;
        }

        table th,
        table td {
            padding: 0.75rem 0.5rem;
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
<div class="page-header">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 600; margin-bottom: 0.5rem;">Customer Enquiries</h1>
        <p style="color: #666;">Manage customer wedding enquiries and inquiries</p>
    </div>
</div>

<!-- Statistics -->
<div class="stats-mini">
    <div class="stat-mini">
        <div class="stat-mini-icon">📋</div>
        <div>
            <div class="stat-mini-label">Total Enquiries</div>
            <div class="stat-mini-value">{{ $enquiries->total() }}</div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon">🆕</div>
        <div>
            <div class="stat-mini-label">New</div>
            <div class="stat-mini-value">{{ $enquiries->where('status', 'new')->count() }}</div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon">📞</div>
        <div>
            <div class="stat-mini-label">In Progress</div>
            <div class="stat-mini-value">{{ $enquiries->where('status', 'in_progress')->count() }}</div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon">✅</div>
        <div>
            <div class="stat-mini-label">Completed</div>
            <div class="stat-mini-value">{{ $enquiries->where('status', 'completed')->count() }}</div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
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

<!-- Enquiries Table -->
<div class="card">
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
                        <div style="font-weight: 600;">{{ $enquiry->created_at->format('M d, Y') }}</div>
                        <div style="font-size: 0.85rem; color: #999;">{{ $enquiry->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="contact-info">
                            <div style="font-weight: 600; color: #2c3e50;">{{ $enquiry->name }}</div>
                            <div style="color: #666;">{{ $enquiry->email }}</div>
                            @if($enquiry->phone)
                            <div style="color: #666;">{{ $enquiry->phone }}</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.9rem;">
                            @if($enquiry->wedding_date)
                            <div><strong>Date:</strong> {{ $enquiry->wedding_date }}</div>
                            @endif
                            @if($enquiry->wedding_type)
                            <div><strong>Type:</strong> {{ $enquiry->wedding_type }}</div>
                            @endif
                            @if($enquiry->guest_count)
                            <div><strong>Guests:</strong> {{ $enquiry->guest_count }}</div>
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
                        <div style="max-width: 300px; font-size: 0.9rem; color: #666;">
                            {{ Str::limit($enquiry->message, 80) }}
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-primary btn-sm">
                                View
                            </a>
                            <a href="{{ route('admin.enquiries.edit', $enquiry) }}" class="btn btn-success btn-sm">
                                Edit
                            </a>
                            <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this enquiry?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
    <div style="margin-top: 1.5rem;">
        {{ $enquiries->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <h3 style="margin-bottom: 0.5rem;">No Enquiries Found</h3>
        <p>There are no customer enquiries yet.</p>
    </div>
    @endif
</div>
@endsection