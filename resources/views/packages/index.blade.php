@extends('layouts.app')

@section('title', 'Wedding Packages - Intimate Bali Wedding')

@push('styles')
<style>
    .packages-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('/assets/background/bg_template.jpg');
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
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .packages-hero p {
        font-size: 1.1rem;
        font-weight: 300;
        letter-spacing: 1px;
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
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .packages-intro p {
        color: #666;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    /* ===== CATEGORY FILTER ===== */
    .category-filter-section {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem 2rem;
    }

    .category-filter-tabs {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
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
        font-family: 'Inter', sans-serif;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #D4AF37;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212,175,55,0.3);
    }

    /* ===== PACKAGES GRID ===== */
    .packages-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .package-card {
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
    
    .package-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .package-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .package-card:hover .package-image {
        transform: scale(1.05);
    }

    .package-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 50%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        transition: background 0.3s ease;
        pointer-events: none;
        text-align: center;
    }

    .package-overlay::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 0;
        background: rgba(0, 0, 0, 0.356);
        transition: height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }

    .package-card:hover .package-overlay::before {
        height: 100%;
    }

    .package-content {
        position: relative;
        z-index: 2;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

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
        transition: all 0.3s ease;
    }

    .package-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        color: white;
    }

    /* Category badge on card */
    .package-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        background: rgba(212,175,55,0.85);
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 5;
    }

    .package-description {
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 1rem 0;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transform: translateY(20px);
        transition: all 0.5s ease 0.2s;
        color: white;
    }

    .package-card:hover .package-description {
        max-height: 200px;
        opacity: 1;
        transform: translateY(0);
    }

    .package-services-count {
        display: inline-block;
        background: rgba(212, 175, 55, 0.9);
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease 0.3s;
    }

    .package-card:hover .package-services-count {
        opacity: 1;
        transform: translateY(0);
    }

    /* Empty state */
    .empty-packages {
        text-align: center;
        padding: 4rem 2rem;
        grid-column: 1 / -1;
    }

    .empty-packages-icon {
        font-size: 4rem;
        color: #D4AF37;
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    .empty-packages h3 {
        font-family: 'Playfair Display', serif;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .empty-packages p { color: #999; }

    /* CTA Section */
    .cta-section {
        background: #f8f8f8;
        padding: 4rem 2rem;
        text-align: center;
        margin-top: 3rem;
    }

    .cta-section h2 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .cta-section p {
        color: #666;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .btn-primary {
        display: inline-block;
        background: linear-gradient(135deg, #D4AF37 0%, #AA8B2A 100%);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        color: white;
    }

    @media (max-width: 768px) {
        .packages-hero h1 { font-size: 2.5rem; }
        .packages-grid {
            grid-template-columns: 1fr;
            padding: 0 1rem;
            gap: 1.5rem;
        }
        .packages-intro { padding: 3rem 1rem 1rem; }
        .package-title { font-size: 1.4rem; }
        .package-card:hover .package-content { transform: translateY(-20%); }
        .category-filter-section { padding: 0 1rem 1.5rem; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="packages-hero">
    <div>
        <h1>Packages</h1>
        <p>Choose Your Perfect Wedding Package</p>
    </div>
</section>

<!-- Intro Section -->
<section class="packages-intro">
    <h2>Wedding Packages</h2>
    <p>All packages can be customised to suit your needs</p>
</section>

<!-- Category Filter -->
@if($categories->count() > 0)
<section class="category-filter-section">
    <div class="category-filter-tabs">
        <button class="filter-btn active" data-category="all">All</button>
        @foreach($categories as $category)
        <button class="filter-btn" data-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>
</section>
@endif

<!-- Packages Grid -->
<section style="padding-bottom: 2rem;">
    <div class="packages-grid" id="packagesGrid">
        @foreach($packages as $package)
        <a href="{{ route('packages.show', $package->id) }}"
           class="package-card"
           data-category="{{ $package->category ?? 'Uncategorized' }}">

            @if($package->category)
            <div class="package-category-badge">
                <i class="fas fa-tag"></i> {{ $package->category }}
            </div>
            @endif

            @if($package->image)
                <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="package-image">
            @else
                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80" alt="{{ $package->name }}" class="package-image">
            @endif
            
            <div class="package-overlay">
                <div class="package-content">
                    {{-- <div class="package-type">{{ $package->category ?? 'Wedding Package' }}</div> --}}
                    <h3 class="package-title">{{ $package->name }}</h3>
                    @if($package->description)
                    <p class="package-description">{{ Str::limit($package->description, 150) }}</p>
                    @endif
                </div>
            </div>
        </a>
        @endforeach

        @if($packages->isEmpty())
        <div class="empty-packages">
            <div class="empty-packages-icon"><i class="fas fa-box-open"></i></div>
            <h3>No Packages Found</h3>
            <p>Please check back later for our available packages.</p>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <h2>Ready to Start Planning?</h2>
    <p>Contact us today for a free consultation and let's create your dream wedding together</p>
    <a href="{{ route('contact') }}" class="btn-primary">Get Started</a>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const cat = this.dataset.category;
        const cards = document.querySelectorAll('.package-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardCat = card.dataset.category;
            const show = cat === 'all' || cardCat === cat;
            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Show empty state if no results
        const emptyState = document.getElementById('emptyState');
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    });
});
</script>
@endpush