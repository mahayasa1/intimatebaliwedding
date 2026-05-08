@extends('layouts.app')
@php use App\Helpers\ImageHelper; @endphp

@section('title', 'Wedding Venue - Intimate Bali Wedding')

@push('styles')
<style>
   /* ===== HERO ===== */
.hero-section {
    position: relative;
    height: 100vh;
    min-height: 480px;
    overflow: hidden;
    margin-top: -80px;
}

.hero-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 1.5s ease-in-out; /* naikkan dari 1s ke 1.5s */
    will-change: opacity;
}

.hero-slide.active { opacity: 1; }

.hero-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.55));
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    color: white;
    text-align: center;
    z-index: 10;
    padding: clamp(1rem, 3vw, 1.5rem) clamp(1rem, 4vw, 1.5rem) clamp(2rem, 5vw, 4rem);
}

.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1rem, 4vw, 4rem);
    font-weight: 700;
    margin-bottom: clamp(0.4rem, 1vw, 0.75rem);
    letter-spacing: clamp(1px, 0.3vw, 2px);
    animation: fadeInUp 1s ease-out both;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: clamp(0.75rem, 2vw, 1.2rem);
    margin-bottom: clamp(1rem, 2.5vw, 2rem);
    letter-spacing: clamp(0.5px, 0.2vw, 1px);
    font-weight: 300;
    animation: fadeInUp 1s ease-out 0.2s both;
    max-width: clamp(280px, 60vw, 560px);
}

.hero-cta {
    animation: fadeInUp 1s ease-out 0.4s both;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ===== PACKAGE CARDS ===== */
.package-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(clamp(140px, 30vw, 260px), 1fr));
    gap: clamp(0.75rem, 2vw, 1.25rem);
    max-width: 1200px;
    margin: 2rem auto;
}

.package-card {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    cursor: pointer;
    text-decoration: none;
    display: block;
    border-radius: clamp(8px, 1.5vw, 12px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.package-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.package-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.package-card:hover img { transform: scale(1.05); }

.package-overlay {
    position: absolute;
    inset: 0;
    padding: clamp(1rem, 2.5vw, 1.5rem);
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 55%);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    transition: background 0.3s ease;
    text-align: center;
}

.package-overlay::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 0;
    background: rgba(0,0,0,0.32);
    transition: height 0.5s cubic-bezier(0.4,0,0.2,1);
    z-index: 1;
}

.package-card:hover .package-overlay::before { height: 100%; }

.package-content {
    position: relative;
    z-index: 2;
    transition: transform 0.5s cubic-bezier(0.4,0,0.2,1);
}

.package-card:hover .package-content { transform: translateY(-20%); }

.package-type {
    font-size: clamp(0.6rem, 1.2vw, 0.7rem);
    color: #D4AF37;
    text-transform: uppercase;
    letter-spacing: clamp(1px, 0.3vw, 2px);
    margin-bottom: clamp(0.25rem, 0.5vw, 0.4rem);
    font-weight: 600;
}

.package-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(0.85rem, 2.5vw, 1.6rem);
    font-weight: 600;
    margin-bottom: clamp(0.25rem, 0.5vw, 0.4rem);
    text-transform: uppercase;
    letter-spacing: clamp(0.5px, 0.2vw, 1px);
    line-height: 1.2;
}

.package-card p {
    font-size: clamp(0.75rem, 1.5vw, 0.85rem);
    line-height: 1.5;
    margin: clamp(0.4rem, 0.8vw, 0.6rem) 0 0;
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transform: translateY(16px);
    transition: all 0.5s ease 0.15s;
}

.package-card:hover p {
    max-height: 80px;
    opacity: 1;
    transform: translateY(0);
}

/* ===== GALLERY PREVIEW ===== */
.gallery-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(clamp(140px, 30vw, 260px), 1fr));
    gap: clamp(0.75rem, 2vw, 1.25rem);
    max-width: 1200px;
    margin: 2rem auto;
}

.gallery-preview-item video,
.gallery-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: rgba(255,255,255,.9);
    color: #e74c3c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: bold;
}

.gallery-preview-item {
    position: relative;
    overflow: hidden;
    border-radius: clamp(6px, 1vw, 8px);
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    aspect-ratio: 4 / 3;
}

.gallery-preview-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.gallery-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-preview-item:hover img { transform: scale(1.08); }

/* ===== BLOG PREVIEW ===== */
.blog-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(clamp(240px, 35vw, 320px), 1fr));
    gap: clamp(1rem, 2.5vw, 1.5rem);
    max-width: 1200px;
    margin: 2rem auto;
}

.blog-preview-card {
    background: white;
    border-radius: clamp(6px, 1vw, 8px);
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    display: block;
}

.blog-preview-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.blog-preview-image {
    width: 100%;
    height: clamp(160px, 20vw, 200px);
    object-fit: cover;
    display: block;
}

.blog-preview-content { padding: clamp(1rem, 2vw, 1.25rem); }

.blog-preview-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(0.95rem, 2vw, 1.3rem);
    color: #333;
    margin: 0 0 0.5rem;
}

.blog-preview-excerpt {
    color: #666;
    font-size: clamp(0.8rem, 1.5vw, 0.88rem);
    line-height: 1.6;
    margin-bottom: 0.75rem;
}

.blog-preview-date {
    color: #D4AF37;
    font-size: clamp(0.75rem, 1.3vw, 0.82rem);
}

/* ===== INSTAGRAM ===== */
.instagram-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: clamp(0.4rem, 1.5vw, 0.75rem);
}

.instagram-post-item {
    position: relative;
    display: block;
    overflow: hidden;
    border-radius: clamp(6px, 1vw, 8px);
    aspect-ratio: 1 / 1;
    text-decoration: none;
    background: #f0f0f0;
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), box-shadow 0.3s;
}

.instagram-post-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.instagram-post-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease, filter 0.4s ease;
}

.instagram-post-item:hover img {
    transform: scale(1.08);
    filter: brightness(1.08) contrast(1.04);
}

.media-type-icon {
    position: absolute;
    top: clamp(5px, 1vw, 8px);
    right: clamp(5px, 1vw, 8px);
    background: rgba(0,0,0,0.55);
    border-radius: 50%;
    width: clamp(24px, 4vw, 32px);
    height: clamp(24px, 4vw, 32px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    backdrop-filter: blur(8px);
}

.instagram-overlay-hover {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0);
    transition: background 0.3s ease, opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    gap: clamp(0.4rem, 1vw, 0.75rem);
}

.instagram-post-item:hover .instagram-overlay-hover {
    opacity: 1;
    background: rgba(0,0,0,0.68);
    backdrop-filter: blur(4px);
}

.instagram-stats {
    display: flex;
    gap: clamp(0.75rem, 2vw, 1.5rem);
    font-size: clamp(0.75rem, 1.5vw, 0.95rem);
    font-weight: 600;
}

.stat-item { display: flex; align-items: center; gap: 0.4rem; }

.btn-instagram {
    display: inline-flex;
    align-items: center;
    gap: clamp(0.4rem, 1vw, 0.6rem);
    padding: clamp(0.7rem, 1.5vw, 0.875rem) clamp(1.5rem, 3vw, 2.25rem);
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    font-size: clamp(0.8rem, 2vw, 1.1rem);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 15px rgba(188,24,136,0.3);
}

.btn-instagram:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 8px 25px rgba(188,24,136,0.5);
    color: white;
}

/* ===== TESTIMONIALS ===== */
.testimonial-section {
    background: #f8f8f8;
    padding: clamp(3rem, 6vw, 5rem) clamp(1rem, 3vw, 1.5rem);
    overflow: hidden;
}

