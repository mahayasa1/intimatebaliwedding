@extends('layouts.app')

@section('title', 'Gallery - Intimate Bali Wedding')

@push('styles')
<style>
    .gallery-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=1920&q=80');
        background-size: cover;
        background-position: center;
        height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-top: -80px;
        padding-top: 80px;
    }

    .gallery-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
    }

    .gallery-section {
        padding: 4rem 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .gallery-intro {
        text-align: center;
        margin-bottom: 3rem;
    }

    .gallery-intro h2 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .gallery-filters {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .filter-btn {
        padding: 0.6rem 1.5rem;
        border: 2px solid #D4AF37;
        background: white;
        color: #D4AF37;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #D4AF37;
        color: white;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        grid-auto-flow: dense;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    /* Horizontal images span 2 columns */
    .gallery-item.horizontal {
        grid-column: span 2;
        aspect-ratio: 16/9;
    }

    /* Vertical images span 1 column */
    .gallery-item.vertical {
        grid-column: span 1;
        aspect-ratio: 3/4;
    }

    /* Default aspect ratio */
    .gallery-item:not(.horizontal):not(.vertical) {
        aspect-ratio: 4/3;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .gallery-category {
        font-size: 0.85rem;
        color: #D4AF37;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Lightbox styles */
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.95);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .lightbox-close {
        position: absolute;
        top: 2rem;
        right: 2rem;
        color: white;
        font-size: 3rem;
        cursor: pointer;
        z-index: 10000;
    }

    /* Testimonials Section */
    .testimonials-section {
        background: #f9f9f9;
        padding: 5rem 2rem;
        margin-top: 3rem;
    }

    .testimonials-header {
        text-align: center;
        margin-bottom: 1rem;
    }

    .testimonials-header h2 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .testimonials-header p {
        color: #666;
        font-size: 1.1rem;
    }

    .google-verified {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top: 1rem;
    }

    .google-verified img {
        width: 24px;
        height: 24px;
    }

    .google-stats {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        margin: 2rem 0 3rem;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #D4AF37;
        font-family: 'Playfair Display', serif;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .testimonials-grid {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .testimonial-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .testimonial-header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #D4AF37;
    }

    .testimonial-info h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        margin: 0;
        color: #333;
    }

    .testimonial-time {
        font-size: 0.85rem;
        color: #999;
        margin-top: 0.2rem;
    }

    .testimonial-rating {
        color: #D4AF37;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .testimonial-review {
        color: #555;
        line-height: 1.6;
        font-size: 0.95rem;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .google-maps-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 30px;
        height: 30px;
        opacity: 0.6;
    }

    .testimonials-cta {
        text-align: center;
        margin-top: 3rem;
    }

    .btn-google-maps {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: #4285f4;
        color: white;
        text-decoration: none;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(66, 133, 244, 0.3);
    }

    .btn-google-maps:hover {
        background: #3367d6;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 133, 244, 0.4);
        color: white;
    }

    .no-reviews {
        text-align: center;
        padding: 3rem;
        color: #999;
    }

    @media (max-width: 768px) {
        .gallery-hero h1 {
            font-size: 2.5rem;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .gallery-item.horizontal,
        .gallery-item.vertical {
            grid-column: span 1;
            aspect-ratio: 4/3;
        }

        .gallery-section {
            padding: 3rem 1rem;
        }

        .testimonials-grid {
            grid-template-columns: 1fr;
        }

        .testimonials-section {
            padding: 3rem 1rem;
        }

        .google-stats {
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="gallery-hero">
    <div>
        <h1>GALLERY & TESTIMONIALS</h1>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section">
    <div class="gallery-intro">
        <h2>Our Beautiful Moments</h2>
        <p style="color: #666; max-width: 700px; margin: 0 auto;">
            Browse through our collection of beautiful wedding moments captured in paradise
        </p>
    </div>

    <!-- Category Filters -->
    <div class="gallery-filters">
        <button class="filter-btn active" data-category="all">All</button>
        @foreach($categories as $category)
        <button class="filter-btn" data-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid">
        <!-- Dynamic Gallery Items from Database -->
        @foreach($galleries as $gallery)
        @php
            $imagePath = storage_path('app/public/' . $gallery->image);
            $orientation = '';
            
            if (file_exists($imagePath)) {
                list($width, $height) = getimagesize($imagePath);
                $orientation = $width > $height ? 'horizontal' : 'vertical';
            }
        @endphp
        <div class="gallery-item {{ $orientation }}" data-category="{{ $gallery->category ?? 'Other' }}">
            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
            <div class="gallery-overlay">
                <div class="gallery-title">{{ $gallery->title }}</div>
                @if($gallery->category)
                <div class="gallery-category">{{ $gallery->category }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($galleries->hasPages())
    <div style="margin-top: 3rem; text-align: center;">
        {{ $galleries->links() }}
    </div>
    @endif
</section>

<!-- Testimonials Section (Google Maps Reviews) -->
<section class="testimonials-section">
    <div class="testimonials-header">
        <h2>What Our Couples Say</h2>
        <p>Real reviews from Google Maps</p>
        
        @if(!empty($businessStats))
        <div class="google-verified">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Google_Maps_icon_%282015-2020%29.svg/512px-Google_Maps_icon_%282015-2020%29.svg.png" alt="Google">
            <span>Verified by Google</span>
        </div>
        @endif
    </div>

    @if(!empty($businessStats) && isset($businessStats['rating']))
    <div class="google-stats">
        <div class="stat-item">
            <div class="stat-number">{{ number_format($businessStats['rating'], 1) }}</div>
            <div class="stat-label">Average Rating</div>
            <div class="testimonial-rating">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($businessStats['rating']))
                        ★
                    @elseif($i - 0.5 <= $businessStats['rating'])
                        ★
                    @else
                        ☆
                    @endif
                @endfor
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($businessStats['total_reviews']) }}</div>
            <div class="stat-label">Total Reviews</div>
        </div>
    </div>
    @endif

    @if(isset($googleReviews['success']) && $googleReviews['success'] && !empty($googleReviews['reviews']))
    <div class="testimonials-grid">
        @foreach($googleReviews['reviews'] as $review)
        <div class="testimonial-card" onclick="window.open('{{ $googleReviews['place_url'] }}', '_blank')">
            <!-- Google Maps Badge -->
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Google_Maps_icon_%282015-2020%29.svg/512px-Google_Maps_icon_%282015-2020%29.svg.png" 
                 alt="Google Maps" 
                 class="google-maps-badge">
            
            <div class="testimonial-header-content">
                @if(isset($review['author_photo']) && $review['author_photo'])
                <img src="{{ $review['author_photo'] }}" 
                     alt="{{ $review['author_name'] }}" 
                     class="testimonial-avatar"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                @endif
                <div class="testimonial-avatar" style="background: #D4AF37; display: {{ isset($review['author_photo']) && $review['author_photo'] ? 'none' : 'flex' }}; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.5rem;">
                    {{ strtoupper(substr($review['author_name'], 0, 1)) }}
                </div>
                
                <div class="testimonial-info">
                    <h4>{{ $review['author_name'] }}</h4>
                    <div class="testimonial-time">{{ $review['relative_time'] }}</div>
                </div>
            </div>

            <div class="testimonial-rating">
                {{ str_repeat('★', $review['rating']) }}{{ str_repeat('☆', 5 - $review['rating']) }}
            </div>

            <div class="testimonial-review">
                {{ $review['text'] }}
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="no-reviews">
        <p>{{ $googleReviews['error'] ?? 'Reviews will appear here once Google Maps integration is configured.' }}</p>
    </div>
    @endif

    <div class="testimonials-cta">
        <a href="{{ $googleReviews['place_url'] ?? '#' }}" target="_blank" class="btn-google-maps">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            See All Reviews on Google Maps
        </a>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img src="" alt="" id="lightbox-img">
</div>
@endsection

@push('scripts')
<script>
    // Gallery Filter
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const category = this.dataset.category;
            const items = document.querySelectorAll('.gallery-item');

            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Lightbox
    document.querySelectorAll('.gallery-item img').forEach(img => {
        img.addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('lightbox').classList.add('active');
            document.getElementById('lightbox-img').src = this.src;
            document.body.style.overflow = 'hidden';
        });
    });

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
</script>
@endpush