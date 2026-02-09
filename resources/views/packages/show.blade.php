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
        max-width: 900px;
        margin: 0 auto;
        padding: 4rem 2rem;
        background: white;
    }

    /* Package Image */
    .package-main-image {
        width: 100%;
        max-width: 600px;
        margin: 0 auto 3rem;
        display: block;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    /* Package Title & Description */
    .package-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .package-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #333;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    /* Price Display */
    .package-price {
        font-size: 1.5rem;
        color: #D4AF37;
        font-weight: 700;
        margin: 1.5rem 0;
    }

    .package-price-note {
        font-size: 0.9rem;
        color: #666;
        font-style: italic;
    }

    /* Services List */
    .services-list {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
    }

    .services-list li {
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
        color: #555;
        line-height: 1.6;
        position: relative;
        padding-left: 2rem;
    }

    .services-list li:before {
        content: "•";
        position: absolute;
        left: 0.5rem;
        color: #D4AF37;
        font-size: 1.5rem;
        line-height: 1.2;
    }

    .services-list li:last-child {
        border-bottom: none;
    }

    /* Gallery Grid */
    .package-gallery {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin: 2rem 0;
    }

    .package-gallery img {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .package-gallery img:hover {
        transform: scale(1.05);
    }

    /* Enquiry Button */
    .enquiry-btn {
        display: inline-block;
        background: #333;
        color: white;
        padding: 1rem 3rem;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 2rem 0;
        text-align: center;
    }

    .enquiry-btn:hover {
        background: #555;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .other-package-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
    }

    .other-package-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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

        .package-header h2 {
            font-size: 1.5rem;
        }

        .package-gallery {
            grid-template-columns: 1fr;
        }

        .other-packages-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="package-detail-hero">
    <div>
        <h1>PACKAGE</h1>
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
        
        <!-- Price Display (Optional - uncomment if you add price field) -->
        {{-- 
        <div class="package-price">
            IDR 22,000,000 Net (USD 1,355)
        </div>
        --}}
    </div>

    <!-- Package Description -->
    @if($package->description)
    <div style="text-align: center; color: #666; line-height: 1.8; margin-bottom: 2rem;">
        {!! nl2br(e($package->description)) !!}
    </div>
    @endif

    <!-- Services List -->
    @if($package->services->count() > 0)
    <ul class="services-list">
        @foreach($package->services as $service)
        <li>{{ $service->name }}{{ $service->description ? ': ' . $service->description : '' }}</li>
        @endforeach
    </ul>
    @endif

    <!-- Package Gallery (from services photos) -->
    @php
        $servicePhotos = $package->services->filter(fn($s) => $s->foto)->take(4);
    @endphp
    
    @if($servicePhotos->count() > 0)
    <div class="package-gallery">
        @foreach($servicePhotos as $service)
        <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->name }}" loading="lazy">
        @endforeach
    </div>
    @endif

    <!-- Enquiry Button -->
    <div style="text-align: center;">
        <a href="{{ route('contact') }}" class="enquiry-btn">Enquiry</a>
    </div>
</section>

<!-- Other Packages Section -->
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
@endsection