.testimonials-header {
    text-align: center;
    margin-bottom: clamp(1.5rem, 3vw, 2.5rem);
}

.testimonials-header h2 {
    font-family: 'Playfair Display', serif;
    color: #D4AF37;
    font-size: clamp(1.4rem, 3.5vw, 2.5rem);
    margin-bottom: 0.75rem;
}

.testimonials-slider-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 clamp(28px, 5vw, 52px);
}

.testimonials-slider-wrapper { overflow: hidden; }

.testimonials-slider {
    display: flex;
    gap: clamp(1rem, 2vw, 1.5rem);
    transition: transform 0.5s ease-in-out;
}

.testimonial-card {
    background: white;
    border-radius: clamp(8px, 1.5vw, 12px);
    padding: clamp(1.25rem, 2.5vw, 2rem);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    min-width: clamp(200px, 40vw, 320px);
    flex-shrink: 0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-left: 4px solid #D4AF37;
}

.testimonial-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.13);
}

.testimonial-header-content {
    display: flex;
    align-items: center;
    gap: clamp(0.6rem, 1.5vw, 0.875rem);
    margin-bottom: clamp(0.75rem, 2vw, 1.25rem);
}

.testimonial-avatar {
    width: clamp(40px, 6vw, 54px);
    height: clamp(40px, 6vw, 54px);
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #D4AF37;
    flex-shrink: 0;
}

.testimonial-info { flex: 1; }

.testimonial-info h4 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(0.9rem, 1.8vw, 1.05rem);
    margin: 0 0 0.2rem;
    color: #333;
}

.testimonial-time {
    font-size: clamp(0.7rem, 1.2vw, 0.78rem);
    color: #999;
}

.testimonial-rating {
    color: #D4AF37;
    font-size: clamp(1rem, 2vw, 1.2rem);
    margin-bottom: clamp(0.6rem, 1.5vw, 0.875rem);
    letter-spacing: 2px;
}

.testimonial-review {
    color: #555;
    line-height: 1.8;
    font-size: clamp(0.82rem, 1.5vw, 0.95rem);
    font-style: italic;
    position: relative;
    padding-left: clamp(0.75rem, 2vw, 1.25rem);
}

.testimonial-review::before {
    content: '"';
    position: absolute;
    left: 0;
    top: -8px;
    font-size: clamp(1.8rem, 3.5vw, 2.5rem);
    color: #D4AF37;
    opacity: 0.3;
    font-family: Georgia, serif;
    line-height: 1;
}

.slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: clamp(32px, 5vw, 44px);
    height: clamp(32px, 5vw, 44px);
    background: white;
    border: 2px solid #D4AF37;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease, color 0.3s ease;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.slider-nav:hover {
    background: #D4AF37;
    color: white;
    transform: translateY(-50%) scale(1.08);
}

.slider-nav.prev { left: 0; }
.slider-nav.next { right: 0; }

.slider-nav svg {
    width: clamp(16px, 2.5vw, 22px);
    height: clamp(16px, 2.5vw, 22px);
    fill: currentColor;
}

.slider-dots {
    display: flex;
    justify-content: center;
    gap: 0.4rem;
    margin-top: clamp(1.5rem, 3vw, 2.5rem);
}

.slider-dot {
    width: clamp(8px, 1.5vw, 10px);
    height: clamp(8px, 1.5vw, 10px);
    border-radius: 50%;
    background: #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    background: #D4AF37;
    width: clamp(20px, 4vw, 28px);
    border-radius: 5px;
}

.testimonials-cta {
    text-align: center;
    margin-top: clamp(1.5rem, 3vw, 2.5rem);
}

.btn-google-maps {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: clamp(0.7rem, 1.5vw, 0.875rem) clamp(1.25rem, 2.5vw, 1.75rem);
    background: #4285f4;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    font-size: clamp(0.8rem, 1.8vw, 1rem);
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 15px rgba(66,133,244,0.3);
}

