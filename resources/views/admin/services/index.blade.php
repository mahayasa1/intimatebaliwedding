@extends('layouts.admin')

@section('title', 'Services')
@section('page-title', 'Wedding Services')

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
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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

    /* Service Cards Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .service-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        border-color: #8B7355;
    }

    .service-image-container {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .service-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .service-card:hover .service-image {
        transform: scale(1.05);
    }

    .service-image-overlay {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        display: flex;
        gap: 0.5rem;
    }

    .image-badge {
        padding: 0.4rem 0.75rem;
        background: rgba(0,0,0,0.75);
        color: white;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .service-content {
        padding: 1.5rem;
    }

    .service-package {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .service-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .service-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }

    .service-date {
        font-size: 0.8rem;
        color: #999;
    }

    .service-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Action Buttons */
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
        padding: 0.4rem 0.75rem;
        font-size: 0.75rem;
    }

    .btn-icon {
        padding: 0.5rem;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Filter Bar */
    .filter-bar {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 0.65rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        font-family: 'Work Sans', sans-serif;
        transition: all 0.3s ease;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
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
        margin-bottom: 2rem;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .services-grid {
            grid-template-columns: 1fr;
        }

        .filter-bar {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }

        .service-actions {
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
<!-- Page Header -->
<div class="page-header">
    <h1>Wedding Services</h1>
    <a href="{{ route('admin.services.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Add New Service
    </a>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Services</div>
            <div class="stat-icon"><i class="fas fa-cog"></i></div>
        </div>
        <div class="stat-value">{{ $services->total() }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">With Images</div>
            <div class="stat-icon"><i class="fas fa-images"></i></div>
        </div>
        <div class="stat-value">{{ $services->where('foto', '!=', null)->count() }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">This Month</div>
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
        <div class="stat-value">{{ $services->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="filter-group">
        <label for="searchInput">Search Services</label>
        <input type="text" id="searchInput" placeholder="Search by name or description...">
    </div>
    <div class="filter-group">
        <label for="packageFilter">Filter by Package</label>
        <select id="packageFilter">
            <option value="">All Packages</option>
            @foreach($services->pluck('package')->unique()->filter() as $package)
            <option value="{{ $package->name }}">{{ $package->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Services Grid -->
@if($services->count() > 0)
<div class="services-grid" id="servicesGrid">
    @foreach($services as $service)
    <div class="service-card" data-package="{{ $service->package->name ?? '' }}">
        <div class="service-image-container">
            @if($service->foto)
            <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->name }}" class="service-image">
            @else
            <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80" alt="{{ $service->name }}" class="service-image">
            @endif
            @if($service->foto)
            <div class="service-image-overlay">
                <span class="image-badge"><i class="fas fa-camera"></i> Image</span>
            </div>
            @endif
        </div>

        <div class="service-content">
            @if($service->package)
            <div class="service-package">{{ $service->package->name }}</div>
            @endif

            <h3 class="service-name">{{ $service->name }}</h3>

            @if($service->description)
            <p class="service-description">{{ $service->description }}</p>
            @endif

            <div class="service-footer">
                <div class="service-date">
                    {{ $service->created_at->format('M d, Y') }}
                </div>
                <div class="service-actions">
                    <a href="{{ route('admin.services.show', $service) }}" class="btn btn-primary btn-sm btn-icon" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-success btn-sm btn-icon" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this service?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
@if($services->hasPages())
<div class="pagination">
    {{ $services->links() }}
</div>
@endif
@else
<div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-cog"></i></div>
    <h3>No Services Yet</h3>
    <p>Start by creating your first wedding service</p>
    <a href="{{ route('admin.services.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Create First Service
    </a>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Search and Filter functionality
    const searchInput = document.getElementById('searchInput');
    const packageFilter = document.getElementById('packageFilter');
    const serviceCards = document.querySelectorAll('.service-card');

    function filterServices() {
        const searchValue = searchInput?.value.toLowerCase() || '';
        const packageValue = packageFilter?.value.toLowerCase() || '';

        serviceCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const packageName = card.dataset.package.toLowerCase();
            
            const matchesSearch = text.includes(searchValue);
            const matchesPackage = !packageValue || packageName.includes(packageValue);

            card.style.display = (matchesSearch && matchesPackage) ? '' : 'none';
        });
    }

    searchInput?.addEventListener('keyup', filterServices);
    packageFilter?.addEventListener('change', filterServices);
</script>
@endpush