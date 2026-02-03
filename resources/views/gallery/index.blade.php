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
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        aspect-ratio: 4/3;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
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

    @media (max-width: 768px) {
        .gallery-hero h1 {
            font-size: 2.5rem;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .gallery-section {
            padding: 3rem 1rem;
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
        <!-- Testimonial Card -->
        <div class="gallery-item" style="grid-column: span 2; aspect-ratio: 16/9;" data-category="testimonial">
            <img src="https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=800&q=80" alt="Sarah & Jonathan">
            <div class="gallery-overlay" style="opacity: 1; padding: 2rem;">
                <div style="display: flex; gap: 2rem; align-items: center;">
                    <div style="flex-shrink: 0; width: 150px; height: 150px; border-radius: 8px; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=300&q=80" alt="Couple" style="width: 100%; height: 100%; object-fit: cover; transform: none;">
                    </div>
                    <div style="flex: 1;">
                        <div style="color: #D4AF37; margin-bottom: 0.5rem; font-size: 1.2rem;">★★★★★</div>
                        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 0.5rem;">Nyoman & Daya</h3>
                        <p style="font-size: 0.9rem; line-height: 1.6; margin-bottom: 0;">
                            "Absolutely stunning venue and impeccable service. Our wedding day was beyond perfect..."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Gallery Items (Beach Canggu examples from image) -->
        <div class="gallery-item" data-category="Beach">
            <img src="https://images.unsplash.com/photo-1519167758481-83f29da8c6c7?w=600&q=80" alt="Beach Canggu">
            <div class="gallery-overlay">
                <div class="gallery-title">Beach Canggu</div>
                <div class="gallery-category">Beach Wedding</div>
            </div>
        </div>

        <div class="gallery-item" data-category="Beach">
            <img src="https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=600&q=80" alt="Beach Canggu">
            <div class="gallery-overlay">
                <div class="gallery-title">Beach Canggu</div>
                <div class="gallery-category">Beach Wedding</div>
            </div>
        </div>

        <div class="gallery-item" data-category="Beach">
            <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=600&q=80" alt="Beach Canggu">
            <div class="gallery-overlay">
                <div class="gallery-title">Beach Canggu</div>
                <div class="gallery-category">Beach Wedding</div>
            </div>
        </div>

        <!-- Dynamic Gallery Items from Database -->
        @foreach($galleries as $gallery)
        <div class="gallery-item" data-category="{{ $gallery->category ?? 'Other' }}">
            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}">
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
        img.addEventListener('click', function() {
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