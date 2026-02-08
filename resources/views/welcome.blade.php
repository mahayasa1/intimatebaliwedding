@extends('layouts.app')

@section('title', 'Wedding Venue - Intimate Bali Wedding')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        position: relative;
        height: 100vh;
        overflow: hidden;
        margin-top: -80px;
    }

    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .hero-slide.active {
        opacity: 1;
    }

    .hero-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.5));
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        z-index: 10;
        padding: 2rem;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: 4rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: 2px;
        animation: fadeInUp 1s ease-out;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        letter-spacing: 1px;
        font-weight: 300;
        animation: fadeInUp 1s ease-out 0.2s both;
    }

    .hero-cta {
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Package Cards - Slide Up Effect */
    .package-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
        max-width: 1400px;
        margin: 0 auto;
    }

    .package-card {
        position: relative;
        overflow: hidden;
        aspect-ratio: 1/1;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }

    .package-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .package-card:hover img {
        transform: scale(1.05);
    }

    /* Base overlay - gradient dari bawah, selalu terlihat */
    .package-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0, 0, 0) 0%, transparent 50%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        transition: background 0.3s ease;
        pointer-events: none;
        text-align: center;
    }

    /* Black overlay yang slide dari bawah */
    .package-overlay::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 0;
        background: rgba(0, 0, 0, 0.327);
        transition: height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }

    /* Saat hover, black overlay naik ke atas */
    .package-card:hover .package-overlay::before {
        height: 100%;
    }

    /* Content wrapper */
    .package-content {
        position: relative;
        z-index: 2;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Saat hover, content bergeser ke tengah */
    .package-card:hover .package-content {
        transform: translateY(-30%);
    }

    .package-type {
        font-size: 0.75rem;
        color: #D4AF37;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .package-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .package-card p {
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0.8rem 0;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transform: translateY(20px);
        transition: all 0.5s ease 0.2s;
    }

    .package-card:hover p {
        max-height: 100px;
        opacity: 1;
        transform: translateY(0);
    }

    /* Gallery Preview */
    .gallery-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 2rem auto;
    }

    .gallery-preview-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        aspect-ratio: 4/3;
    }

    .gallery-preview-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .gallery-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .gallery-preview-item:hover img {
        transform: scale(1.1);
    }

    /* Blog Preview */
    .blog-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 2rem auto;
    }

    .blog-preview-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        cursor: pointer;
    }

    .blog-preview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .blog-preview-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .blog-preview-content {
        padding: 1.5rem;
    }

    .blog-preview-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .blog-preview-excerpt {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .blog-preview-date {
        color: #D4AF37;
        font-size: 0.85rem;
    }

    /* Testimonials */
    .testimonial-section {
        background: #f8f8f8;
        padding: 5rem 2rem;
        overflow: hidden;
    }

    .testimonials-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .testimonials-header h2 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    /* Testimonial Slider Container */
    .testimonials-slider-container {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 60px;
    }

    .testimonials-slider-wrapper {
        overflow: hidden;
        position: relative;
    }

    .testimonials-slider {
        display: flex;
        gap: 2rem;
        transition: transform 0.5s ease-in-out;
    }

    .testimonial-card {
        background: white;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        position: relative;
        min-width: 350px;
        flex-shrink: 0;
        transition: all 0.3s;
        border-left: 4px solid #D4AF37;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .testimonial-header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #D4AF37;
        flex-shrink: 0;
    }

    .testimonial-info {
        flex: 1;
    }

    .testimonial-info h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        margin: 0 0 0.3rem 0;
        color: #333;
    }

    .testimonial-time {
        font-size: 0.8rem;
        color: #999;
    }

    .testimonial-rating {
        color: #D4AF37;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        letter-spacing: 2px;
    }

    .testimonial-review {
        color: #555;
        line-height: 1.8;
        font-size: 1rem;
        font-style: italic;
        position: relative;
        padding-left: 1.5rem;
    }

    .testimonial-review:before {
        content: '"';
        position: absolute;
        left: 0;
        top: -10px;
        font-size: 3rem;
        color: #D4AF37;
        opacity: 0.3;
        font-family: Georgia, serif;
    }

    /* Slider Navigation Buttons */
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: white;
        border: 2px solid #D4AF37;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .slider-nav:hover {
        background: #D4AF37;
        color: white;
        transform: translateY(-50%) scale(1.1);
    }

    .slider-nav.prev {
        left: 0;
    }

    .slider-nav.next {
        right: 0;
    }

    .slider-nav svg {
        width: 24px;
        height: 24px;
        fill: currentColor;
    }

    /* Slider Dots */
    .slider-dots {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }

    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ddd;
        cursor: pointer;
        transition: all 0.3s;
    }

    .slider-dot.active {
        background: #D4AF37;
        width: 30px;
        border-radius: 6px;
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

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            height: 70vh;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .package-grid {
            grid-template-columns: 1fr;
        }

        .package-title {
            font-size: 1.3rem;
        }

        .package-card:hover .package-content {
            transform: translateY(-20%);
        }

        .gallery-preview-grid {
            grid-template-columns: 1fr;
        }

        .blog-preview-grid {
            grid-template-columns: 1fr;
        }

        .testimonial-section {
            padding: 3rem 1rem;
        }

        .testimonials-slider-container {
            padding: 0 50px;
        }

        .testimonial-card {
            min-width: 280px;
            padding: 1.5rem;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
        }

        .testimonial-review {
            font-size: 0.9rem;
            padding-left: 1rem;
        }

        .slider-nav {
            width: 40px;
            height: 40px;
        }

        .slider-nav svg {
            width: 20px;
            height: 20px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section id="home" class="hero-section">
    @foreach($heroSlides as $index => $slide)
    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
        @if(str_contains($slide->image, 'storage/'))
            <img src="{{ asset($slide->image) }}" alt="Wedding Venue">
        @else
            <img src="{{ asset('storage/' . $slide->image) }}" alt="Wedding Venue" 
                 onerror="this.onerror=null; this.src='{{ asset($slide->image) }}';">
        @endif
    </div>
    @endforeach

    <!-- Hero Overlay -->
    <div class="hero-overlay">
        <h1 class="hero-title">WEDDING VENUE</h1>
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
            <a href="{{ route('services.public') }}" class="btn-primary" style="margin-left: 1rem;">Our Services</a>
        </div>
    </div>
</section>

<!-- Wedding Package Section -->
<section id="packages" class="section" style="background: #f8f8f8;">
    <div class="container">
        <h2 class="section-title">WEDDING PACKAGE</h2>
        <p class="section-subtitle">
            All packages can be customised to suit your needs
        </p>

        <div class="package-grid">
            @foreach($packages->take(4) as $package)
            <a href="{{ route('packages.public') }}" class="package-card">
                @if($package->image)
                    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}">
                @else
                    <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80" alt="{{ $package->name }}">
                @endif
                <div class="package-overlay">
                    <div class="package-content">
                        <div class="package-type">Wedding Package</div>
                        <h3 class="package-title">{{ $package->name }}</h3>
                        <p>{{ Str::limit($package->description, 80) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('packages.public') }}" class="btn-primary">View All Packages</a>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
<section id="gallery" class="section">
    <div class="container">
        <h2 class="section-title">GALLERY</h2>
        <p class="section-subtitle">
            Beautiful moments captured in paradise
        </p>
        
        @if($galleries->count() > 0)
        <div class="gallery-preview-grid">
            @foreach($galleries as $gallery)
            <div class="gallery-preview-item">
                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
            </div>
            @endforeach
        </div>
        @endif

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('gallery.public') }}" class="btn-primary">View Full Gallery</a>
        </div>
    </div>
