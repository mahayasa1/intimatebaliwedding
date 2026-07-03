@extends('layouts.app')
@php use App\Helpers\ImageHelper; @endphp

@section('title', 'Gallery - Intimate Bali Wedding')

@push('styles')
<style>
    /* ── SHARED DESIGN TOKENS ── */
    :root {
        --gold:        #D4AF37;
        --gold-dark:   #AA8B2A;
        --gold-light:  rgba(212,175,55,0.15);
        --text-dark:   #1a1a1a;
        --text-mid:    #555;
        --text-muted:  #999;
        --radius-card: 12px;
        --font-serif:  'Playfair Display', serif;
        --font-sans:   'Inter', sans-serif;
        --transition:  all 0.5s cubic-bezier(0.4,0,0.2,1);
    }

    /* ── HERO ── */
    .gallery-hero {
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

    .gallery-hero h1 {
        font-family: var(--font-serif);
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 0.5rem;
    }

    .gallery-hero p {
        font-size: clamp(0.85rem, 2vw, 1.1rem);
        font-weight: 300;
        letter-spacing: 2px;
        opacity: 0.9;
    }

    /* ── SECTION WRAPPER ── */
    .gallery-section {
        padding: 4rem 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ── INTRO ── */
    .gallery-intro {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .gallery-intro h2 {
        font-family: var(--font-serif);
        color: var(--gold);
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 700;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .gallery-intro p {
        color: var(--text-mid);
        font-size: 1rem;
        line-height: 1.7;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ── TAB SWITCHER ── */
    .tab-switcher {
        display: flex;
        justify-content: center;
        gap: 0;
        margin-bottom: 2.5rem;
        border: 2px solid var(--gold);
        border-radius: 30px;
        overflow: hidden;
        max-width: 360px;
        margin-left: auto;
        margin-right: auto;
    }

    .tab-btn {
        flex: 1;
        padding: 0.7rem 1.25rem;
        background: white;
        color: var(--gold);
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background 0.25s, color 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        font-family: var(--font-sans);
    }

    .tab-btn:first-child { border-radius: 28px 0 0 28px; }
    .tab-btn:last-child  { border-radius: 0 28px 28px 0; }
    .tab-btn.active      { background: var(--gold); color: white; }

    .tab-btn .tab-count {
        background: rgba(255,255,255,0.3);
        border-radius: 10px;
        padding: 1px 6px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .tab-btn:not(.active) .tab-count {
        background: var(--gold-light);
    }

    /* ── FILTER PILLS ── */
    .gallery-filters {
        display: flex;
        justify-content: center;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }

    .filter-btn {
        padding: 0.5rem 1.25rem;
        border: 2px solid var(--gold);
        background: white;
        color: var(--gold);
        border-radius: 25px;
        cursor: pointer;
        transition: background 0.25s, color 0.25s, transform 0.2s;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.78rem;
        font-family: var(--font-sans);
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--gold);
        color: white;
        transform: translateY(-2px);
    }

    /* ── SHARED GRID ── */
    .gallery-grid,
    .video-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    /* ── SHARED CARD BASE ── */
    .gallery-item,
    .video-grid-item {
        position: relative;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        border-radius: var(--radius-card);
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: block;
        text-decoration: none;
        background: #f0f0f0;
    }

    .gallery-item:hover,
    .video-grid-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.18);
    }

    .gallery-item img,
    .video-grid-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .gallery-item:hover img,
    .video-grid-item:hover img {
        transform: scale(1.05);
    }

    /* ── OVERLAY ── */
    .card-overlay {
        position: absolute;
        inset: 0;
        padding: 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 55%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        text-align: center;
        pointer-events: none;
    }

    .card-overlay::before {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 0;
        background: rgba(0,0,0,0.35);
        transition: height 0.5s cubic-bezier(0.4,0,0.2,1);
        z-index: 1;
    }

    .gallery-item:hover .card-overlay::before,
    .video-grid-item:hover .card-overlay::before {
        height: 100%;
    }

    /* ── CARD CONTENT ── */
    .card-content {
        position: relative;
        z-index: 2;
        transition: var(--transition);
    }

    .gallery-item:hover .card-content,
    .video-grid-item:hover .card-content {
        transform: translateY(-28%);
    }

    .card-category-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.8rem;
        background: rgba(212,175,55,0.92);
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 0.5rem;
        font-family: var(--font-sans);
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        color: white;
    }

    .card-description {
        font-size: 0.88rem;
        line-height: 1.6;
        color: rgba(255,255,255,0.9);
        margin: 0.75rem 0 0;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transform: translateY(16px);
        transition: max-height 0.5s ease 0.15s, opacity 0.5s ease 0.15s, transform 0.5s ease 0.15s;
    }

    .gallery-item:hover .card-description,
    .video-grid-item:hover .card-description {
        max-height: 120px;
        opacity: 1;
        transform: translateY(0);
    }

    .card-cta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 1rem;
        background: rgba(212,175,55,0.9);
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        color: white;
        margin-top: 0.6rem;
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.5s ease 0.25s, transform 0.5s ease 0.25s;
    }

    .video-grid-item .card-cta-badge {
        background: rgba(231,76,60,0.9);
    }

    .gallery-item:hover .card-cta-badge,
    .video-grid-item:hover .card-cta-badge {
        opacity: 1;
        transform: translateY(0);
    }

    .top-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        z-index: 5;
        background: var(--gold);
        color: white;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: var(--font-sans);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .video-play-btn {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 60px; height: 60px;
        background: rgba(255,255,255,0.92);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #e74c3c;
        font-size: 1.5rem;
        z-index: 3;
        transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.4s ease;
        pointer-events: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    }

    .video-play-btn i { margin-left: 3px; }

    .video-grid-item:hover .video-play-btn {
        transform: translate(-50%, -50%) scale(0);
        opacity: 0;
    }

    /* ── VIDEO MODAL ── */
    .video-modal {
        display: none;
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,0.93);
        align-items: center; justify-content: center;
        padding: 2rem;
    }

    .video-modal.active { display: flex; }

    .video-modal-inner {
        width: 100%; max-width: 900px; position: relative;
    }

    .video-modal-inner iframe {
        width: 100%;
        aspect-ratio: 16/9;
        border: none;
        border-radius: var(--radius-card);
    }

    .modal-close {
        position: fixed; top: 20px; right: 24px;
        background: rgba(255,255,255,0.12); color: white;
        border: none; border-radius: 50%;
        width: 48px; height: 48px; font-size: 1.4rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background 0.3s, transform 0.3s; z-index: 10001;
    }

    .modal-close:hover { background: rgba(255,255,255,0.22); transform: rotate(90deg); }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center; padding: 4rem 2rem; grid-column: 1/-1;
    }

    .empty-icon { font-size: 3.5rem; color: var(--gold); opacity: 0.25; margin-bottom: 1rem; }
    .empty-state p { color: var(--text-muted); font-size: 1rem; }

    /* ── TAB PANELS ── */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* ── PAGINATION ── */
    .simple-pagination {
        margin-top: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-page {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #6B5644;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-page:hover {
        background: #f4f4f4;
        color: #6B5644;
    }

    /* ── TESTIMONIALS ── */
    .testimonials-section {
        background: #f9f9f9;
        padding: 5rem 2rem;
        margin-top: 2rem;
    }

    .testimonials-header {
        text-align: center;
        margin-bottom: 1rem;
    }

    .testimonials-header h2 {
        font-family: var(--font-serif);
        color: var(--gold);
        font-size: clamp(1.75rem, 3.5vw, 2.5rem);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .testimonials-header p {
        color: var(--text-mid);
        font-size: 1rem;
    }

    .google-verified {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.4rem 1.25rem;
        border-radius: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-top: 0.75rem;
        font-size: 0.88rem;
        font-weight: 500;
    }

    .google-stats {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 3rem;
        margin: 2rem 0 3rem;
        flex-wrap: wrap;
    }

    .stat-item { text-align: center; }

    .stat-number {
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 700;
        color: var(--gold);
        font-family: var(--font-serif);
        line-height: 1;
    }

    .stat-label {
        font-size: 0.78rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 0.25rem;
    }

    .testimonials-grid {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .testimonial-card {
        background: white;
        border-radius: var(--radius-card);
        padding: 1.75rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        position: relative;
        border-left: 3px solid var(--gold);
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .testimonial-header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.875rem;
    }

    .testimonial-avatar {
        width: 52px; height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--gold);
        flex-shrink: 0;
    }

    .testimonial-info h4 {
        font-family: var(--font-serif);
        font-size: 1.05rem;
        margin: 0 0 0.2rem;
        color: var(--text-dark);
    }

    .testimonial-time { font-size: 0.78rem; color: var(--text-muted); }

    .testimonial-rating { color: var(--gold); font-size: 1.1rem; margin-bottom: 0.75rem; letter-spacing: 2px; }

    .testimonial-review {
        color: var(--text-mid);
        line-height: 1.7;
        font-size: 0.9rem;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-style: italic;
    }

    .google-maps-badge {
        position: absolute;
        top: 1rem; right: 1rem;
        width: 28px; height: 28px;
        opacity: 0.5;
    }

    .testimonials-cta { text-align: center; margin-top: 2.5rem; }

    .btn-google-maps {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: #4285f4;
        color: white;
        text-decoration: none;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 4px 15px rgba(66,133,244,0.25);
    }

    .btn-google-maps:hover {
        background: #3367d6;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66,133,244,0.4);
        color: white;
    }

    .no-reviews { text-align: center; padding: 3rem; color: var(--text-muted); }

    /* ── LOADING STATE ── */
    #galleryGrid.is-loading {
        opacity: 0.4;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .gallery-hero h1 { font-size: 2.2rem; }
        .gallery-grid,
        .video-grid { grid-template-columns: 1fr; gap: 1rem; }
        .gallery-section { padding: 3rem 1rem; }
        .testimonials-grid { grid-template-columns: 1fr; }
        .testimonials-section { padding: 3rem 1rem; }
        .gallery-item:hover .card-content,
        .video-grid-item:hover .card-content { transform: translateY(-20%); }
    }

    @media (max-width: 480px) {
        .google-stats { gap: 1.5rem; }
        .tab-switcher { max-width: 300px; }
    }
</style>
@endpush

@section('content')
<!-- Hero -->
<section class="gallery-hero">
    <div>
        <h1>GALLERY & TESTIMONIALS</h1>
        <p>Relive The Previous Beautiful Moments And Kind Words.</p>
    </div>
</section>

<section class="gallery-section">
    <!-- Intro -->
    <div class="gallery-intro">
        <h2>Our Beautiful Moments</h2>
        <p>Browse through our collection of beautiful wedding moments captured in paradise.</p>
    </div>

    <!-- Tab Switcher -->
    <div class="tab-switcher">
        <button class="tab-btn active" data-tab="photos" onclick="switchTab('photos', this)">
            <i class="fas fa-images"></i> Photos
            <span class="tab-count">{{ $photos->total() }}</span>
        </button>
        <button class="tab-btn" data-tab="videos" onclick="switchTab('videos', this)">
            <i class="fab fa-youtube"></i> Videos
            <span class="tab-count">{{ $videoGalleries->count() }}</span>
        </button>
    </div>

    {{-- ===== TAB FOTO ===== --}}
    <div class="tab-panel active" id="tab-photos">

        @if($categories->count() > 0)
        <div class="gallery-filters" id="photoFilters">
            <button type="button"
                    class="filter-btn {{ !request('category') || request('category') == 'all' ? 'active' : '' }}"
                    data-category="all">
                All
            </button>
            @foreach($categories as $category)
                <button type="button"
                        class="filter-btn {{ request('category') == $category ? 'active' : '' }}"
                        data-category="{{ $category }}">
                    {{ $category }}
                </button>
            @endforeach
        </div>
        @endif

        <div class="gallery-grid" id="galleryGrid">
            @forelse($photos as $gallery)
            @php
                $additionalPhotos = is_array($gallery->photo) ? $gallery->photo : [];
                $totalPhotos = ($gallery->image ? 1 : 0) + count($additionalPhotos);
                $previewImage = $gallery->image ?: ($additionalPhotos[0] ?? null);
            @endphp
            <a href="{{ route('gallery.show', $gallery->id) }}"
               class="gallery-item"
               data-category="{{ $gallery->category ?? 'Other' }}">
            
                @if($gallery->category)
                <span class="top-badge"><i class="fas fa-tag"></i> {{ $gallery->category }}</span>
                @endif
            
                @if($previewImage)
                <img src="{{ asset('storage/' . ImageHelper::thumb($previewImage)) }}"
                     alt="{{ $gallery->title }}"
                     loading="lazy"
                     class="gallery-img"
                     data-fallback="{{ asset('storage/' . $previewImage) }}">
                @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0f0f0;">
                    <i class="fas fa-image" style="font-size:3rem;color:#ccc;"></i>
                </div>
                @endif

                <div class="card-overlay">
                    <div class="card-content">
                        <div class="card-title">{{ $gallery->title }}</div>

                        @if(!empty($gallery->description))
                        <div class="card-description">{{ Str::limit($gallery->description, 100) }}</div>
                        @endif

                        <div class="card-cta-badge">
                            <i class="fas fa-images"></i>
                            View {{ $totalPhotos }} {{ $totalPhotos == 1 ? 'Image' : 'Images' }}
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-images"></i></div>
                <p>Belum ada foto di gallery.</p>
            </div>
            @endforelse
        </div>

        <div id="galleryPaginationWrapper">
            @if($photos->hasPages())
            <div class="simple-pagination" id="galleryPagination">

                @if($photos->onFirstPage())
                    <span class="btn-page" style="opacity:.5;">
                        <i class="fas fa-angle-left"></i> Previous
                    </span>
                @else
                    <a href="#" class="btn-page ajax-page-link" data-page="{{ $photos->currentPage() - 1 }}">
                        <i class="fas fa-angle-left"></i> Previous
                    </a>
                @endif

                @foreach($photos->getUrlRange(1, $photos->lastPage()) as $page => $url)
                    @if($page == $photos->currentPage())
                        <span class="btn-page" style="background:#8B7355;color:#fff;border-color:#8B7355;">
                            {{ $page }}
                        </span>
                    @else
                        <a href="#" class="btn-page ajax-page-link" data-page="{{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($photos->hasMorePages())
                    <a href="#" class="btn-page ajax-page-link" data-page="{{ $photos->currentPage() + 1 }}">
                        Next <i class="fas fa-angle-right"></i>
                    </a>
                @else
                    <span class="btn-page" style="opacity:.5;">
                        Next <i class="fas fa-angle-right"></i>
                    </span>
                @endif

            </div>
            @endif
        </div>
    </div>

    {{-- ===== TAB VIDEO ===== --}}
    <div class="tab-panel" id="tab-videos">
        @php
            $videoCategories = $videoGalleries->pluck('category')->filter()->unique()->sort()->values();
        @endphp

        @if($videoCategories->count() > 0)
        <div class="gallery-filters" id="videoFilters">
            <button class="filter-btn active" data-category="all">All</button>
            @foreach($videoCategories as $category)
            <button class="filter-btn" data-category="{{ $category }}">{{ $category }}</button>
            @endforeach
        </div>
        @endif

        @if($videoGalleries->count() > 0)
        <div class="video-grid" id="videoGrid">
            @foreach($videoGalleries as $video)
            <div class="video-grid-item"
                 data-category="{{ $video->category ?? 'Other' }}"
                 onclick="openVideoModal('{{ $video->youtube_embed_url }}', '{{ addslashes($video->title) }}')">

                <span class="top-badge"><i class="fab fa-youtube"></i> VIDEO</span>

                @if($video->youtube_thumbnail)
                <img src="{{ $video->youtube_thumbnail }}"
                     alt="{{ $video->title }}"
                     loading="lazy"
                     onerror="this.src='https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg'">
                @else
                <div style="width:100%;height:100%;background:#1a1a1a;display:flex;align-items:center;justify-content:center;">
                    <i class="fab fa-youtube" style="font-size:4rem;color:#e74c3c;opacity:0.35;"></i>
                </div>
                @endif

                <div class="video-play-btn">
                    <i class="fas fa-play"></i>
                </div>

                <div class="card-overlay">
                    <div class="card-content">
                        <div class="card-title">{{ $video->title }}</div>

                        @if($video->description)
                        <div class="card-description">{{ Str::limit($video->description, 100) }}</div>
                        @endif

                        <div class="card-cta-badge" style="background:rgba(231,76,60,0.9);">
                            <i class="fab fa-youtube"></i> Watch Video
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fab fa-youtube"></i></div>
            <p>Belum ada video di gallery.</p>
        </div>
        @endif
    </div>
</section>

{{-- Video Modal --}}
<div id="videoModal" class="video-modal" onclick="closeVideoModal(event)">
    <button class="modal-close" onclick="closeVideoModal()">
        <i class="fas fa-times"></i>
    </button>
    <div class="video-modal-inner">
        <iframe id="modalIframe" src="" allowfullscreen
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
        </iframe>
    </div>
</div>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="testimonials-header">
        <h2>What Our Couples Say</h2>
        <p>Real reviews from Google Maps.</p>
        @if(!empty($businessStats))
        <div class="google-verified">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Google_Maps_icon_%282015-2020%29.svg/512px-Google_Maps_icon_%282015-2020%29.svg.png"
                 alt="Google" style="width:20px;height:20px;">
            <span>Verified by Google</span>
        </div>
        @endif
    </div>

    @if(!empty($businessStats) && isset($businessStats['rating']))
    <div class="google-stats">
        <div class="stat-item">
            <div class="stat-number">{{ number_format($businessStats['rating'], 1) }}</div>
            <div class="stat-label">Average Rating</div>
            <div class="testimonial-rating" style="margin-top:0.4rem;">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= floor($businessStats['rating']) ? '★' : '☆' }}
                @endfor
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($businessStats['total_reviews']) }}</div>
            <div class="stat-label">Total Reviews</div>
        </div>
    </div>
    @endif

    @if(isset($googleReviews['success']) && $googleReviews['success'] && !empty($googleReviews['reviews']))
    <div class="testimonials-grid">
        @foreach($googleReviews['reviews'] as $review)
        <div class="testimonial-card" onclick="window.open('{{ $googleReviews['place_url'] }}', '_blank')">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Google_Maps_icon_%282015-2020%29.svg/512px-Google_Maps_icon_%282015-2020%29.svg.png"
                 alt="Google Maps" class="google-maps-badge">
            <div class="testimonial-header-content">
                @if(isset($review['author_photo']) && $review['author_photo'])
                <img src="{{ $review['author_photo'] }}" alt="{{ $review['author_name'] }}"
                     class="testimonial-avatar"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                @endif
                <div class="testimonial-avatar"
                     style="background:var(--gold);display:{{ isset($review['author_photo']) && $review['author_photo'] ? 'none' : 'flex' }};align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.3rem;">
                    {{ strtoupper(substr($review['author_name'], 0, 1)) }}
                </div>
                <div class="testimonial-info">
                    <h4>{{ $review['author_name'] }}</h4>
                    <div class="testimonial-time">{{ $review['relative_time'] }}</div>
                </div>
            </div>
            <div class="testimonial-rating">
                {{ str_repeat('★', $review['rating']) }}{{ str_repeat('☆', 5 - $review['rating']) }}
            </div>
            <div class="testimonial-review">{{ $review['text'] }}</div>
        </div>
        @endforeach
    </div>
    @else
    <div class="no-reviews">
        <p>{{ $googleReviews['error'] ?? 'Reviews akan muncul setelah Google Maps dikonfigurasi.' }}</p>
    </div>
    @endif

    <div class="testimonials-cta">
        <a href="{{ $googleReviews['place_url'] ?? '#' }}" target="_blank" class="btn-google-maps">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            See All Reviews on Google Maps
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>

document.addEventListener('error', function (e) {
    const img = e.target;
    if (img.tagName === 'IMG' && img.classList.contains('gallery-img') && img.dataset.fallback) {
        const fb = img.dataset.fallback;
        img.dataset.fallback = '';
        img.src = fb;
    }
}, true); // capture:true wajib karena event 'error' pada <img> tidak bubble

// ── TAB SWITCHER ──
function switchTab(tab, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
    history.replaceState(null, '', '#' + tab);
}

const hash = location.hash.replace('#', '');
if (hash === 'videos') {
    const videoBtn = document.querySelector('[data-tab="videos"]');
    if (videoBtn) switchTab('videos', videoBtn);
}

// ── FILTER VIDEO (client-side, tidak diubah) ──
document.querySelectorAll('#tab-videos .filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('#tab-videos .filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.category;
        document.querySelectorAll('#videoGrid .video-grid-item').forEach(item => {
            item.style.display = (cat === 'all' || item.dataset.category === cat) ? '' : 'none';
        });
    });
});

// ── VIDEO MODAL ──
function openVideoModal(embedUrl, title) {
    if (!embedUrl) return;
    document.getElementById('modalIframe').src = embedUrl + '&autoplay=1';
    document.getElementById('videoModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeVideoModal(e) {
    if (e && e.target !== document.getElementById('videoModal') && !e.target.closest('.modal-close')) return;
    document.getElementById('modalIframe').src = '';
    document.getElementById('videoModal').classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeVideoModal({ target: document.getElementById('videoModal') });
});

