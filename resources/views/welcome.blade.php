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

    /* Package Cards */
    .package-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .package-card {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .package-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .package-card img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .package-card:hover img {
        transform: scale(1.1);
    }

    .package-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 2rem 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        color: white;
    }

    .package-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /* Testimonials */
    .testimonial-section {
        background: #f8f8f8;
    }

    .testimonial-card {
        display: flex;
        gap: 2rem;
        max-width: 900px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .testimonial-image {
        flex-shrink: 0;
        width: 250px;
        height: 300px;
        border-radius: 8px;
        overflow: hidden;
    }

    .testimonial-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .testimonial-content {
        flex: 1;
    }

    .testimonial-rating {
        color: #D4AF37;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }

    .testimonial-name {
        font-weight: 600;
        color: #D4AF37;
        margin-bottom: 0.5rem;
    }

    .testimonial-text {
        color: #666;
        line-height: 1.8;
        margin-bottom: 1rem;
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

        .testimonial-card {
            flex-direction: column;
        }

        .testimonial-image {
            width: 100%;
            height: 250px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section id="home" class="hero-section">
    <!-- Slide 1 -->
    <div class="hero-slide active">
        <img src="{{ asset('assets/intimate/web/Background/home_1.jpg') }}" alt="Wedding Venue">
    </div>
    <!-- Slide 2 -->
    <div class="hero-slide">
        <img src="{{ asset('assets/intimate/web/Background/home_2.jpg') }}" alt="Wedding Venue">
    </div>
    <!-- Slide 3 -->
    <div class="hero-slide">
        <img src="{{ asset('assets/intimate/web/Background/home_3.jpg') }}" alt="Wedding Venue">
    </div>

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
            <!-- Beach Wedding -->
            <a href="{{ route('packages.public') }}" class="package-card" style="text-decoration: none;">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800&q=80" alt="Beach Wedding">
                <div class="package-overlay">
                    <h3 class="package-title">Beach Wedding</h3>
                    <p>Celebrate your love with the ocean as your backdrop</p>
                </div>
            </a>

            <!-- Garden Wedding -->
            <a href="{{ route('packages.public') }}" class="package-card" style="text-decoration: none;">
                <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800&q=80" alt="Garden Wedding">
                <div class="package-overlay">
                    <h3 class="package-title">Garden Wedding</h3>
                    <p>Exchange vows surrounded by lush tropical gardens</p>
                </div>
            </a>

            <!-- Chapel Wedding -->
            <a href="{{ route('packages.public') }}" class="package-card" style="text-decoration: none;">
                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80" alt="Chapel Wedding">
                <div class="package-overlay">
                    <h3 class="package-title">Chapel Wedding</h3>
                    <p>Traditional elegance in our beautiful chapel</p>
                </div>
            </a>

            <!-- Villa Wedding -->
            <a href="{{ route('packages.public') }}" class="package-card" style="text-decoration: none;">
                <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=800&q=80" alt="Villa Wedding">
                <div class="package-overlay">
                    <h3 class="package-title">Villa Wedding</h3>
                    <p>Intimate celebration in a private luxury villa</p>
                </div>
            </a>
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
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('gallery.public') }}" class="btn-primary">View Full Gallery</a>
        </div>
    </div>
</section>

<!-- Blog Preview -->
<section class="section" style="background: #f8f8f8;">
    <div class="container">
        <h2 class="section-title">LATEST FROM OUR BLOG</h2>
        <p class="section-subtitle">
            Wedding tips, inspiration, and stories
        </p>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('blogs.public') }}" class="btn-primary">Read Our Blog</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="section testimonial-section">
    <div class="container">
        <h2 class="section-title">Testimonials</h2>
        <p class="section-subtitle">
            Our wedding was perfect and flawless because of Intimate<br>
            Bali which led the effort to be organized.
        </p>

        <div class="testimonial-card">
            <div class="testimonial-image">
                <img src="https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=500&q=80" alt="Sarah & Jonathan">
            </div>
            <div class="testimonial-content">
                <div class="testimonial-rating">★★★★★</div>
                <h4 class="testimonial-name">Sarah & Jonathan</h4>
                <h4 style="color: #999; font-size: 0.9rem; margin-bottom: 1rem;">Married in Ubud, Bali</h4>
                <p class="testimonial-text">
                    Hiring Intimate Bali Weddings as our wedding planner was one of the best decisions we made. Her attention to detail, 
                    creativity, and professionalism made our special day absolutely perfect. She listened to our vision and brought it to 
                    life in ways we couldn't have imagined. From the initial planning stages to the day of the wedding, she was there every 
                    step of the way, ensuring everything ran smoothly. Our guests are still raving about how beautiful and well-organized 
                    everything was. We couldn't have asked for a better experience. Thank you for making our dream wedding a reality!
                </p>
                <a href="{{ route('gallery.public') }}" class="btn-primary" style="font-size: 0.85rem; padding: 0.6rem 1.5rem;">View More Testimonials</a>
            </div>
        </div>
    </div>
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
    });
</script>
@endpush