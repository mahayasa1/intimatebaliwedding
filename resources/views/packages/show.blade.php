@extends('layouts.app')
@php use App\Helpers\ImageHelper; @endphp

@section('title', $package->name . ' - Intimate Bali Wedding')

@section('og_title', $package->name . ' - Intimate Bali Wedding')
@section('og_description', Str::limit(strip_tags($package->description ?? ''), 160) ?: 'Paket pernikahan intimate di Bali, sesuaikan dengan impian Anda.')
@section('og_image', $package->image ? asset('storage/' . ImageHelper::thumb($package->image)) : asset('assets/Logo_IBW_2B.png'))
@section('og_type', 'article')

@push('styles')
<style>
    .package-detail-hero {
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
        font-size: 3rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 3px;
    }

    .package-detail-container {
        max-width: 1200px; margin: 0 auto; padding: 4rem 2rem; background: white;
    }

    .package-main-image {
        width: 100%; max-width: 600px; height: auto; max-height: 400px;
        object-fit: cover; margin: 0 auto 3rem; display: block;
        border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .package-header { text-align: center; margin-bottom: 2rem; }

    .package-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem; color: #333; margin-bottom: 1rem; line-height: 1.4;
    }

    .package-description {
        text-align: center; color: #666; line-height: 1.8; margin-bottom: 3rem;
        font-size: 1.05rem; max-width: 800px; margin-left: auto; margin-right: auto;
    }

    /* ===================== SUBPACKAGES SELECTOR ===================== */

.subpackages-section .section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.75rem; color: #1a1a1a; text-align: center;
    margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 2.5px;
}

.subpackages-section .section-subtitle {
    text-align: center; color: #999; font-size: 0.9rem;
    margin-bottom: 2.5rem; letter-spacing: 0.4px;
}

.subpackages-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    max-width: 1000px;
    margin: 0 auto;
}

/* Card subpackage — sama persis seperti package card */
.subpackage-card {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1/1;
    cursor: pointer;
    display: block;
    text-decoration: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.subpackage-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.subpackage-card-img-wrap {
    width: 100%;
    height: 100%;
    position: absolute;
    inset: 0;
}

.subpackage-card-img-wrap img {
    width: 100%; height: 100%; object-fit: cover; display: block;
    transition: transform 0.5s ease;
}

.subpackage-card:hover .subpackage-card-img-wrap img { transform: scale(1.05); }

.subpackage-card-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, #f5f0eb, #ede5d8);
    display: flex; align-items: center; justify-content: center;
    color: #D4AF37; font-size: 3rem;
}

.subpackage-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0; height: 100%;
    padding: 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 50%);
    color: white;
    display: flex; flex-direction: column; justify-content: flex-end;
    transition: background 0.3s ease;
    pointer-events: none;
    text-align: center;
}

.subpackage-overlay::before {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0; height: 0;
    background: rgba(0,0,0,0.356);
    transition: height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
}

.subpackage-card:hover .subpackage-overlay::before { height: 100%; }

.subpackage-content {
    position: relative; z-index: 2;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.subpackage-card:hover .subpackage-content { transform: translateY(-30%); }

.subpackage-type {
    font-size: 0.72rem; color: #D4AF37;
    text-transform: uppercase; letter-spacing: 2px;
    margin-bottom: 0.4rem; font-weight: 600;
}

.subpackage-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 1px; color: white;
}

.subpackage-desc-hover {
    font-size: 0.9rem; line-height: 1.6;
    margin: 0.8rem 0;
    max-height: 0; opacity: 0; overflow: hidden;
    transform: translateY(20px);
    transition: all 0.5s ease 0.2s; color: white;
}

.subpackage-card:hover .subpackage-desc-hover {
    max-height: 150px; opacity: 1; transform: translateY(0);
}

