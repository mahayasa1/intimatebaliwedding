@extends('layouts.app')

@section('title', $package->name . ' - Intimate Bali Wedding')

@push('styles')
<style>
    /* Hero Section with Background */
    .package-detail-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=1920&q=80');
        background-size: cover;
        background-position: center;
        height: 40vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-top: -80px;
        padding-top: 80px;
    }

    .package-detail-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
    }

    /* Main Content Container */
    .package-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
        background: white;
    }

    /* Package Image - REDUCED SIZE */
    .package-main-image {
        width: 100%;
        max-width: 600px;  /* Dikurangi dari 800px */
        height: auto;
        max-height: 400px;  /* Tambahkan batas tinggi */
        object-fit: cover;  /* Maintain aspect ratio */
        margin: 0 auto 3rem;
        display: block;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    /* Package Header */
    .package-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .package-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #333;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    /* Package Description */
    .package-description {
        text-align: center;
        color: #666;
        line-height: 1.8;
        margin-bottom: 3rem;
        font-size: 1.05rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Package Gallery Section */
    .package-gallery-section {
        margin: 4rem 0;
    }

    .gallery-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        color: #333;
        text-align: center;
        margin-bottom: 2rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* Grid Gallery - BETTER LAYOUT */
    .package-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin: 0 auto;
        max-width: 1200px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        cursor: pointer;
        aspect-ratio: 4/3;  /* Consistent aspect ratio */
        background: #f0f0f0;
        transition: all 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    /* Lightbox Modal */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
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
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 40px;
        font-size: 40px;
        color: white;
        cursor: pointer;
        z-index: 10000;
        transition: all 0.3s ease;
        background: rgba(0,0,0,0.5);
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .lightbox-close:hover {
        background: rgba(255,255,255,0.2);
        transform: rotate(90deg);
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 50px;
        color: white;
        cursor: pointer;
        background: rgba(0,0,0,0.5);
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
        user-select: none;
    }

    .lightbox-nav:hover {
        background: rgba(255,255,255,0.2);
    }

    .lightbox-prev {
        left: 40px;
    }

    .lightbox-next {
        right: 40px;
    }

    .lightbox-counter {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-size: 16px;
        background: rgba(0,0,0,0.7);
        padding: 10px 20px;
        border-radius: 20px;
    }

    /* Enquiry Button */
    .enquiry-btn {
        display: inline-block;
        background: linear-gradient(135deg, #D4AF37 0%, #AA8B2A 100%);
        color: white;
        padding: 1rem 3rem;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 2rem 0;
        text-align: center;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .enquiry-btn:hover {
        background: linear-gradient(135deg, #AA8B2A 0%, #D4AF37 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
        color: white;
    }

    /* Other Packages Section */
    .other-packages-section {
        background: #f8f8f8;
        padding: 4rem 2rem;
        margin-top: 2rem;
    }

    .other-packages-section h3 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #333;
        text-align: center;
        margin-bottom: 3rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .other-packages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .other-package-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
    }

    .other-package-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }

    .other-package-image {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
    }

    .other-package-content {
        padding: 1.5rem;
        text-align: center;
    }

    .other-package-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .other-package-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .package-gallery {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .package-detail-hero {
            height: 30vh;
        }

        .package-detail-hero h1 {
            font-size: 2rem;
        }

        .package-detail-container {
            padding: 2rem 1rem;
        }

        .package-main-image {
            max-width: 100%;
            max-height: 300px;
            margin-bottom: 2rem;
        }

        .package-header h2 {
            font-size: 1.5rem;
        }

        .package-gallery {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .other-packages-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .lightbox-nav {
            width: 50px;
            height: 50px;
            font-size: 30px;
        }

        .lightbox-prev {
            left: 10px;
        }

        .lightbox-next {
            right: 10px;
        }

        .lightbox-close {
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            font-size: 30px;
        }

        .enquiry-btn {
            padding: 0.875rem 2.5rem;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 480px) {
        .package-gallery {
            grid-template-columns: 1fr;
        }

        .package-main-image {
            max-height: 250px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="package-detail-hero">
    <div>
        <h1>{{ strtoupper($package->name) }}</h1>
    </div>
</section>

<!-- Package Detail Content -->
<section class="package-detail-container">
    <!-- Main Package Image -->
    @if($package->image)
    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="package-main-image">
    @endif

    <!-- Package Header -->
    <div class="package-header">
        <h2>{{ $package->name }}</h2>
    </div>

    <!-- Package Description -->
    @if($package->description)
    <div class="package-description">
        {!! nl2br(e($package->description)) !!}
    </div>
    @endif

    <!-- Package Gallery Photos -->
    @if(is_array($package->photo) && count($package->photo) > 0)
    <div class="package-gallery-section">
        <h3 class="gallery-title">Gallery</h3>
        
        <div class="package-gallery" id="packageGallery">
            @foreach($package->photo as $index => $photoPath)
            <div class="gallery-item" onclick="openLightbox({{ $index }})">
                <img src="{{ asset('storage/' . $photoPath) }}" 
                     alt="{{ $package->name }} - Photo {{ $index + 1 }}" 
                     loading="lazy">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Enquiry Button -->
    <div style="text-align: center;">
        <a href="{{ route('contact') }}" class="enquiry-btn">Make an Enquiry</a>
    </div>
</section>

<!-- Lightbox Modal -->
@if(is_array($package->photo) && count($package->photo) > 0)
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <span class="lightbox-prev" onclick="changeImage(-1)">&#10094;</span>
    <div class="lightbox-content">
        <img id="lightbox-img" src="" alt="">
    </div>
    <span class="lightbox-next" onclick="changeImage(1)">&#10095;</span>
    <div class="lightbox-counter">
        <span id="current-index">1</span> / <span id="total-images">{{ count($package->photo) }}</span>
    </div>
</div>
@endif

<!-- Other Packages Section -->
@if($otherPackages && $otherPackages->count() > 0)
<section class="other-packages-section">
    <h3>Other Packages</h3>
    
    <div class="other-packages-grid">
        @foreach($otherPackages as $otherPackage)
        <a href="{{ route('packages.show', $otherPackage->id) }}" class="other-package-card">
            @if($otherPackage->image)
            <img src="{{ asset('storage/' . $otherPackage->image) }}" alt="{{ $otherPackage->name }}" class="other-package-image">
            @else
            <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400&q=80" alt="{{ $otherPackage->name }}" class="other-package-image">
            @endif
            
            <div class="other-package-content">
                <h4 class="other-package-title">{{ $otherPackage->name }}</h4>
                @if($otherPackage->description)
                <p class="other-package-description">{{ Str::limit($otherPackage->description, 80) }}</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

@if(is_array($package->photo) && count($package->photo) > 0)
<script>
// Gallery photos data
const galleryPhotos = @json($package->photo);
let currentImageIndex = 0;

// Open lightbox
function openLightbox(index) {
    currentImageIndex = index;
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    
    img.src = '{{ asset("storage") }}/' + galleryPhotos[currentImageIndex];
    lightbox.classList.add('active');
    updateCounter();
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// Close lightbox
function closeLightbox(event) {
    // Close only if clicking outside the image or on close button
    if (!event || event.target.id === 'lightbox' || event.target.classList.contains('lightbox-close')) {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Change image
function changeImage(direction) {
    currentImageIndex += direction;
    
    // Loop around
    if (currentImageIndex < 0) {
        currentImageIndex = galleryPhotos.length - 1;
    } else if (currentImageIndex >= galleryPhotos.length) {
        currentImageIndex = 0;
    }
    
    const img = document.getElementById('lightbox-img');
    img.src = '{{ asset("storage") }}/' + galleryPhotos[currentImageIndex];
    updateCounter();
}

// Update counter
function updateCounter() {
    document.getElementById('current-index').textContent = currentImageIndex + 1;
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    if (lightbox.classList.contains('active')) {
        if (e.key === 'ArrowLeft') {
            changeImage(-1);
        } else if (e.key === 'ArrowRight') {
            changeImage(1);
        } else if (e.key === 'Escape') {
            closeLightbox();
        }
    }
});

// Prevent right-click on gallery images (optional)
document.querySelectorAll('.gallery-item img').forEach(img => {
    img.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
});
</script>
@endif
@endsection