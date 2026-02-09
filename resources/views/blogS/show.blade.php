@extends('layouts.app')

@section('title', $blog->title)

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@400;500;600&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #fafafa;
    }

    /* Hero Section */
    .page-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1522413452208-996ff3f3e740?w=1920&q=80');
        background-size: cover;
        background-position: center;
        height: 20vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-top: -80px;
        margin-bottom: 3rem;
    }

    .page-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 400;
        color: white;
        letter-spacing: 12px;
        text-transform: uppercase;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    /* Back Button */
    .back-button-section {
        max-width: 900px;
        margin: 0 1rem auto 1rem;
        padding: 0 2rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        text-decoration: none;
        font-family: 'Lora', serif;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.5rem 0;
    }

    .btn-back:hover {
        color: #8B7355;
        transform: translateX(-4px);
    }

    /* Blog Container */
    .blog-detail-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 2rem 6rem;
        background: white;
        box-shadow: 0 2px 20px rgba(0,0,0,0.05);
        border-radius: 12px;
    }

    /* Blog Header */
    .blog-header {
        padding: 3rem 3rem 2rem;
        text-align: center;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 3rem;
    }

    /* Blog Title */
    .blog-detail-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.3;
        margin-bottom: 1.5rem;
        letter-spacing: 0.5px;
    }

    /* Meta Info */
    .blog-meta-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Lora', serif;
        font-size: 0.9rem;
        color: #666;
    }

    .meta-icon {
        width: 16px;
        height: 16px;
        color: #8B7355;
    }

    .meta-divider {
        width: 1px;
        height: 16px;
        background: #ddd;
    }

    /* Featured Image */
    .featured-image-wrapper {
        width: 100%;
        margin: 0 0 3rem 0;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .featured-image {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform 0.5s ease;
    }

    .featured-image-wrapper:hover .featured-image {
        transform: scale(1.02);
    }

    /* Blog Content */
    .blog-content-wrapper {
        padding: 0 3rem 3rem;
    }

    /* Excerpt */
    .blog-excerpt {
        font-family: 'Lora', serif;
        font-size: 1.2rem;
        line-height: 1.8;
        color: #555;
        font-style: italic;
        margin-bottom: 3rem;
        padding: 2rem;
        background: #f8f9fa;
        border-left: 4px solid #8B7355;
        border-radius: 4px;
    }

    /* Content */
    .blog-detail-content {
        font-family: 'Lora', serif;
        font-size: 1.05rem;
        line-height: 1.9;
        color: #333;
    }

    .blog-detail-content p {
        margin-bottom: 1.8rem;
        text-align: justify;
    }

    .blog-detail-content p:first-of-type::first-letter {
        font-size: 3.5rem;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        float: left;
        line-height: 1;
        margin: 0.1rem 0.5rem 0 0;
        color: #8B7355;
    }

    .blog-detail-content h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-top: 3.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .blog-detail-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2a2a2a;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .blog-detail-content ul,
    .blog-detail-content ol {
        margin: 2rem 0;
        padding-left: 2.5rem;
    }

    .blog-detail-content li {
        margin-bottom: 1rem;
        line-height: 1.8;
    }

    .blog-detail-content blockquote {
        border-left: 4px solid #8B7355;
        padding: 1.5rem 2rem;
        margin: 2.5rem 0;
        background: #f9f9f9;
        font-style: italic;
        color: #555;
        font-size: 1.1rem;
        border-radius: 4px;
    }

    .blog-detail-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 2rem 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Divider */
    .content-divider {
        margin: 3rem 0;
        border: none;
        height: 1px;
        background: linear-gradient(to right, transparent, #ddd, transparent);
    }

    /* Author Section */
    .author-section {
        margin-top: 4rem;
        padding: 2.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .author-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 600;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .author-details {
        flex: 1;
    }

    .author-label {
        font-family: 'Lora', serif;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #8B7355;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .author-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .author-bio {
        font-family: 'Lora', serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
    }

    /* Share Section */
    .share-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e8e8e8;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .share-label {
        font-family: 'Lora', serif;
        font-size: 0.95rem;
        color: #666;
        font-weight: 500;
    }

    .share-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #666;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .share-btn:hover {
        background: #8B7355;
        color: white;
        border-color: #8B7355;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-hero {
            height: 35vh;
            min-height: 250px;
            background-attachment: scroll;
        }

        .page-hero h1 {
            font-size: 2rem;
            letter-spacing: 6px;
        }

        .blog-detail-container {
            border-radius: 0;
            box-shadow: none;
        }

        .blog-header {
            padding: 2rem 1.5rem 1.5rem;
        }

        .blog-detail-title {
            font-size: 1.8rem;
        }

        .blog-content-wrapper {
            padding: 0 1.5rem 2rem;
        }

        .blog-detail-content {
            font-size: 1rem;
        }

        .blog-detail-content p:first-of-type::first-letter {
            font-size: 3rem;
        }

        .featured-image {
            max-height: 350px;
        }

        .author-section {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .share-section {
            flex-direction: column;
            gap: 1rem;
        }

        .blog-meta-info {
            flex-direction: column;
            gap: 0.75rem;
        }

        .meta-divider {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .page-hero h1 {
            font-size: 1.5rem;
            letter-spacing: 4px;
        }

        .blog-detail-title {
            font-size: 1.5rem;
        }

        .blog-detail-content {
            font-size: 0.95rem;
        }

        .blog-excerpt {
            font-size: 1.05rem;
            padding: 1.5rem;
        }

        .featured-image {
            max-height: 250px;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Hero -->
<div class="page-hero">
</div>

<!-- Back Button -->
<div class="back-button-section">
    <a href="{{ route('blogs.public') }}" class="btn-back">
        ← Back to Blog
    </a>
</div>

<!-- Blog Detail Container -->
<div class="blog-detail-container">
    <!-- Blog Header -->
    <div class="blog-header">
        <!-- Blog Title -->
        <h1 class="blog-detail-title">{{ $blog->title }}</h1>

        <!-- Meta Info -->
        <div class="blog-meta-info">
            @if($blog->author)
            <div class="meta-item">
                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>{{ $blog->author }}</span>
            </div>
            @endif
            
            @if($blog->author && $blog->published_at)
            <span class="meta-divider"></span>
            @endif
            
            @if($blog->published_at)
            <div class="meta-item">
                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $blog->published_at->format('F d, Y') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Featured Image -->
    @if($blog->image)
    <div class="featured-image-wrapper">
        <img src="{{ asset('storage/' . $blog->image) }}" 
             alt="{{ $blog->title }}" 
             class="featured-image">
    </div>
    @endif

    <!-- Blog Content -->
    <div class="blog-content-wrapper">
        @if($blog->excerpt)
        <div class="blog-excerpt">
            {{ $blog->excerpt }}
        </div>
        @endif

        <div class="blog-detail-content">
            {!! nl2br(e($blog->content)) !!}
        </div>

        <hr class="content-divider">

        <!-- Author Section -->
        @if($blog->author)
        <div class="author-section">
            <div class="author-avatar">
                {{ strtoupper(substr($blog->author, 0, 1)) }}
            </div>
            <div class="author-details">
                <div class="author-label">Written by</div>
                <div class="author-name">{{ $blog->author }}</div>
                <div class="author-bio">Wedding Specialist & Content Creator</div>
            </div>
        </div>
        @endif
        </div>
    </div>
</div>
@endsection