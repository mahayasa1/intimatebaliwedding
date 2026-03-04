@extends('layouts.admin')

@section('title', 'Package Details')
@section('page-title', 'Package Details')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .detail-card {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 2rem;
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
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
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

    /* Main Image */
    .main-image-container {
        margin-bottom: 2rem;
        text-align: center;
    }

    .main-image {
        width: 100%;
        max-width: 600px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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

    .description-text {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        white-space: pre-wrap;
    }

    /* Photo Gallery Grid */
    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-item-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-item:hover .gallery-item-overlay {
        opacity: 1;
    }

    .gallery-item-overlay i {
        color: white;
        font-size: 2rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #999;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    /* Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        align-items: center;
        justify-content: center;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
    }

    .lightbox-content img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: rgba(255,255,255,0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .lightbox-close:hover {
        background: rgba(255,255,255,0.2);
        transform: scale(1.1);
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: rgba(255,255,255,0.1);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .lightbox-nav:hover {
        background: rgba(255,255,255,0.2);
    }

    .lightbox-prev {
        left: 20px;
    }

    .lightbox-next {
        right: 20px;
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
    @media (max-width: 768px) {
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

        .photo-gallery {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }

        .lightbox-nav {
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
        }

        .lightbox-prev {
            left: 10px;
        }

        .lightbox-next {
            right: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    <!-- Package Information -->
    <div class="detail-card">
        <h3 class="card-title">
            <div class="card-title-icon"><i class="fas fa-box"></i></div>
            Package Information
        </h3>

        @if($package->image)
        <div class="main-image-container">
            <img src="{{ asset('storage/' . $package->image) }}" 
                 alt="{{ $package->name }}" 
                 class="main-image">
        </div>
        @endif
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Package Name</div>
                <div class="info-value">{{ $package->name }}</div>
            </div>

            @if($package->description)
            <div class="info-item">
                <div class="info-label">Description</div>
                <div class="description-text">{{ $package->description }}</div>
            </div>
            @endif

            <div class="info-item">
                <div class="info-label">Gallery Photos</div>
                @php
                    $photoCount = is_array($package->photo) ? count($package->photo) : 0;
                @endphp
                <div class="info-value">{{ $photoCount }} Photo{{ $photoCount != 1 ? 's' : '' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $package->created_at->format('F d, Y \a\t h:i A') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $package->updated_at->format('F d, Y \a\t h:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Photo Gallery -->
    <div class="detail-card">
        <h3 class="card-title">
            <div class="card-title-icon"><i class="fas fa-images"></i></div>
            Photo Gallery
        </h3>
        
        @if(is_array($package->photo) && count($package->photo) > 0)
        <div class="photo-gallery">
            @foreach($package->photo as $index => $photoPath)
            <div class="gallery-item" onclick="openLightbox({{ $index }})">
                <img src="{{ asset('storage/' . $photoPath) }}" alt="Photo {{ $index + 1 }}">
                <div class="gallery-item-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-images"></i></div>
            <p>No gallery photos uploaded yet.</p>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="action-section">
        <div class="action-buttons">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                ← Back to List
            </a>
            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Package
            </a>
            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this package? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Delete Package
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-content">
        <span class="lightbox-close" onclick="closeLightbox(event)">
            <i class="fas fa-times"></i>
        </span>
        @if(is_array($package->photo) && count($package->photo) > 1)
        <span class="lightbox-nav lightbox-prev" onclick="event.stopPropagation(); changePhoto(-1)">
            <i class="fas fa-chevron-left"></i>
        </span>
        <span class="lightbox-nav lightbox-next" onclick="event.stopPropagation(); changePhoto(1)">
            <i class="fas fa-chevron-right"></i>
        </span>
        @endif
        <img id="lightboxImage" src="" alt="Gallery Photo">
    </div>
</div>

<script>
@if(is_array($package->photo) && count($package->photo) > 0)
const photos = @json(array_map(function($photoPath) { return asset('storage/' . $photoPath); }, $package->photo));
let currentPhotoIndex = 0;

function openLightbox(index) {
    currentPhotoIndex = index;
    document.getElementById('lightboxImage').src = photos[currentPhotoIndex];
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(event) {
    if (event.target.id === 'lightbox' || event.target.closest('.lightbox-close')) {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

function changePhoto(direction) {
    currentPhotoIndex += direction;
    
    if (currentPhotoIndex < 0) {
        currentPhotoIndex = photos.length - 1;
    } else if (currentPhotoIndex >= photos.length) {
        currentPhotoIndex = 0;
    }
    
    document.getElementById('lightboxImage').src = photos[currentPhotoIndex];
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').classList.contains('active')) {
        if (e.key === 'Escape') {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = 'auto';
        } else if (e.key === 'ArrowLeft') {
            changePhoto(-1);
        } else if (e.key === 'ArrowRight') {
            changePhoto(1);
        }
    }
});
@endif
</script>
@endsection