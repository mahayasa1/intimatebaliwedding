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

        <!-- Share Section -->
        <div class="share-section">
            <span class="share-label">Share this article:</span>
            <div class="share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                   target="_blank" 
                   class="share-btn" 
                   title="Share on Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" 
                   target="_blank" 
                   class="share-btn"
                   title="Share on Twitter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($blog->title) }}" 
                   target="_blank" 
                   class="share-btn"
                   title="Share on LinkedIn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . request()->url()) }}" 
                   target="_blank" 
                   class="share-btn"
                   title="Share on WhatsApp">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection