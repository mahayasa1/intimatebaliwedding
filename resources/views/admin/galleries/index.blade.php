@extends('layouts.admin')

@section('title', 'Gallery')
@section('page-title', 'Gallery Management')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .btn-add {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    /* Search and Filter */
    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-input {
        font-family: 'Work Sans', sans-serif;
        width: 100%;
        padding: 0.875rem 1.25rem 0.875rem 3rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .view-toggle {
        display: flex;
        gap: 0.5rem;
    }

    .view-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #666;
    }

    .view-btn:hover,
    .view-btn.active {
        border-color: #8B7355;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
    }

    /* Gallery Grid View */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .gallery-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .gallery-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .gallery-image-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 100%;
        overflow: hidden;
        background: #f5f5f5;
    }

    .gallery-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-card:hover .gallery-image {
        transform: scale(1.05);
    }

    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        padding: 1rem;
    }

    .gallery-card:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-content {
        padding: 1.25rem;
    }

    .gallery-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .gallery-description {
        font-family: 'Work Sans', sans-serif;
        color: #666;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .gallery-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .meta-badge {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        background: #f0f0f0;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #666;
    }

    .gallery-actions {
        display: flex;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }

    .btn-icon {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.65rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        font-family: 'Work Sans', sans-serif;
    }

    .btn-view {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-edit {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    /* List View */
    .gallery-list {
        display: none;
    }

    .gallery-list.active {
        display: block;
    }

    .gallery-list-item {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1.5rem;
        align-items: center;
        transition: all 0.3s ease;
    }

    .gallery-list-item:hover {
        transform: translateX(8px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .list-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .list-content {
        flex: 1;
    }

    .list-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .list-actions .btn-icon {
        flex: initial;
        width: 40px;
        height: 40px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
        color: #8B7355;
    }

    .empty-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        margin-bottom: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .header-section {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .gallery-list-item {
            flex-direction: column;
        }

        .list-image {
            width: 100%;
            height: 200px;
        }

        .list-actions {
            width: 100%;
        }

        .list-actions .btn-icon {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="header-section">
    <h2 class="header-title">Gallery Collection</h2>
    <a href="{{ route('admin.galleries.create') }}" class="btn-add">
        <i class="fa-solid fa-plus"></i> Add New Photo
    </a>
</div>

<!-- Search and Filter -->
<div class="filter-section">
    <form action="{{ route('admin.galleries.index') }}" method="GET">
        <div class="filter-grid">
            <div class="search-box">
                <i class="fa-solid fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search gallery..."
                    value="{{ request('search') }}"
                >
            </div>
            <div class="view-toggle">
                <button type="button" class="view-btn active" id="grid-view-btn" title="Grid View">
                    <i class="fa-solid fa-grip"></i>
                </button>
                <button type="button" class="view-btn" id="list-view-btn" title="List View">
                    <i class="fa-solid fa-list"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Gallery Grid View -->
@if($galleries->count() > 0)
<div class="gallery-grid" id="gallery-grid">
    @foreach($galleries as $gallery)
    <div class="gallery-card">
        <div class="gallery-image-wrapper">
            <img src="{{ asset('storage/' . $gallery->foto) }}" alt="{{ $gallery->title }}" class="gallery-image">
            <div class="gallery-overlay"></div>
        </div>
        
        <div class="gallery-content">
            <h3 class="gallery-title">{{ $gallery->title }}</h3>
            
            @if($gallery->description)
            <p class="gallery-description">{{ $gallery->description }}</p>
            @endif
            
            <div class="gallery-meta">
                <span class="meta-badge">
                    <i class="fa-solid fa-calendar"></i>
                    {{ $gallery->created_at->format('M d, Y') }}
                </span>
            </div>
            
            <div class="gallery-actions">
                <a href="{{ route('admin.galleries.show', $gallery) }}" class="btn-icon btn-view">
                    <i class="fa-solid fa-eye"></i> View
                </a>
                <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn-icon btn-edit">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon btn-delete" style="width: 100%;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Gallery List View -->
<div class="gallery-list" id="gallery-list">
    @foreach($galleries as $gallery)
    <div class="gallery-list-item">
        <img src="{{ asset('storage/' . $gallery->foto) }}" alt="{{ $gallery->title }}" class="list-image">
        <div class="list-content">
            <h3 class="gallery-title">{{ $gallery->title }}</h3>
            @if($gallery->description)
            <p class="gallery-description">{{ $gallery->description }}</p>
            @endif
            <div class="gallery-meta">
                <span class="meta-badge">
                    <i class="fa-solid fa-calendar"></i>
                    {{ $gallery->created_at->format('M d, Y') }}
                </span>
            </div>
        </div>
        <div class="list-actions">
            <a href="{{ route('admin.galleries.show', $gallery) }}" class="btn-icon btn-view" title="View">
                <i class="fa-solid fa-eye"></i>
            </a>
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn-icon btn-edit" title="Edit">
                <i class="fa-solid fa-pen"></i>
            </a>
            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-icon btn-delete" title="Delete">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div style="margin-top: 2rem;">
    {{ $galleries->links() }}
</div>
@else
<div class="empty-state">
    <div class="empty-icon">
        <i class="fa-solid fa-images"></i>
    </div>
    <h3 class="empty-title">No Photos Found</h3>
    <p class="empty-text">
        @if(request('search'))
            No photos match your search criteria.
        @else
            Start building your gallery by adding your first photo.
        @endif
    </p>
    <a href="{{ route('admin.galleries.create') }}" class="btn-add">
        <i class="fa-solid fa-plus"></i> Add First Photo
    </a>
</div>
@endif
@endsection

@push('scripts')
<script>
    // View Toggle
    const gridViewBtn = document.getElementById('grid-view-btn');
    const listViewBtn = document.getElementById('list-view-btn');
    const galleryGrid = document.getElementById('gallery-grid');
    const galleryList = document.getElementById('gallery-list');

    gridViewBtn.addEventListener('click', function() {
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        galleryGrid.style.display = 'grid';
        galleryList.classList.remove('active');
        localStorage.setItem('galleryView', 'grid');
    });

    listViewBtn.addEventListener('click', function() {
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        galleryGrid.style.display = 'none';
        galleryList.classList.add('active');
        localStorage.setItem('galleryView', 'list');
    });

    // Restore view preference
    const savedView = localStorage.getItem('galleryView');
    if (savedView === 'list') {
        listViewBtn.click();
    }
</script>
@endpush