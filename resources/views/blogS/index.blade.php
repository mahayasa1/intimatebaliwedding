@extends('layouts.app')

@section('title', 'Blog - Intimate Bali Wedding')

@push('styles')
<style>
    .blog-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1522413452208-996ff3f3e740?w=1920&q=80');
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

    .blog-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
    }

    .blog-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2.5rem;
    }

    .blog-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .blog-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .blog-card:hover .blog-image {
        transform: scale(1.05);
    }

    .blog-content {
        padding: 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 1rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .blog-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s;
    }

    .blog-title a:hover {
        color: #D4AF37;
    }

    .blog-excerpt {
        color: #666;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        flex: 1;
    }

    .blog-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #eee;
        font-size: 0.85rem;
        color: #999;
    }

    .blog-author {
        font-weight: 600;
        color: #D4AF37;
    }

    .blog-read-more {
        color: #D4AF37;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.3s;
    }

    .blog-read-more:hover {
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .blog-hero h1 {
            font-size: 2.5rem;
        }

        .blog-container {
            padding: 3rem 1rem;
        }

        .blog-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="blog-hero">
    <div>
        <h1>BLOG</h1>
        <p style="font-size: 1.1rem; font-weight: 300;">Wedding Tips, Stories & Inspiration</p>
    </div>
</section>

<!-- Blog Grid -->
<section class="blog-container">
    <div class="blog-grid">
        <!-- Sample Blog Posts (from image) -->
        <div class="blog-card">
            <img src="https://images.unsplash.com/photo-1594842645988-fae93304b00e?w=800&q=80" alt="Best Time Wedding" class="blog-image">
            <div class="blog-content">
                <h3 class="blog-title">
                    <a href="#">Best Time Wedding</a>
                </h3>
                <p class="blog-excerpt">
                    Discover the perfect season and time of day for your Bali wedding. Learn about weather patterns, 
                    lighting conditions, and how to choose the ideal moment for your special day...
                </p>
                <div class="blog-meta">
                    <span class="blog-author">By Wedding Planner</span>
                    <a href="#" class="blog-read-more">
                        Read More →
                    </a>
                </div>
            </div>
        </div>

        <div class="blog-card">
            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80" alt="Wedding Preparation" class="blog-image">
            <div class="blog-content">
                <h3 class="blog-title">
                    <a href="#">Wedding Preparation</a>
                </h3>
                <p class="blog-excerpt">
                    Essential tips and timeline for preparing your dream wedding. From venue selection to vendor 
                    coordination, we'll guide you through every step of the planning process...
                </p>
                <div class="blog-meta">
                    <span class="blog-author">By Event Coordinator</span>
                    <a href="#" class="blog-read-more">
                        Read More →
                    </a>
                </div>
            </div>
        </div>

        <div class="blog-card">
            <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=800&q=80" alt="Most Underrated Weddings" class="blog-image">
            <div class="blog-content">
                <h3 class="blog-title">
                    <a href="#">Most Underrated Weddings</a>
                </h3>
                <p class="blog-excerpt">
                    Explore hidden gems and unique wedding venues in Bali that offer intimate, unforgettable 
                    experiences away from the crowds. Discover secret locations that will make your day truly special...
                </p>
                <div class="blog-meta">
                    <span class="blog-author">By Venue Expert</span>
                    <a href="#" class="blog-read-more">
                        Read More →
                    </a>
                </div>
            </div>
        </div>

        <div class="blog-card">
            <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80" alt="Perfect Places Wedding" class="blog-image">
            <div class="blog-content">
                <h3 class="blog-title">
                    <a href="#">Perfect Places Wedding</a>
                </h3>
                <p class="blog-excerpt">
                    A comprehensive guide to Bali's most stunning wedding venues. From beachfront resorts to 
                    cliffside chapels, find the perfect backdrop for your love story...
                </p>
                <div class="blog-meta">
                    <span class="blog-author">By Location Scout</span>
                    <a href="#" class="blog-read-more">
                        Read More →
                    </a>
                </div>
            </div>
        </div>

        <!-- Dynamic Blog Posts from Database -->
        @foreach($blogs as $blog)
        <div class="blog-card">
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="blog-image">
            <div class="blog-content">
                <h3 class="blog-title">
                    <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                </h3>
                <p class="blog-excerpt">
                    {{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 150) }}
                </p>
                <div class="blog-meta">
                    <span class="blog-author">By {{ $blog->author ?? 'Admin' }}</span>
                    <a href="{{ route('blog.show', $blog->slug) }}" class="blog-read-more">
                        Read More →
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($blogs->hasPages())
    <div style="margin-top: 3rem; text-align: center;">
        {{ $blogs->links() }}
    </div>
    @endif
</section>

<!-- CTA Section -->
<section style="background: #f8f8f8; padding: 4rem 2rem; text-align: center;">
    <h2 style="font-family: 'Playfair Display', serif; color: #D4AF37; font-size: 2rem; margin-bottom: 1rem;">
        Ready to Plan Your Wedding?
    </h2>
    <p style="color: #666; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
        Let our experienced team help you create the wedding of your dreams
    </p>
    <a href="#contact" class="btn-primary">Contact Us</a>
</section>
@endsection