@extends('layouts.admin')

@section('title', 'Packages')
@section('page-title', 'Wedding Packages')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .btn-add {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1.75rem;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    /* Stats Grid */
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
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
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

    .stat-card:hover::before { transform: scaleX(1); }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.25rem;
    }

    .stat-title {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.85rem;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.25);
    }

    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 2.75rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-right: 0.25rem;
    }

    .filter-tab {
        font-family: 'Work Sans', sans-serif;
        padding: 0.45rem 1rem;
        border: 1.5px solid #e0e0e0;
        background: white;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.25s ease;
        font-size: 0.82rem;
        font-weight: 500;
        color: #666;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .filter-tab:hover { border-color: #8B7355; color: #8B7355; }

    .filter-tab.active {
        background: linear-gradient(135deg, #8B7355, #6B5644);
        border-color: transparent;
        color: white;
        box-shadow: 0 2px 8px rgba(139,115,85,0.25);
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .table-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 0.65rem 1rem 0.65rem 2.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.9rem;
        font-family: 'Work Sans', sans-serif;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
    }

    .search-box::before {
        content: '\f002';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1rem;
        color: #999;
    }

    .table-responsive { overflow-x: auto; }

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
        vertical-align: middle;
        color: #333;
    }

    table tr:last-child td { border-bottom: none; }

    .package-info {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .package-thumbnail {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .package-details { flex: 1; }

    .package-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .package-description {
        color: #666;
        font-size: 0.85rem;
        line-height: 1.5;
        max-width: 360px;
    }

    /* Category Badge */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.9rem;
        background: linear-gradient(135deg, rgba(139,115,85,0.12), rgba(107,86,68,0.12));
        color: #8B7355;
        border: 1px solid rgba(139,115,85,0.25);
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .category-badge i { font-size: 0.65rem; }

    .photos-count {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #e8f4f8 0%, #d1ecf1 100%);
        color: #0d47a1;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid #90caf9;
    }

    .date-display { font-size: 0.9rem; color: #666; }

    /* Action Buttons */
    .action-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; }

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

    .btn-primary { background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%); color: white; box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3); }
    .btn-success { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; box-shadow: 0 2px 8px rgba(46, 204, 113, 0.2); }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3); }
    .btn-danger { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2); }
    .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3); }
    .btn-sm { padding: 0.4rem 0.85rem; font-size: 0.8rem; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-state-icon { font-size: 5rem; margin-bottom: 1.5rem; opacity: 0.3; filter: grayscale(1); }

    .empty-state h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem; color: #1a1a1a; margin-bottom: 0.75rem;
    }

    .empty-state p { font-family: 'Work Sans', sans-serif; color: #999; font-size: 1rem; margin-bottom: 2rem; }

    /* Pagination */
    .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; padding: 1.5rem; }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; gap: 1rem; align-items: flex-start; }
        .btn-add { width: 100%; justify-content: center; }
        .stats-grid { grid-template-columns: 1fr; }
        .table-header { flex-direction: column; gap: 1rem; }
        .search-box { width: 100%; }
        table { font-size: 0.85rem; }
        table th, table td { padding: 1rem 0.75rem; }
        .package-info { flex-direction: column; align-items: flex-start; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Wedding Packages</h1>
    <a href="{{ route('admin.packages.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Add New Package
    </a>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Packages</div>
            <div class="stat-icon"><i class="fas fa-box"></i></div>
        </div>
        <div class="stat-value">{{ $packages->total() }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Photos</div>
            <div class="stat-icon"><i class="fas fa-images"></i></div>
        </div>
        <div class="stat-value">{{ $packages->sum(function($p) { return is_array($p->photo) ? count($p->photo) : 0; }) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Categories</div>
            <div class="stat-icon"><i class="fas fa-tags"></i></div>
        </div>
        <div class="stat-value">{{ $packages->pluck('category')->filter()->unique()->count() }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">This Month</div>
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
        <div class="stat-value">{{ $packages->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
    </div>
</div>

<!-- Category Filter Tabs -->
@php
    $allCategories = $packages->pluck('category')->filter()->unique()->values();
@endphp

@if($allCategories->count() > 0)
<div class="filter-section">
    <span class="filter-label"><i class="fas fa-filter"></i> Filter:</span>
    <button class="filter-tab active" data-category="all">All ({{ $packages->total() }})</button>
    @foreach($allCategories as $cat)
    <button class="filter-tab" data-category="{{ $cat }}">
        <i class="fas fa-tag"></i> {{ $cat }}
    </button>
    @endforeach
</div>
@endif

<!-- Packages Table -->
<div class="table-card">
    <div class="table-header">
        <h3 class="table-title">All Packages</h3>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search packages...">
        </div>
    </div>

    @if($packages->count() > 0)
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Package Details</th>
                    <th>Category</th>
                    <th>Gallery Photos</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="packageTable">
                @foreach($packages as $package)
                <tr data-category="{{ $package->category ?? '' }}">
                    <td>
                        <div class="package-info">
                            @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}" 
                                 alt="{{ $package->name }}" 
                                 class="package-thumbnail">
                            @else
                            <div class="package-thumbnail" style="background: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: #999;"></i>
                            </div>
                            @endif
                            <div class="package-details">
                                <div class="package-name">{{ $package->name }}</div>
                                @if($package->description)
                                <div class="package-description">
                                    {{ Str::limit($package->description, 70) }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($package->category)
                        <span class="category-badge">
                            <i class="fas fa-tag"></i>
                            {{ $package->category }}
                        </span>
                        @else
                        <span style="color: #ccc; font-size: 0.85rem;">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $photoCount = is_array($package->photo) ? count($package->photo) : 0;
                        @endphp
                        <span class="photos-count">
                            <i class="fas fa-images"></i> 
                            {{ $photoCount }} Photo{{ $photoCount != 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td>
                        <div class="date-display">
                            {{ $package->created_at->format('M d, Y') }}
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.packages.show', $package) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this package?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($packages->hasPages())
    <div class="pagination">
        {{ $packages->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-box"></i></div>
        <h3>No Packages Yet</h3>
        <p>Start by creating your first wedding package</p>
        <a href="{{ route('admin.packages.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Create First Package
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#packageTable tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const activeCategory = document.querySelector('.filter-tab.active')?.dataset.category || 'all';
            const rowCat = row.dataset.category || '';
            const matchesSearch = text.includes(searchValue);
            const matchesFilter = activeCategory === 'all' || rowCat === activeCategory;
            row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
        });
    });

    // Category filter tabs
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const cat = this.dataset.category;
            const searchValue = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const rows = document.querySelectorAll('#packageTable tr');

            rows.forEach(row => {
                const rowCat = row.dataset.category || '';
                const text = row.textContent.toLowerCase();
                const matchesFilter = cat === 'all' || rowCat === cat;
                const matchesSearch = !searchValue || text.includes(searchValue);
                row.style.display = (matchesFilter && matchesSearch) ? '' : 'none';
            });
        });
    });
</script>
@endpush