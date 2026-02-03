@extends('layouts.app')

@section('title', 'Wedding Packages - Intimate Bali Wedding')

@push('styles')
<style>
    .packages-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=1920&q=80');
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

    .packages-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .packages-intro {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        padding: 4rem 2rem 2rem;
    }

    .packages-intro h2 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .packages-intro p {
        color: #666;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .packages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .package-card {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        height: 400px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .package-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .package-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .package-card:hover .package-image {
        transform: scale(1.1);
    }

    .package-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        color: white;
    }

    .package-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .package-description {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .package-services-count {
        display: inline-block;
        background: rgba(212, 175, 55, 0.9);
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .packages-hero h1 {
            font-size: 2.5rem;
        }

        .packages-grid {
            grid-template-columns: 1fr;
            padding: 2rem 1rem;
        }

        .packages-intro {
            padding: 3rem 1rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="packages-hero">
    <div>
        <h1>PACKAGES</h1>
        <p style="font-size: 1.1rem; font-weight: 300;">Choose Your Perfect Wedding Package</p>
    </div>
</section>

<!-- Intro Section -->
<section class="packages-intro">
    <h2>WEDDING PACKAGE</h2>
    <p>All packages can be customised to suit your needs</p>
</section>

<!-- Packages Grid -->
<section class="packages-grid">
    <div class="package-card">
        <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800&q=80" alt="Beach Wedding" class="package-image">
        <div class="package-overlay">
            <h3 class="package-title">Beach Wedding</h3>
            <p class="package-description">Celebrate your love with the ocean as your backdrop</p>
            <span class="package-services-count">View Details</span>
        </div>
    </div>

    <div class="package-card">
        <img src="https://images.unsplash.com/photo-1519167758481-83f29da8c6c7?w=800&q=80" alt="Sunset Wedding" class="package-image">
        <div class="package-overlay">
            <h3 class="package-title">Sunset Wedding</h3>
            <p class="package-description">Exchange vows during Bali's magical golden hour</p>
            <span class="package-services-count">View Details</span>
        </div>
    </div>

    <div class="package-card">
        <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800&q=80" alt="Garden Wedding" class="package-image">
        <div class="package-overlay">
            <h3 class="package-title">Beach Garden</h3>
            <p class="package-description">Tropical gardens meet ocean views</p>
            <span class="package-services-count">View Details</span>
        </div>
    </div>

    <div class="package-card">
        <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=800&q=80" alt="Nature Wedding" class="package-image">
        <div class="package-overlay">
            <h3 class="package-title">Nature Wedding</h3>
            <p class="package-description">Surrounded by Bali's natural beauty</p>
            <span class="package-services-count">View Details</span>
        </div>
    </div>

    @foreach($packages as $package)
    <div class="package-card">
        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80" alt="{{ $package->name }}" class="package-image">
        <div class="package-overlay">
            <h3 class="package-title">{{ $package->name }}</h3>
            @if($package->description)
            <p class="package-description">{{ Str::limit($package->description, 100) }}</p>
            @endif
            <span class="package-services-count">{{ $package->services_count }} Services</span>
        </div>
    </div>
    @endforeach
</section>

<!-- CTA Section -->
<section style="background: #f8f8f8; padding: 4rem 2rem; text-align: center;">
    <h2 style="font-family: 'Playfair Display', serif; color: #D4AF37; font-size: 2rem; margin-bottom: 1rem;">
        Ready to Start Planning?
    </h2>
    <p style="color: #666; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
        Contact us today for a free consultation and let's create your dream wedding together
    </p>
    <a href="#contact" class="btn-primary">Get Started</a>
</section>
@endsection