.btn-google-maps:hover {
    background: #3367d6;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(66,133,244,0.4);
    color: white;
}

.no-reviews {
    text-align: center;
    padding: clamp(1.5rem, 4vw, 3rem);
    color: #999;
}
</style>
@endpush

@section('content')

{{-- ===================== PRELOAD HERO PERTAMA ===================== --}}
@if($heroSlides->isNotEmpty())
@php
    $firstSlide = $heroSlides->first();
    $isPublic = isset($firstSlide->is_public) && $firstSlide->is_public;
    $firstImgUrl = $isPublic
        ? asset($firstSlide->image)
        : asset('storage/' . ImageHelper::thumb(
            str_contains($firstSlide->image, 'storage/')
                ? ltrim(str_replace('storage/', '', $firstSlide->image), '/')
                : $firstSlide->image
          ));
@endphp
@push('preload')
<link rel="preload" as="image" href="{{ $firstImgUrl }}" fetchpriority="high">
@endpush
@endif

<!-- Hero Section -->
<section id="home" class="hero-section">
    @foreach($heroSlides as $index => $slide)
    @php
        $isPublic = isset($slide->is_public) && $slide->is_public;

        if ($isPublic) {
            $imgUrl = asset($slide->image);
        } else {
            $rawPath = str_contains($slide->image, 'storage/')
                ? ltrim(str_replace('storage/', '', $slide->image), '/')
                : $slide->image;
            $imgUrl = asset('storage/' . ImageHelper::thumb($rawPath));
        }
    @endphp
    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
        @if($index === 0)
            <img
                src="{{ $imgUrl }}"
                alt="Wedding Venue Bali"
                width="1920"
                height="1080"
                fetchpriority="high"
                style="width:100%;height:100%;object-fit:cover;object-position:center;"
            />
        @else
            <img
                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                data-src="{{ $imgUrl }}"
                alt="Wedding Venue Bali"
                width="1920"
                height="1080"
                style="width:100%;height:100%;object-fit:cover;object-position:center;"
                decoding="async"
            />
        @endif
    </div>
    @endforeach

    <div class="hero-overlay">
        <h1 class="hero-title">INTIMATE WEDDING IN BALI</h1>
        <p class="hero-subtitle">Creating Timeless Memories Amidst Nature's Splendor</p>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section">
    <div class="container">
        <h2 class="section-title">INTIMATE BALI WEDDING</h2>
        <p class="section-subtitle">
            Intimate Bali Wedding is a specialized wedding service company dedicated to wedding affairs, meticulously, and resourcefully wedding. We
            GUARANTEE THAT YOU ARE IN GOOD HANDS FROM BEGINNING TO END. PROFESSIONAL AND FLEXIBLE. We have worked on various
            projects around the world. Let us handle it!
        </p>
        <div style="text-align: center;">
            <a href="{{ route('about') }}" class="btn-primary">Learn More</a>
        </div>
    </div>
</section>