</section>

<!-- Blog Preview -->
@if($blogs->count() > 0)
<section class="section" style="background: #f8f8f8;">
    <div class="container">
        <h2 class="section-title">LATEST FROM OUR BLOG</h2>
        <p class="section-subtitle">
            Wedding tips, inspiration, and stories
        </p>
        
        <div class="blog-preview-grid">
            @foreach($blogs as $blog)
            <a href="{{ route('blogs.show', $blog->slug) }}" class="blog-preview-card" style="text-decoration: none;">
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="blog-preview-image">
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

<!-- Testimonials Section (Google Maps Reviews) -->
<section id="testimonials" class="testimonial-section">
    <div class="testimonials-header">
        <h2>What Our Couples Say</h2>
    </div>

    @if(isset($googleReviews['success']) && $googleReviews['success'] && !empty($googleReviews['reviews']))
    <div class="testimonials-slider-container">
        <!-- Previous Button -->
        <div class="slider-nav prev" onclick="moveSlider(-1)">
            <svg viewBox="0 0 24 24">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
            </svg>
        </div>

        <!-- Slider Wrapper -->
        <div class="testimonials-slider-wrapper">
            <div class="testimonials-slider" id="testimonials-slider">
                @foreach($googleReviews['reviews'] as $review)
                <div class="testimonial-card">
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
        </div>

        <!-- Next Button -->
        <div class="slider-nav next" onclick="moveSlider(1)">
            <svg viewBox="0 0 24 24">
                <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
            </svg>
        </div>
    </div>

    <!-- Slider Dots -->
    <div class="slider-dots" id="slider-dots"></div>
    
    <!-- CTA Button -->
    <div class="testimonials-cta">
        <a href="{{ $googleReviews['place_url'] ?? route('gallery.public') }}" target="_blank" class="btn-google-maps">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
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
    // Hero Image Slider with Fade Effect
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slide');
        let currentSlide = 0;
        const slideInterval = 3000; 

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        // Auto slide
        if (slides.length > 1) {
            setInterval(nextSlide, slideInterval);
        }

        // Initialize Testimonial Slider
        initTestimonialSlider();
    });

    // Testimonial Slider
    let currentSlideIndex = 0;
    let testimonialSlider;
    let testimonialCards;
    let sliderInterval;
    let cardsPerView = 3;

    function initTestimonialSlider() {
        testimonialSlider = document.getElementById('testimonials-slider');
        if (!testimonialSlider) return;

        testimonialCards = testimonialSlider.querySelectorAll('.testimonial-card');
        if (testimonialCards.length === 0) return;

        // Calculate cards per view based on screen width
        updateCardsPerView();
        
        // Create dots
        createSliderDots();
        
        // Update slider position
        updateSliderPosition();
        
        // Start auto-slide
        startAutoSlide();

        // Update on window resize
        window.addEventListener('resize', function() {
            updateCardsPerView();
            updateSliderPosition();
            createSliderDots();
        });

        // Pause auto-slide on hover
        testimonialSlider.addEventListener('mouseenter', stopAutoSlide);
        testimonialSlider.addEventListener('mouseleave', startAutoSlide);
    }

    function updateCardsPerView() {
        const width = window.innerWidth;
        if (width < 768) {
            cardsPerView = 1;
        } else if (width < 1200) {
            cardsPerView = 2;
        } else {
            cardsPerView = 3;
        }
    }

    function moveSlider(direction) {
        const maxSlides = Math.ceil(testimonialCards.length / cardsPerView) - 1;
        
        currentSlideIndex += direction;
        
        if (currentSlideIndex < 0) {
            currentSlideIndex = maxSlides;
        } else if (currentSlideIndex > maxSlides) {
            currentSlideIndex = 0;
        }
        
        updateSliderPosition();
        updateDots();
        
        // Reset auto-slide
        stopAutoSlide();
        startAutoSlide();
    }

    function updateSliderPosition() {
        if (!testimonialSlider || !testimonialCards.length) return;
        
        const cardWidth = testimonialCards[0].offsetWidth;
        const gap = 32; // 2rem gap
        const moveAmount = (cardWidth + gap) * cardsPerView;
        const offset = -(currentSlideIndex * moveAmount);
        
        testimonialSlider.style.transform = `translateX(${offset}px)`;
    }

    function createSliderDots() {
        const dotsContainer = document.getElementById('slider-dots');
        if (!dotsContainer || !testimonialCards.length) return;
        
        dotsContainer.innerHTML = '';
        const totalDots = Math.ceil(testimonialCards.length / cardsPerView);
        
        for (let i = 0; i < totalDots; i++) {
            const dot = document.createElement('div');
            dot.className = 'slider-dot' + (i === currentSlideIndex ? ' active' : '');
            dot.onclick = () => goToSlide(i);
            dotsContainer.appendChild(dot);
        }
    }

    function updateDots() {
        const dots = document.querySelectorAll('.slider-dot');
        dots.forEach((dot, index) => {
            if (index === currentSlideIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    function goToSlide(index) {
        currentSlideIndex = index;
        updateSliderPosition();
        updateDots();
        
        // Reset auto-slide
        stopAutoSlide();
        startAutoSlide();
    }

    function startAutoSlide() {
        stopAutoSlide(); // Clear any existing interval
        sliderInterval = setInterval(() => {
            moveSlider(1);
        }, 5000); // Auto-slide every 5 seconds
    }

    function stopAutoSlide() {
        if (sliderInterval) {
            clearInterval(sliderInterval);
        }
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!testimonialSlider) return;
        
        if (e.key === 'ArrowLeft') {
            moveSlider(-1);
        } else if (e.key === 'ArrowRight') {
            moveSlider(1);
        }
    });

    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    if (testimonialSlider) {
        testimonialSlider.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        testimonialSlider.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
    }

    function handleSwipe() {
        if (touchEndX < touchStartX - 50) {
            // Swipe left
            moveSlider(1);
        }
        if (touchEndX > touchStartX + 50) {
            // Swipe right
            moveSlider(-1);
        }
    }
</script>
@endpush