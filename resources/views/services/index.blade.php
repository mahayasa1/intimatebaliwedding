@extends('layouts.app')

@section('title', 'Our Services - Intimate Bali Wedding')

@push('styles')
<style>
    .services-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1519741497674-611481863552?w=1920&q=80');
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

    .services-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2.5rem;
        padding: 4rem 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .service-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .service-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
    }

    .service-content {
        padding: 2rem;
    }

    .service-category {
        color: #D4AF37;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .service-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .service-description {
        color: #666;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .service-btn {
        background: #D4AF37;
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .service-btn:hover {
        background: #B8941F;
    }

    @media (max-width: 768px) {
        .services-hero h1 {
            font-size: 2.5rem;
        }

        .services-grid {
            grid-template-columns: 1fr;
            padding: 3rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="services-hero">
    <div>
        <h1>SERVICES</h1>
    </div>
</section>

<!-- Services Grid -->
<section class="services-grid">
    @forelse($services as $service)
    <div class="service-card">
        @if($service->foto)
        <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->name }}" class="service-image">
        @else
        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80" alt="{{ $service->name }}" class="service-image">
        @endif
        
        <div class="service-content">
            @if($service->package)
            <div class="service-category">{{ $service->package->name }}</div>
            @endif
            
            <h3 class="service-title">{{ $service->name }}</h3>
            
            @if($service->description)
            <p class="service-description">
                {{ Str::limit($service->description, 150) }}
            </p>
            @endif
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
        <p style="color: #999; font-size: 1.2rem;">No services available at the moment.</p>
    </div>
    @endforelse
</section>

<!-- Pagination -->
@if($services->hasPages())
<div style="padding: 0 2rem 4rem; max-width: 1400px; margin: 0 auto;">
    {{ $services->links() }}
</div>
@endif
@endsection