// ── AJAX GALLERY FILTER & PAGINATION (FOTO) ──
(function () {
    const filterUrl = "{{ route('gallery.filter') }}";
    let currentCategory = "{{ request('category', 'all') }}";
    let isLoading = false;

    function loadGallery(category, page) {
        if (isLoading) return;
        isLoading = true;

        const grid = document.getElementById('galleryGrid');
        grid.classList.add('is-loading');

        const params = new URLSearchParams({ category: category, page: page });

        fetch(filterUrl + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Request failed');
            return res.json();
        })
        .then(function (data) {
            grid.innerHTML = data.html;
            document.getElementById('galleryPaginationWrapper').innerHTML = data.pagination || '';

            currentCategory = category;

            bindPaginationEvents();

            const targetUrl = new URL(window.location.href);
            if (category && category !== 'all') {
                targetUrl.searchParams.set('category', category);
            } else {
                targetUrl.searchParams.delete('category');
            }
            history.replaceState(null, '', targetUrl.pathname + targetUrl.search + '#photos');
        })
        .catch(function (err) {
            console.error('Gallery filter error:', err);
        })
        .finally(function () {
            grid.classList.remove('is-loading');
            isLoading = false;
        });
    }

    function bindFilterEvents() {
        document.querySelectorAll('#photoFilters .filter-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('#photoFilters .filter-btn').forEach(function (b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');
                loadGallery(this.dataset.category, 1);
            });
        });
    }

    function bindPaginationEvents() {
        document.querySelectorAll('#galleryPaginationWrapper .ajax-page-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page, 10) || 1;
                loadGallery(currentCategory, page);
            });
        });
    }

    bindFilterEvents();
    bindPaginationEvents();
})();
</script>
@endpush