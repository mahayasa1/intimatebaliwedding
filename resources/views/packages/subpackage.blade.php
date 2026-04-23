@extends('layouts.app')
@php use App\Helpers\ImageHelper; @endphp

@section('title', $subpackage->name . ' — ' . $package->name . ' - Intimate Bali Wedding')

@push('styles')
<style>
    /* ===== HERO ===== */
    .sub-hero {
        position: relative;
        height: 55vh; min-height: 380px;
        display: flex; align-items: flex-end;
        margin-top: -80px; padding-top: 80px;
        overflow: hidden;
    }

    .sub-hero-bg {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        transition: transform 8s ease;
        transform: scale(1.04);
    }

    .sub-hero:hover .sub-hero-bg { transform: scale(1); }

    .sub-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.75) 0%, rgba(0,0,0,.15) 60%, transparent 100%);
    }

    .sub-hero-content {
        position: relative; z-index: 2;
        padding: 0 2rem 3rem;
        max-width: 1200px; margin: 0 auto; width: 100%;
    }

    .sub-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.82rem; color: rgba(255,255,255,0.7);
        margin-bottom: 1rem; flex-wrap: wrap;
    }

    .sub-breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.2s; }
    .sub-breadcrumb a:hover { color: #D4AF37; }
    .sub-breadcrumb .sep { color: rgba(255,255,255,0.4); }
    .sub-breadcrumb .current { color: white; }

    .sub-hero-label {
        font-size: 0.75rem; letter-spacing: 2.5px;
        color: #D4AF37; text-transform: uppercase;
        margin-bottom: 0.5rem; font-weight: 600;
    }

    .sub-hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700; color: white;
        line-height: 1.15; text-transform: uppercase; letter-spacing: 2px;
        text-align: center;
    }

    /* ===== MAIN CONTENT ===== */
    .sub-container {
        max-width: 1100px; margin: 0 auto;
        padding: 4rem 2rem;
    }

    /* Description block */
    .sub-description {
        max-width: 760px; margin: 0 auto 4rem;
        text-align: center;
    }

    .sub-description p {
        color: #555; font-size: 1.05rem; line-height: 1.9;
    }

    /* ===== GALLERY ===== */
    .sub-gallery-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem; color: #1a1a1a; text-align: center;
        margin-bottom: 1.75rem; text-transform: uppercase; letter-spacing: 2px;
    }

    /* Masonry-ish grid */
    .sub-gallery {
        columns: 3; column-gap: 1rem;
        max-width: 1000px; margin: 0 auto;
    }

    .sub-gallery-item {
        break-inside: avoid;
        margin-bottom: 1rem;
        border-radius: 10px; overflow: hidden;
        cursor: pointer; position: relative;
    }

    .sub-gallery-item img {
        width: 100%; display: block;
        transition: transform 0.45s ease;
    }

    .sub-gallery-item:hover img { transform: scale(1.06); }

    .sub-gallery-item-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0);
        display: flex; align-items: center; justify-content: center;
        transition: background 0.3s ease;
    }

    .sub-gallery-item:hover .sub-gallery-item-overlay { background: rgba(0,0,0,0.28); }

    .sub-gallery-item-overlay i {
        color: white; font-size: 1.75rem; opacity: 0;
        transform: scale(0.7); transition: all 0.3s ease;
    }

    .sub-gallery-item:hover .sub-gallery-item-overlay i { opacity: 1; transform: scale(1); }

    /* ===== LIGHTBOX ===== */
    .lightbox {
        display: none; position: fixed; z-index: 9999;
        inset: 0; background: rgba(0,0,0,0.95);
        align-items: center; justify-content: center;
    }

    .lightbox.active { display: flex; }

    .lightbox-content {
        max-width: 90vw; max-height: 90vh; position: relative;
    }

    .lightbox-content img {
        max-width: 100%; max-height: 90vh; object-fit: contain; border-radius: 4px;
    }

    .lb-close {
        position: fixed; top: 24px; right: 32px;
        color: white; font-size: 2rem; cursor: pointer;
        background: rgba(255,255,255,0.1); width: 48px; height: 48px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; z-index: 10001;
    }

    .lb-close:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }

    .lb-nav {
        position: fixed; top: 50%; transform: translateY(-50%);
        color: white; font-size: 2.2rem; cursor: pointer;
        background: rgba(255,255,255,0.1); width: 56px; height: 56px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; user-select: none; z-index: 10001;
    }

    .lb-nav:hover { background: rgba(255,255,255,0.2); }
    .lb-prev { left: 24px; }
    .lb-next { right: 24px; }

    .lb-counter {
        position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
        color: white; font-size: 0.9rem; background: rgba(0,0,0,0.6);
        padding: 8px 20px; border-radius: 20px; z-index: 10001;
    }

    /* ===== CTA ===== */
    .sub-cta {
        text-align: center;
        padding: 3.5rem 2rem;
        background: linear-gradient(135deg, #faf7f0 0%, #f5efe2 100%);
        border-radius: 20px;
        margin: 3rem 0 0;
    }

    .sub-cta h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem; color: #1a1a1a; margin-bottom: 0.75rem;
    }

    .sub-cta p { color: #777; margin-bottom: 2rem; font-size: 1rem; line-height: 1.7; }

    .btn-enquire {
        display: inline-flex; align-items: center; gap: 0.6rem;
        background: linear-gradient(135deg, #D4AF37 0%, #AA8B2A 100%);
        color: white; padding: 1rem 2.75rem;
        border-radius: 30px; text-decoration: none;
        font-weight: 700; font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 18px rgba(212,175,55,0.35);
    }

    .btn-enquire:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(212,175,55,0.45);
        color: white;
    }

    .btn-back {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: #AA8B2A; font-weight: 600; font-size: 0.9rem;
        text-decoration: none; margin-bottom: 1.5rem;
        transition: gap 0.2s ease;
    }

    .btn-back:hover { gap: 0.8rem; color: #D4AF37; }

    /* ===== OTHER SUBPACKAGES ===== */
    .other-subs-section {
        background: #f8f8f8; padding: 4rem 2rem; margin-top: 0;
    }

    .other-subs-section h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem; color: #1a1a1a; text-align: center;
        margin-bottom: 2.5rem; text-transform: uppercase; letter-spacing: 2px;
    }

    .other-subs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem; max-width: 1000px; margin: 0 auto;
    }

    .other-sub-card {
        background: white; border-radius: 14px; overflow: hidden;
        border: 1px solid #e8e8e8;
        text-decoration: none; display: block;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .other-sub-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(212,175,55,0.15);
        border-color: #D4AF37;
    }

    .other-sub-card img {
        width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block;
        transition: transform 0.4s ease;
    }

    .other-sub-card:hover img { transform: scale(1.05); }

    .other-sub-card-body { padding: 1.25rem 1.3rem 1.4rem; }

    .other-sub-card-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.4rem;
    }

    .other-sub-card-desc { color: #888; font-size: 0.85rem; line-height: 1.55; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .sub-hero { height: 45vh; }
        .sub-hero-title { font-size: 2rem; }
        .sub-container { padding: 2.5rem 1.25rem; }
        .sub-gallery { columns: 2; }
        .lb-nav { width: 44px; height: 44px; font-size: 1.6rem; }
        .lb-prev { left: 10px; }
        .lb-next { right: 10px; }
        .other-subs-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 480px) {
        .sub-gallery { columns: 1; }
        .other-subs-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="sub-hero">
    <div class="sub-hero-bg" style="
        background-image: url('{{ $subpackage->image ? asset("storage/" . $subpackage->image) : "https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=1920&q=80" }}');
    "></div>
    <div class="sub-hero-overlay"></div>
    <div class="sub-hero-content">
        {{-- <div class="sub-breadcrumb">
            <a href="{{ route('packages.public') }}">Packages</a>
            <span class="sep">›</span>
            <a href="{{ route('packages.show', $package->id) }}">{{ $package->name }}</a>
            <span class="sep">›</span>
            <span class="current">{{ $subpackage->name }}</span>
        </div> --}}
        {{-- <div class="sub-hero-label">{{ $package->name }}</div> --}}
        <h1 class="sub-hero-title">{{ $subpackage->name }}</h1>
    </div>
</section>

{{-- ===== MAIN ===== --}}
<section class="sub-container">

    {{-- Back link --}}
    <a href="{{ route('packages.show', $package->id) }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to {{ $package->name }}
    </a>

    {{-- Description --}}
    @if($subpackage->description)
    <div class="sub-description">
        <p>{!! nl2br(e($subpackage->description)) !!}</p>
    </div>
    @endif

    {{-- Gallery --}}
    @if(is_array($subpackage->photo) && count($subpackage->photo) > 0)
    <h3 class="sub-gallery-title">Gallery</h3>
    <div class="sub-gallery" id="subGallery">
        @foreach($subpackage->photo as $index => $photoPath)
        <div class="sub-gallery-item" onclick="openLb({{ $index }})">
            <img src="{{ asset('storage/' . ImageHelper::thumb($photoPath)) }}"
                 alt="{{ $subpackage->name }} photo {{ $index + 1 }}" loading="lazy">
            <div class="sub-gallery-item-overlay">
                <i class="fas fa-search-plus"></i>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- CTA --}}
    <div class="sub-cta">
        <h3>Interested in This Package?</h3>
        <p>Get in touch with us to discuss how we can make your dream wedding a reality.</p>
        <a href="{{ route('contact') }}" class="btn-enquire">
            <i class="fas fa-envelope"></i> Make an Enquiry
        </a>
    </div>

</section>

{{-- ===== LIGHTBOX ===== --}}
@if(is_array($subpackage->photo) && count($subpackage->photo) > 0)
<div id="lightbox" class="lightbox" onclick="closeLb(event)">
    <span class="lb-close" onclick="closeLb()"><i class="fas fa-times"></i></span>
    <span class="lb-nav lb-prev" onclick="event.stopPropagation(); changeLb(-1)"><i class="fas fa-chevron-left"></i></span>
    <div class="lightbox-content">
        <img id="lb-img" src="" alt="">
    </div>
    <span class="lb-nav lb-next" onclick="event.stopPropagation(); changeLb(1)"><i class="fas fa-chevron-right"></i></span>
    <div class="lb-counter"><span id="lb-cur">1</span> / {{ count($subpackage->photo) }}</div>
</div>
@endif

{{-- ===== OTHER SUBPACKAGES ===== --}}
@php
    $otherSubs = $package->subpackages->where('id', '!=', $subpackage->id)->values();
@endphp

@if($otherSubs->count() > 0)
<section class="other-subs-section">
    <h3>Other Options in {{ $package->name }}</h3>
    <div class="other-subs-grid">
        @foreach($otherSubs as $other)
        <a href="{{ route('subpackages.show', [$package->id, $other->id]) }}" class="other-sub-card">
            @if($other->image)
            <img src="{{ asset('storage/' . ImageHelper::thumb($other->image)) }}" alt="{{ $other->name }}">
            @else
            <div style="width:100%;aspect-ratio:4/3;background:linear-gradient(135deg,#f5f0eb,#ede5d8);
                        display:flex;align-items:center;justify-content:center;color:#D4AF37;font-size:2rem;">
                <i class="fas fa-gem"></i>
            </div>
            @endif
            <div class="other-sub-card-body">
                <div class="other-sub-card-name">{{ $other->name }}</div>
                @if($other->description)
                <div class="other-sub-card-desc">{{ Str::limit($other->description, 70) }}</div>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

@if(is_array($subpackage->photo) && count($subpackage->photo) > 0)
<script>
const photos = @json($subpackage->photo);
let cur = 0;

function openLb(index) {
    cur = index;
    document.getElementById('lb-img').src = '{{ asset("storage") }}/' + photos[cur];
    document.getElementById('lb-cur').textContent = cur + 1;
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLb(e) {
    if (!e || e.target.id === 'lightbox' || e.target.closest('.lb-close')) {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }
}

function changeLb(dir) {
    cur = (cur + dir + photos.length) % photos.length;
    document.getElementById('lb-img').src = '{{ asset("storage") }}/' + photos[cur];
    document.getElementById('lb-cur').textContent = cur + 1;
}

document.addEventListener('keydown', e => {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === 'ArrowLeft') changeLb(-1);
    if (e.key === 'ArrowRight') changeLb(1);
    if (e.key === 'Escape') closeLb();
});
</script>
@endif

@endsection