<!-- Wedding Package Section -->
<section id="packages" class="section" style="background: #f8f8f8;">
    <div class="container">
        <h2 class="section-title">WEDDING PACKAGE</h2>
        <p class="section-subtitle">All packages can be customised to suit your needs</p>

        <div class="package-grid">
            @foreach($packages->take(8) as $package)
            <a href="{{ route('packages.public') }}" class="package-card">
                <x-image
                    :src="$package->image ?? 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80'"
                    :alt="$package->name"
                    class=""
                />
                <div class="package-overlay">
                    <div class="package-content">
                        <h3 class="package-title">{{ $package->name }}</h3>
                        <p>{{ Str::limit($package->description, 80) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 2.5rem;">
            <a href="{{ route('packages.public') }}" class="btn-primary">View All Packages</a>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
<section id="gallery" class="section">
    <div class="container">
        <h2 class="section-title">GALLERY</h2>
        <p class="section-subtitle">Beautiful moments captured in paradise</p>

        @if($galleries->count() > 0)
        <div class="gallery-preview-grid">
            @foreach($galleries->take(8) as $gallery)
            <div class="gallery-preview-item">

                @if($gallery->type === 'video')

                    {{-- YOUTUBE --}}
                    @if(Str::contains($gallery->video_url, ['youtube.com', 'youtu.be']))
                        @php
                            preg_match('/(youtu\.be\/|v=)([^&]+)/', $gallery->video_url, $match);
                            $youtubeId = $match[2] ?? null;
                        @endphp
                        <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg"
                             alt="{{ $gallery->title }}"
                             loading="lazy"
                             decoding="async">

                    {{-- VIMEO --}}
                    @elseif(Str::contains($gallery->video_url, 'vimeo.com'))
                        <img src="{{ asset('images/video-placeholder.jpg') }}"
                             alt="{{ $gallery->title }}"
                             loading="lazy"
                             decoding="async">

                    {{-- LOCAL VIDEO --}}
                    @else
                        <video muted playsinline preload="none">
                            <source src="{{ asset('storage/' . $gallery->video_url) }}">
                        </video>
                    @endif

                    <div class="video-play-icon">▶</div>

                @else

                    <x-image
                        :src="$gallery->image"
                        :alt="$gallery->title ?? 'Gallery'"
                    />

                @endif

            </div>
            @endforeach
        </div>
        @endif

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('gallery.public') }}" class="btn-primary">View Full Gallery</a>
        </div>
    </div>
</section>

<!-- Instagram Feed Section -->
<section id="instagram" class="section" style="background: #f8f8f8;">
    <div class="container">
        <h2 class="section-title">FOLLOW US ON INSTAGRAM</h2>
        <p class="section-subtitle">Stay connected and see our latest moments</p>

        <div class="instagram-feed-wrapper" style="position: relative; max-width: 1200px; margin: 2rem auto;">
            <div class="instagram-feed-container" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <script src="https://snapwidget.com/js/snapwidget.js" defer></script>
                <iframe src="https://snapwidget.com/embed/1119589"
                        class="snapwidget-widget"
                        allowtransparency="true"
                        frameborder="0"
                        scrolling="no"
                        style="border:none; overflow:hidden; width:100%;"
                        title="Posts from Instagram"
                        loading="lazy">
                </iframe>
            </div>
            <div class="instagram-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 999; cursor: default;"></div>
        </div>

        <div style="text-align: center; margin-top: 2rem; position: relative; z-index: 1000; padding: 0 1rem;">
            <a href="https://instagram.com/intimatebaliwedding"
               target="_blank"
               rel="noopener"
               class="btn-instagram">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                Follow @intimatebaliwedding
            </a>
        </div>
    </div>
</section>

<!-- Blog Preview -->
@if($blogs->count() > 0)
<section class="section" style="background: #f8f8f8;">
    <div class="container">
        <h2 class="section-title">LATEST FROM OUR BLOG</h2>
        <p class="section-subtitle">Wedding tips, inspiration, and stories</p>

        <div class="blog-preview-grid">
            @foreach($blogs as $blog)
            @php $blogThumb = ImageHelper::thumb($blog->image); @endphp
            <a href="{{ route('blogs.show', $blog->slug) }}" class="blog-preview-card">
                <img
                    src="{{ asset('storage/' . $blogThumb) }}"
                    alt="{{ $blog->title }}"
                    class="blog-preview-image"
                    loading="lazy"
                    decoding="async"
                >
                <div class="blog-preview-content">
                    <h3 class="blog-preview-title">{{ $blog->title }}</h3>
                    <p class="blog-preview-excerpt">{{ Str::limit($blog->excerpt ?? strip_tags($blog->content), 100) }}</p>
                    <div class="blog-preview-date">{{ $blog->published_at->format('F d, Y') }}</div>
                </div>
            </a>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('blogs.public') }}" class="btn-primary">Read Our Blog</a>
        </div>
    </div>