@media (max-width: 768px) {
    .subpackages-grid { grid-template-columns: 1fr; }
    .subpackage-card:hover .subpackage-content { transform: translateY(-20%); }
}

    /* Photo strip */
    .subpackage-photo-strip {
        display: flex; gap: 5px; padding: 0 1.5rem 1.3rem; flex-wrap: nowrap; overflow: hidden;
    }

    .subpackage-photo-strip img {
        width: 48px; height: 48px; object-fit: cover;
        border-radius: 6px; flex-shrink: 0;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        transition: transform 0.2s ease;
    }

    .subpackage-photo-strip img:hover { transform: scale(1.1); }

    .sub-photo-more {
        width: 48px; height: 48px; border-radius: 6px;
        background: rgba(212,175,55,0.12);
        display: flex; align-items: center; justify-content: center;
        color: #AA8B2A; font-weight: 700; font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* ===================== GALLERY ===================== */
    .package-gallery-section { margin: 4rem 0; }

    .gallery-title {
        font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #333;
        text-align: center; margin-bottom: 2rem;
        text-transform: uppercase; letter-spacing: 2px;
    }

    .package-gallery {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem; margin: 0 auto; max-width: 1200px;
    }

    .gallery-item {
        position: relative; overflow: hidden; border-radius: 12px; cursor: pointer;
        aspect-ratio: 4/3; background: #f0f0f0; transition: all 0.3s ease;
    }

    .gallery-item:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.2); }
    .gallery-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease; }
    .gallery-item:hover img { transform: scale(1.1); }

    /* ===================== LIGHTBOX ===================== */
    .lightbox {
        display: none; position: fixed; z-index: 9999;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); align-items: center; justify-content: center;
    }

    .lightbox.active { display: flex; }
    .lightbox-content { max-width: 90%; max-height: 90%; position: relative; }
    .lightbox-content img { max-width: 100%; max-height: 90vh; object-fit: contain; }

    .lightbox-close {
        position: absolute; top: 20px; right: 40px; font-size: 40px; color: white;
        cursor: pointer; z-index: 10000; transition: all 0.3s ease;
        background: rgba(0,0,0,0.5); width: 50px; height: 50px;
        display: flex; align-items: center; justify-content: center; border-radius: 50%;
    }

    .lightbox-close:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }

    .lightbox-nav {
        position: absolute; top: 50%; transform: translateY(-50%);
        font-size: 50px; color: white; cursor: pointer;
        background: rgba(0,0,0,0.5); width: 60px; height: 60px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; transition: all 0.3s ease; user-select: none;
    }

    .lightbox-nav:hover { background: rgba(255,255,255,0.2); }
    .lightbox-prev { left: 40px; }
    .lightbox-next { right: 40px; }

    .lightbox-counter {
        position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
        color: white; font-size: 16px; background: rgba(0,0,0,0.7);
        padding: 10px 20px; border-radius: 20px;
    }

    /* ===================== CTA ===================== */
    .enquiry-btn {
        display: inline-block;
        background: linear-gradient(135deg, #D4AF37 0%, #AA8B2A 100%);
        color: white; padding: 1rem 3rem; border-radius: 30px;
        text-decoration: none; font-weight: 600; transition: all 0.3s ease;
        margin: 2rem 0; text-align: center;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .enquiry-btn:hover {
        background: linear-gradient(135deg, #AA8B2A 0%, #D4AF37 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); color: white;
    }

    /* ===================== OTHER PACKAGES ===================== */
    .other-packages-section {
        background: #f8f8f8; padding: 4rem 2rem; margin-top: 2rem;
    }

    .other-packages-section h3 {
        font-family: 'Playfair Display', serif; font-size: 2rem; color: #333;
        text-align: center; margin-bottom: 3rem;
        text-transform: uppercase; letter-spacing: 2px;
    }

    .other-packages-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem; max-width: 1200px; margin: 0 auto;
    }

    .other-package-card {
        background: white; border-radius: 12px; overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease;
        text-decoration: none; display: block;
    }

    .other-package-card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.2); }
    .other-package-image { width: 100%; aspect-ratio: 4/3; object-fit: cover; }

    .other-package-content { padding: 1.5rem; text-align: center; }

    .other-package-title {
        font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #333; margin-bottom: 0.5rem;
    }

    .other-package-description { color: #666; font-size: 0.9rem; line-height: 1.5; }

    .btn-enquire {
        display: inline-flex; align-items: center; gap: 0.6rem;
        background: linear-gradient(135deg, #D4AF37 0%, #AA8B2A 100%);
        color: white; padding: 1rem 2.75rem;
        border-radius: 30px; text-decoration: none;
        font-weight: 700; font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 18px rgba(212,175,55,0.35);
    }

    .btn-enquire:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(212,175,55,0.45);
        color: white;
    }

    .btn-back {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: #AA8B2A; font-weight: 600; font-size: 0.9rem;
        text-decoration: none; margin-bottom: 1.5rem;
        transition: gap 0.2s ease;
    }

    .btn-back:hover { gap: 0.8rem; color: #D4AF37; }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 768px) {
        .package-detail-hero { height: 30vh; }
        .package-detail-hero h1 { font-size: 2rem; }
        .package-detail-container { padding: 2rem 1rem; }
        .package-main-image { max-width: 100%; max-height: 300px; margin-bottom: 2rem; }
        .package-header h2 { font-size: 1.5rem; }
        .subpackages-grid { grid-template-columns: 1fr; }
        .package-gallery { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
        .other-packages-grid { grid-template-columns: 1fr; }
        .lightbox-nav { width: 50px; height: 50px; font-size: 30px; }
        .lightbox-prev { left: 10px; } .lightbox-next { right: 10px; }
        .lightbox-close { top: 10px; right: 10px; width: 40px; height: 40px; font-size: 30px; }
    }

    @media (max-width: 480px) {
        .package-gallery { grid-template-columns: 1fr; }
        .package-main-image { max-height: 250px; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="package-detail-hero"
    style="
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
        url('{{ $package->image 
            ? asset('storage/' . ImageHelper::thumb($package->image)) 
            : 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=1920&q=80' 
        }}');
        background-size: cover;
        background-position: center;
    ">
    <div>
        <h1>{{ strtoupper($package->name) }}</h1>
    </div>
</section>

<!-- Package Detail Content -->
<section class="package-detail-container">
    {{-- @if($package->image)
    <img src="{{ asset('storage/' . ImageHelper::thumb($package->image)) }}"
         alt="{{ $package->name }}" class="package-main-image">
    @endif

    <div class="package-header">
        <h2>{{ $package->name }}</h2>
    </div>

    @if($package->description)
    <div class="package-description">
        {!! nl2br(e($package->description)) !!}
    </div>
    @endif --}}

    {{-- ============ SUBPACKAGES SECTION ============ --}}
    @if($package->subpackages && $package->subpackages->count() > 0)
    <div class="subpackages-section">
        <a href="{{ route('packages.public') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Packages
        </a>
        <h3 class="section-title">What's Available</h3>
        <p class="section-subtitle">Choose the option that best fits your dream wedding</p>
    
        <div class="subpackages-grid">
            @foreach($package->subpackages as $sub)
            <a href="{{ route('subpackages.show', [$package->id, $sub->id]) }}" class="subpackage-card">
                <div class="subpackage-card-img-wrap">
                    @if($sub->image)
                    <img src="{{ asset('storage/' . ImageHelper::thumb($sub->image)) }}" alt="{{ $sub->name }}">
                    @else
                    <div class="subpackage-card-placeholder"><i class="fas fa-gem"></i></div>
                    @endif
                </div>
                <div class="subpackage-overlay">
                    <div class="subpackage-content">
                        {{-- <div class="subpackage-type">{{ $package->name }}</div> --}}
                        <div class="subpackage-title">{{ $sub->name }}</div>
                        @if($sub->description)
                        <div class="subpackage-desc-hover">{{ Str::limit($sub->description, 120) }}</div>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ============ GALLERY ============ --}}
    @if(is_array($package->photo) && count($package->photo) > 0)
    <div class="package-gallery-section">
        <h3 class="gallery-title">Gallery</h3>
        <div class="package-gallery" id="packageGallery">
            @foreach($package->photo as $index => $photoPath)
            <div class="gallery-item" onclick="openLightbox({{ $index }})">
                <img src="{{ asset('storage/' . ImageHelper::thumb($photoPath)) }}"
                     alt="{{ $package->name }} - Photo {{ $index + 1 }}" loading="lazy">
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
            <img src="{{ asset('storage/' . ImageHelper::thumb($otherPackage->image)) }}"
                 alt="{{ $otherPackage->name }}" class="other-package-image">
            @else
            <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400&q=80"
                 alt="{{ $otherPackage->name }}" class="other-package-image">
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
const galleryPhotos = @json($package->photo);
let currentImageIndex = 0;

function openLightbox(index) {
    currentImageIndex = index;
    const img = document.getElementById('lightbox-img');
    img.src = '{{ asset("storage") }}/' + galleryPhotos[currentImageIndex];
    document.getElementById('lightbox').classList.add('active');
    updateCounter();
    document.body.style.overflow = 'hidden';
}

function closeLightbox(event) {
    if (!event || event.target.id === 'lightbox' || event.target.classList.contains('lightbox-close')) {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }
}

function changeImage(direction) {
    currentImageIndex += direction;
    if (currentImageIndex < 0) currentImageIndex = galleryPhotos.length - 1;
    else if (currentImageIndex >= galleryPhotos.length) currentImageIndex = 0;
    document.getElementById('lightbox-img').src = '{{ asset("storage") }}/' + galleryPhotos[currentImageIndex];
    updateCounter();
}

function updateCounter() {
    document.getElementById('current-index').textContent = currentImageIndex + 1;
}

document.addEventListener('keydown', function(e) {
    const lb = document.getElementById('lightbox');
    if (lb.classList.contains('active')) {
        if (e.key === 'ArrowLeft') changeImage(-1);
        if (e.key === 'ArrowRight') changeImage(1);
        if (e.key === 'Escape') closeLightbox();
    }
});

document.querySelectorAll('.gallery-item img').forEach(img => img.addEventListener('contextmenu', e => e.preventDefault()));
</script>
@endif
@endsection