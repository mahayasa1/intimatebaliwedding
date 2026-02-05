@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .stat-icon.blue { background: #e3f2fd; }
    .stat-icon.green { background: #e8f5e9; }
    .stat-icon.purple { background: #f3e5f5; }
    .stat-icon.orange { background: #fff3e0; }
    .stat-icon.teal { background: #e0f2f1; }
    .stat-icon.red { background: #ffebee; }

    .stat-details {
        flex: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .card-link {
        color: #3498db;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .card-link:hover {
        text-decoration: underline;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        text-align: left;
        padding: 0.75rem;
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.875rem;
        color: #666;
        border-bottom: 2px solid #eee;
    }

    table td {
        padding: 0.875rem 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }

    table tr:hover {
        background: #f8f9fa;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
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

    .badge-in-progress {
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

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #999;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 0.85rem;
        }

        table th,
        table td {
            padding: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">⚙️</div>
        <div class="stat-details">
            <div class="stat-label">Total Services</div>
            <div class="stat-value">{{ $stats['services'] }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">📦</div>
        <div class="stat-details">
            <div class="stat-label">Total Packages</div>
            <div class="stat-value">{{ $stats['packages'] }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">🖼️</div>
        <div class="stat-details">
            <div class="stat-label">Gallery Images</div>
            <div class="stat-value">{{ $stats['galleries'] }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">📝</div>
        <div class="stat-details">
            <div class="stat-label">Blog Posts</div>
            <div class="stat-value">{{ $stats['blogs'] }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon teal">✉️</div>
        <div class="stat-details">
            <div class="stat-label">Total Enquiries</div>
            <div class="stat-value">{{ $stats['enquiries'] }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">🔔</div>
        <div class="stat-details">
            <div class="stat-label">New Enquiries</div>
            <div class="stat-value">{{ $stats['new_enquiries'] }}</div>
        </div>
    </div>
</div>

<!-- Recent Enquiries -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Enquiries</h3>
        <a href="{{ route('admin.enquiries.index') }}" class="card-link">View All →</a>
    </div>

    @if($recentEnquiries->count() > 0)
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Wedding Date</th>
                    <th>Status</th>
                    <th>Date Received</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEnquiries as $enquiry)
                <tr>
                    <td><strong>{{ $enquiry->name }}</strong></td>
                    <td>{{ $enquiry->email }}</td>
                    <td>{{ $enquiry->wedding_date ?: 'Not specified' }}</td>
                    <td>
                        <span class="badge badge-{{ $enquiry->status }}">
                            {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                        </span>
                    </td>
                    <td>{{ $enquiry->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" style="color: #3498db; text-decoration: none;">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <p>No enquiries yet</p>
    </div>
    @endif
</div>

<!-- Recent Blog Posts -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Blog Posts</h3>
        <a href="{{ route('admin.blogs.create') }}" class="card-link">Create New →</a>
    </div>

    @if($recentBlogs->count() > 0)
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Published Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBlogs as $blog)
                <tr>
                    <td><strong>{{ $blog->title }}</strong></td>
                    <td>{{ $blog->author ?: 'Admin' }}</td>
                    <td>
                        @if($blog->is_published)
                        <span class="badge" style="background: #d4edda; color: #155724;">Published</span>
                        @else
                        <span class="badge" style="background: #f8d7da; color: #721c24;">Draft</span>
                        @endif
                    </td>
                    <td>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Not published' }}</td>
                    <td>
                        <a href="{{ route('admin.blogs.edit', $blog) }}" style="color: #3498db; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">📝</div>
        <p>No blog posts yet</p>
    </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="{{ route('admin.services.create') }}" style="padding: 1rem; background: #e3f2fd; border-radius: 8px; text-decoration: none; color: #1976d2; font-weight: 600; text-align: center; transition: all 0.3s;">
            + Add Service
        </a>
        <a href="{{ route('admin.packages.create') }}" style="padding: 1rem; background: #e8f5e9; border-radius: 8px; text-decoration: none; color: #388e3c; font-weight: 600; text-align: center; transition: all 0.3s;">
            + Add Package
        </a>
        <a href="{{ route('admin.galleries.create') }}" style="padding: 1rem; background: #f3e5f5; border-radius: 8px; text-decoration: none; color: #7b1fa2; font-weight: 600; text-align: center; transition: all 0.3s;">
            + Add Gallery
        </a>
        <a href="{{ route('admin.blogs.create') }}" style="padding: 1rem; background: #fff3e0; border-radius: 8px; text-decoration: none; color: #f57c00; font-weight: 600; text-align: center; transition: all 0.3s;">
            + Add Blog Post
        </a>
    </div>
</div>
@endsection