</section>
@endif

<!-- Testimonials Section -->
<section id="testimonials" class="testimonial-section">
    <div class="testimonials-header">
        <h2>What Our Couples Say</h2>
    </div>

    @if(isset($googleReviews['success']) && $googleReviews['success'] && !empty($googleReviews['reviews']))
    <div class="testimonials-slider-container">
        <div class="slider-nav prev" onclick="testimonialMoveSlider(-1)" aria-label="Previous">
            <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>

        <div class="testimonials-slider-wrapper">
            <div class="testimonials-slider" id="testimonials-slider">
                @foreach($googleReviews['reviews'] as $review)
                <div class="testimonial-card">
                    <div class="testimonial-header-content">
                        @if(isset($review['author_photo']) && $review['author_photo'])
                        <x-image
                            :src="$review['author_photo']"
                            :alt="$review['author_name']"
                            class="testimonial-avatar"
                        />
                        @else
                        <div class="testimonial-avatar" style="background:#D4AF37; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:1.3rem;">
                            {{ strtoupper(substr($review['author_name'], 0, 1)) }}
                        </div>
                        @endif
                        <div class="testimonial-info">
                            <h4>{{ $review['author_name'] }}</h4>
                            <div class="testimonial-time">{{ $review['relative_time'] }}</div>
                        </div>
                    </div>

                    <div class="testimonial-rating">
                        {{ str_repeat('★', $review['rating']) }}{{ str_repeat('☆', 5 - $review['rating']) }}
                    </div>

                    <div class="testimonial-review">{{ $review['text'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="slider-nav next" onclick="testimonialMoveSlider(1)" aria-label="Next">
            <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    </div>

    <div class="slider-dots" id="slider-dots"></div>

    <div class="testimonials-cta">
        <a href="{{ $googleReviews['place_url'] ?? route('gallery.public') }}" target="_blank" class="btn-google-maps">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            See All Reviews on Google Maps
        </a>
    </div>
    @else
    <div class="no-reviews">
        <p>{{ $googleReviews['error'] ?? 'Reviews will appear here once Google Maps integration is configured.' }}</p>
    </div>
    @endif
</section>

<!-- CTA Section -->
<section class="section">
    <div class="container" style="text-align: center;">
        <h2 class="section-title">Ready to Start Planning?</h2>
        <p class="section-subtitle">
            Contact us today for a free consultation and let's create your dream wedding together
        </p>
        <a href="{{ route('contact') }}" class="btn-primary">Contact Us Now</a>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ========================================================
       HERO SLIDER
       ======================================================== */
    const heroSlides = document.querySelectorAll('.hero-slide');
    let currentHeroSlide = 0;

    function loadHeroSlideImage(slide) {
        const img = slide.querySelector('img[data-src]');
        if (!img) return; // slide pertama pakai x-image, tidak ada data-src
        if (img.dataset.src) {
            img.src = img.dataset.src;
            delete img.dataset.src;
        }
    }

    function goToHeroSlide(index) {
        const nextIndex = (index + heroSlides.length) % heroSlides.length;
        const nextSlide = heroSlides[nextIndex];
        const img = nextSlide.querySelector('img[data-src]');

        function doTransition() {
            heroSlides[currentHeroSlide].classList.remove('active');
            currentHeroSlide = nextIndex;
            heroSlides[currentHeroSlide].classList.add('active');
        }

        if (img && img.dataset.src) {
            // Load dulu, baru transisi
            img.onload = doTransition;
            img.onerror = doTransition; // tetap lanjut meski gagal
            img.src = img.dataset.src;
            delete img.dataset.src;
        } else {
            doTransition();
        }
    }

    // Preload slide ke-2 saat browser idle agar siap sebelum giliran tampil
    if (heroSlides.length > 1) {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => loadHeroSlideImage(heroSlides[1]));
        } else {
            setTimeout(() => loadHeroSlideImage(heroSlides[1]), 2000);
        }

        setInterval(() => goToHeroSlide(currentHeroSlide + 1), 4000);
    }

    /* ========================================================
       TESTIMONIAL SLIDER
       ======================================================== */
    const testimonialSlider = document.getElementById('testimonials-slider');
    if (!testimonialSlider) return;

    const testimonialCards = testimonialSlider.querySelectorAll('.testimonial-card');
    if (!testimonialCards.length) return;

    let currentTestimonialIndex = 0;
    let cardsPerView = getCardsPerView();
    let testimonialInterval;

    function getCardsPerView() {
        const w = window.innerWidth;
        if (w < 640)  return 1;
        if (w < 1024) return 2;
        return 3;
    }

    function updateSliderPosition() {
        const card    = testimonialCards[0];
        const gap     = parseInt(getComputedStyle(testimonialSlider).gap) || 24;
        const moveAmt = (card.offsetWidth + gap) * cardsPerView;
        testimonialSlider.style.transform = `translateX(${-(currentTestimonialIndex * moveAmt)}px)`;
    }

    function maxTestimonialIndex() {
        return Math.max(0, Math.ceil(testimonialCards.length / cardsPerView) - 1);
    }

    function createDots() {
        const container = document.getElementById('slider-dots');
        if (!container) return;
        container.innerHTML = '';
        const total = Math.ceil(testimonialCards.length / cardsPerView);
        for (let i = 0; i < total; i++) {
            const dot = document.createElement('div');
            dot.className = 'slider-dot' + (i === currentTestimonialIndex ? ' active' : '');
            dot.addEventListener('click', () => goToTestimonialSlide(i));
            container.appendChild(dot);
        }
    }

    function updateDots() {
        document.querySelectorAll('.slider-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === currentTestimonialIndex);
        });
    }

    function goToTestimonialSlide(index) {
        currentTestimonialIndex = Math.max(0, Math.min(index, maxTestimonialIndex()));
        updateSliderPosition();
        updateDots();
        restartTestimonialAutoSlide();
    }

    // Dipanggil dari onclick di HTML (harus global)
    window.testimonialMoveSlider = function (dir) {
        let next = currentTestimonialIndex + dir;
        if (next < 0) next = maxTestimonialIndex();
        else if (next > maxTestimonialIndex()) next = 0;
        currentTestimonialIndex = next;
        updateSliderPosition();
        updateDots();
        restartTestimonialAutoSlide();
    };

    function startTestimonialAutoSlide() {
        testimonialInterval = setInterval(() => testimonialMoveSlider(1), 5000);
    }

    function stopTestimonialAutoSlide() {
        if (testimonialInterval) clearInterval(testimonialInterval);
    }

    function restartTestimonialAutoSlide() {
        stopTestimonialAutoSlide();
        startTestimonialAutoSlide();
    }

    // Touch / Swipe
    let touchStartX = 0;
    testimonialSlider.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    testimonialSlider.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 50) testimonialMoveSlider(diff > 0 ? 1 : -1);
    }, { passive: true });

    // Keyboard
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft')  testimonialMoveSlider(-1);
        if (e.key === 'ArrowRight') testimonialMoveSlider(1);
    });

    // Pause on hover
    testimonialSlider.addEventListener('mouseenter', stopTestimonialAutoSlide);
    testimonialSlider.addEventListener('mouseleave', startTestimonialAutoSlide);

    // Resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            cardsPerView = getCardsPerView();
            currentTestimonialIndex = Math.min(currentTestimonialIndex, maxTestimonialIndex());
            updateSliderPosition();
            createDots();
        }, 200);
    });

    // Init
    createDots();
    updateSliderPosition();
    startTestimonialAutoSlide();
});
</script>
@endpush