@extends('layouts.app')

@section('title', $blog->title)

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Work+Sans:wght@400;500;600&display=swap');

    .blog-hero {
        position: relative;
        height: 500px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: flex-end;
        margin-bottom: 3rem;
    }

    .blog-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        padding: 3rem 2rem;
        color: white;
    }

    .blog-category {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(139, 115, 85, 0.9);
        border-radius: 6px;
        font-family: 'Work Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .blog-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .blog-meta {
        font-family: 'Work Sans', sans-serif;
        display: flex;
        gap: 2rem;
        font-size: 0.95rem;
        opacity: 0.95;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Blog Content */
    .blog-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 2rem 4rem;
    }

    .blog-excerpt {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.35rem;
        line-height: 1.8;
        color: #555;
        font-style: italic;
        margin-bottom: 3rem;
        padding: 2rem;
        background: #f8f9fa;
        border-left: 5px solid #8B7355;
        border-radius: 8px;
    }

    .blog-content {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.125rem;
        line-height: 1.9;
        color: #333;
        white-space: pre-wrap;
    }

    .blog-content p {
        margin-bottom: 1.5rem;
    }

    .blog-content h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-top: 3rem;
        margin-bottom: 1.5rem;
    }

    .blog-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    /* Back Button */
    .back-section {
        max-width: 900px;
        margin: 0 auto 2rem;
        padding: 0 2rem;
    }

    .btn-back {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
        color: white;
    }

    /* Author Section */
    .author-section {
        max-width: 900px;
        margin: 3rem auto 0;
        padding: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .author-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1rem;
    }

    .author-name {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.1rem;
        color: #666;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .blog-hero {
            height: 350px;
        }

        .blog-title {
            font-size: 2rem;
        }

        .blog-meta {
            flex-direction: column;
            gap: 0.75rem;
        }

        .blog-excerpt {
            font-size: 1.15rem;
            padding: 1.5rem;
        }

        .blog-content {
            font-size: 1.05rem;
        }

        .blog-container {
            padding: 0 1.5rem 3rem;
        }

        .back-section {
            padding: 0 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Blog Hero -->
<div class="blog-hero" style="background-image: url('{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/default-blog.jpg') }}');">
    <div class="hero-content">
        <div class="blog-category">Blog Post</div>
        <h1 class="blog-title">{{ $blog->title }}</h1>
        <div class="blog-meta">
            @if($blog->author)
            <div class="meta-item">
                <span>👤</span>
                <span>{{ $blog->author }}</span>
            </div>
            @endif
            @if($blog->published_at)
            <div class="meta-item">
                <span>📅</span>
                <span>{{ $blog->published_at->format('F d, Y') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="back-section">
    <a href="{{ route('blogs.index') }}" class="btn-back">
        ← Back to Blog
    </a>
</div>

<!-- Blog Content -->
<div class="blog-container">
    @if($blog->excerpt)
    <div class="blog-excerpt">
        {{ $blog->excerpt }}
    </div>
    @endif

    <div class="blog-content">
        {{ $blog->content }}
    </div>

    <!-- Author Info -->
    @if($blog->author)
    <div class="author-section">
        <h3 class="author-title">About the Author</h3>
        <p class="author-name">Written by <strong>{{ $blog->author }}</strong></p>
    </div>
    @endif
</div>
@endsection