@extends('layouts.app')

@section('title', $blog->title)

@php use App\Helpers\ImageHelper; @endphp
@section('og_title', $blog->title)
@section('og_description', Str::limit(strip_tags($blog->excerpt ?? ''), 160) ?: 'Baca artikel terbaru dari Intimate Bali Wedding.')
@section('og_image', $blog->image ? asset('storage/' . ImageHelper::thumb($blog->image)) : asset('assets/Logo_IBW_2B.png'))
@section('og_type', 'article')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@400;500;600&display=swap');

    body { background: #fafafa; }

    /* Hero */
    .page-hero {
        background: linear-gradient(rgba(0,0,0,0.48), rgba(0,0,0,0.48)),
                    url('https://images.unsplash.com/photo-1522413452208-996ff3f3e740?w=1920&q=80');
        background-size: cover; background-position: center;
        height: 20vh; display: flex; align-items: center;
        justify-content: center; margin-top: -80px; margin-bottom: 3rem;
    }

    /* Back */
    .back-section { max-width: 900px; margin: 0 auto; padding: 0 2rem; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: #666; text-decoration: none; font-family: 'Lora', serif;
        font-size: 0.95rem; font-weight: 500; transition: all 0.3s;
    }

    .btn-back:hover { color: #8B7355; transform: translateX(-4px); }

    /* Blog container */
    .blog-detail-container {
        max-width: 900px; margin: 0 auto; padding: 0 2rem 6rem;
        background: white; box-shadow: 0 2px 20px rgba(0,0,0,0.05); border-radius: 12px;
    }

    /* Header */
    .blog-header { padding: 3rem 3rem 2rem; text-align: center; border-bottom: 2px solid #f0f0f0; margin-bottom: 3rem; }

    .blog-detail-title {
        font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 600;
        color: #1a1a1a; line-height: 1.3; margin-bottom: 1.5rem;
    }

    .blog-meta-info { display: flex; align-items: center; justify-content: center; gap: 1.5rem; flex-wrap: wrap; }

    .meta-item { display: flex; align-items: center; gap: 0.5rem; font-family: 'Lora', serif; font-size: 0.9rem; color: #666; }

    .meta-icon { width: 16px; height: 16px; color: #8B7355; }
    .meta-divider { width: 1px; height: 16px; background: #ddd; }

    /* Featured image */
    .featured-image-wrapper { width: 100%; margin: 0 0 3rem; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }

    .featured-image { width: 100%; height: auto; max-height: 500px; object-fit: cover; display: block; transition: transform 0.5s; }
    .featured-image-wrapper:hover .featured-image { transform: scale(1.02); }

    /* Content wrapper */
    .blog-content-wrapper { padding: 0 3rem 3rem; }

    /* Excerpt */
    .blog-excerpt {
        font-family: 'Lora', serif; font-size: 1.2rem; line-height: 1.8;
        color: #555; font-style: italic; margin-bottom: 3rem; padding: 2rem;
        background: #f8f9fa; border-left: 4px solid #8B7355; border-radius: 4px;
    }

    /*
    |--------------------------------------------------------------------------
    | PDF-generated content styles
    |
    | The content field contains:
    |   <p>paragraph text</p>
    |   <p>more text</p>
    |   <img src="/storage/blogs/pdf-images/..." style="width:100%...">
    |
    | These rules ensure text and images render beautifully as an article.
    |--------------------------------------------------------------------------
    */
    .blog-detail-content {
        font-family: 'Lora', serif; font-size: 1.05rem;
        line-height: 1.9; color: #333;
    }

    .blog-detail-content p {
        margin-bottom: 1.8rem; text-align: justify;
    }

    /* Drop-cap on very first paragraph */
    .blog-detail-content p:first-of-type::first-letter {
        font-size: 3.5rem; font-family: 'Playfair Display', serif; font-weight: 700;
        float: left; line-height: 1; margin: 0.1rem 0.5rem 0 0; color: #8B7355;
    }

    /* Page images from PDF */
    .blog-detail-content img {
        width: 100% !important;
        height: auto;
        display: block;
        margin: 2.5rem auto;
        border-radius: 4px;        /* radius kecil saja */
        box-shadow: none;          /* hapus shadow "kertas" */
        background: transparent;
    }
 
    /* ── Heading dari teks PDF ── */
    .blog-detail-content h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-top: 3rem;
        margin-bottom: 1rem;
        line-height: 1.3;
    }
 
    .blog-detail-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 600;
        color: #2a2a2a;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }
 
    .blog-detail-content ul {
        margin: 1rem 0 1.5rem 1.5rem;
        list-style: disc;
    }
 
    .blog-detail-content li {
        font-family: 'Lora', serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 0.5rem;
    }

    /* Download PDF link */
    .pdf-download-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem; background: #f0ebe4;
        border-radius: 10px; border: 1px solid #d4b896; margin-bottom: 2.5rem; gap: 1rem; flex-wrap: wrap;
    }

    .pdf-download-bar span {
        font-family: 'Lora', serif; font-size: 0.9rem; color: #6B5644;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .btn-download-pdf {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: linear-gradient(135deg, #8B7355, #6B5644); color: white;
        padding: 0.6rem 1.25rem; border-radius: 8px; text-decoration: none;
        font-family: 'Lora', serif; font-size: 0.88rem; font-weight: 600;
        transition: all 0.3s; white-space: nowrap;
    }

    .btn-download-pdf:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139,115,85,0.3); }

    /* Divider */
    .content-divider { margin: 3rem 0; border: none; height: 1px; background: linear-gradient(to right, transparent, #ddd, transparent); }

    /* Author */
    .author-section {
        margin-top: 4rem; padding: 2.5rem; background: #f8f9fa;
        border-radius: 8px; display: flex; align-items: center; gap: 2rem;
    }

    .author-avatar {
        width: 90px; height: 90px; border-radius: 50%;
        background: linear-gradient(135deg, #8B7355, #6B5644);
        display: flex; align-items: center; justify-content: center; color: white;
        font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 600;
        flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .author-label { font-family: 'Lora', serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: #8B7355; font-weight: 600; margin-bottom: 0.5rem; }
    .author-name  { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.25rem; }
    .author-bio   { font-family: 'Lora', serif; font-size: 0.95rem; color: #666; line-height: 1.6; }

    @media (max-width: 768px) {
        .page-hero { height: 35vh; }
        .blog-header { padding: 2rem 1.5rem 1.5rem; }
        .blog-detail-title { font-size: 1.8rem; }
        .blog-content-wrapper { padding: 0 1.5rem 2rem; }
        .author-section { flex-direction: column; text-align: center; padding: 2rem 1.5rem; }
        .pdf-download-bar { flex-direction: column; align-items: flex-start; }
        .blog-detail-container { border-radius: 0; box-shadow: none; }
    }
</style>
@endpush

@section('content')
<div class="page-hero"></div>

<div class="back-section">
    <a href="{{ route('blogs.public') }}" class="btn-back">← Back to Blog</a>
</div>

<div class="blog-detail-container">
    {{-- Header --}}
    <div class="blog-header">
        <h1 class="blog-detail-title">{{ $blog->title }}</h1>
        <div class="blog-meta-info">
            @if($blog->author)
            <div class="meta-item">
                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ $blog->author }}
            </div>
            @if($blog->published_at) <span class="meta-divider"></span> @endif
            @endif

            @if($blog->published_at)
            <div class="meta-item">
                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $blog->published_at->format('F d, Y') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Featured image --}}
    @if($blog->image)
    <div class="featured-image-wrapper">
        <x-image :src="$blog->image" :alt="$blog->title" class="featured-image" :eager="true" :thumb="false" />
    </div>
    @endif

    <div class="blog-content-wrapper">
        {{-- Excerpt --}}
        @if($blog->excerpt)
        <div class="blog-excerpt">{{ $blog->excerpt }}</div>
        @endif

        {{-- PDF download bar (shown only if PDF exists) --}}
        @if($blog->pdf)
        <div class="pdf-download-bar">
            <span>
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                    <path d="M14 2v6h6"/>
                </svg>
                Article sourced from PDF
            </span>
            <a href="{{ asset('storage/' . $blog->pdf) }}" download class="btn-download-pdf">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Download PDF
            </a>
        </div>
        @endif

        {{--
            Main content — rendered as raw HTML.
            Contains <p> paragraphs + <img> page images generated by processPdf().
        --}}
        <div class="blog-detail-content">
            {!! $blog->content !!}
        </div>

        <hr class="content-divider">

        {{-- Author --}}
        @if($blog->author)
        <div class="author-section">
            <div class="author-avatar">{{ strtoupper(substr($blog->author, 0, 1)) }}</div>
            <div>
                <div class="author-label">Written by</div>
                <div class="author-name">{{ $blog->author }}</div>
                <div class="author-bio">Wedding Specialist & Content Creator</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection