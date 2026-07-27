@extends('layouts.app')
@php use App\Helpers\ImageHelper; @endphp

@section('title', $gallery->title . ' - Intimate Bali Wedding')

@section('og_title', $gallery->title . ' - Intimate Bali Wedding')
@section('og_description', Str::limit(strip_tags($gallery->description ?? ''), 160) ?: 'Lihat momen indah pernikahan di Bali bersama Intimate Bali Wedding.')
@section('og_image', $gallery->thumbnail ?: asset('assets/Logo_IBW_2B.png'))
@section('og_type', 'article')

@push('styles')
<style>
    .show-hero {
        background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                    url('{{ asset('storage/' . $gallery->image) }}');
        background-size: cover; background-position: center;
        height: 50vh; display: flex; align-items: center; justify-content: center;
        color: white; text-align: center; margin-top: -80px; padding-top: 80px;
    }

    .show-hero h1 {
        font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;
    }

    .show-hero p {
        font-size: 1rem; letter-spacing: 2px; text-transform: uppercase; color: #D4AF37;
    }

    .show-section {
        max-width: 1400px; margin: 0 auto; padding: 4rem 2rem;
    }

    /* Back button */
    .btn-back {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: #D4AF37; text-decoration: none; font-weight: 600;
        font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 3rem; transition: gap 0.3s;
    }

    .btn-back:hover { gap: 0.8rem; color: #D4AF37; }

    /* Description */
    .show-description {
        text-align: center; max-width: 700px; margin: 0 auto 3rem;
        color: #666; line-height: 1.8; font-size: 1.05rem;
    }

    /* Photo count badge */
    .photo-count {
        text-align: center; margin-bottom: 2rem;
        font-size: 0.85rem; color: #999; text-transform: uppercase; letter-spacing: 1px;
    }

    /* ── PHOTO GRID ── */
    .photos-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .photo-item {
        position: relative; overflow: hidden; border-radius: 10px;
        aspect-ratio: 1/1; cursor: pointer; background: #f0f0f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .photo-item:first-child {
        grid-column: span 2;
        grid-row: span 2;
        aspect-ratio: unset;
    }

    .photo-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .photo-item img {
        width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;
    }

    .photo-item:hover img { transform: scale(1.05); }

    .photo-item .zoom-icon {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0); display: flex; align-items: center; justify-content: center;
        transition: background 0.3s;
    }

    .photo-item:hover .zoom-icon { background: rgba(0,0,0,0.3); }

    .zoom-icon i {
        color: white; font-size: 1.8rem; opacity: 0; transform: scale(0.7);
        transition: all 0.3s ease;
    }

    .photo-item:hover .zoom-icon i { opacity: 1; transform: scale(1); }

    /* ── VIDEO GRID (sama dengan foto) ── */
    .videos-section-title {
        font-family: 'Playfair Display', serif;
        color: #D4AF37; font-size: 1.5rem; font-weight: 600;
        text-align: center; margin: 4rem 0 1rem;
    }

    .video-count {
        text-align: center; margin-bottom: 2rem;
        font-size: 0.85rem; color: #999; text-transform: uppercase; letter-spacing: 1px;
    }

    .videos-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .videos-grid .video-grid-item:first-child {
        grid-column: span 2;
        grid-row: span 2;
        aspect-ratio: unset;
    }

    .video-grid-item {
        position: relative; overflow: hidden; border-radius: 10px;
        aspect-ratio: 1/1; cursor: pointer; background: #1a1a1a;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .video-grid-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .video-grid-item img {
        width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;
    }

    .video-grid-item:hover img { transform: scale(1.05); }

    /* Play button di tengah */
    .video-play-icon {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 56px; height: 56px; background: rgba(255,255,255,0.92);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: #e74c3c; font-size: 1.4rem; z-index: 3;
        transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.4s ease;
        pointer-events: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    }

    .video-grid-item:hover .video-play-icon {
        transform: translate(-50%, -50%) scale(0);
        opacity: 0;
    }

    /* Shared overlay */
    .video-grid-overlay {
        position: absolute; bottom: 0; left: 0; right: 0; height: 100%;
        padding: 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 50%);
        color: white; display: flex; flex-direction: column; justify-content: flex-end;
        text-align: center; pointer-events: none;
    }

    .video-grid-overlay::before {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 0;
        background: rgba(0,0,0,0.356);
        transition: height 0.5s cubic-bezier(0.4,0,0.2,1); z-index: 1;
    }

    .video-grid-item:hover .video-grid-overlay::before { height: 100%; }

    .video-grid-content {
        position: relative; z-index: 2; transition: all 0.5s cubic-bezier(0.4,0,0.2,1);
    }

    .video-grid-item:hover .video-grid-content { transform: translateY(-25%); }

    .video-grid-category {
        font-size: 0.7rem; color: #D4AF37; text-transform: uppercase;
        letter-spacing: 2px; margin-bottom: 0.4rem; font-weight: 600;
    }

    .video-grid-title {
        font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 600; color: white;
    }

    .video-grid-desc {
        font-size: 0.85rem; line-height: 1.5; color: rgba(255,255,255,0.85); margin: 0.75rem 0;
        max-height: 0; opacity: 0; overflow: hidden;
        transform: translateY(15px); transition: all 0.5s ease 0.2s;
    }

    .video-grid-item:hover .video-grid-desc { max-height: 120px; opacity: 1; transform: translateY(0); }

    .video-grid-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(231,76,60,0.9);
        padding: 0.35rem 0.9rem; border-radius: 20px; font-size: 0.8rem;
        font-weight: 600; color: white; opacity: 0; transform: translateY(15px);
        transition: all 0.5s ease 0.3s;
    }

    .video-grid-item:hover .video-grid-badge { opacity: 1; transform: translateY(0); }

    /* Video Modal */
    .video-modal {
        display: none;
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,0.92);
        align-items: center; justify-content: center;
        padding: 2rem;
    }

    .video-modal.active { display: flex; }

    .video-modal-inner {
        width: 100%; max-width: 900px; position: relative;
    }

    .video-modal-inner iframe {
        width: 100%; aspect-ratio: 16/9;
        border: none; border-radius: 12px;
    }

    .modal-close {
        position: fixed; top: 20px; right: 24px;
        background: rgba(255,255,255,0.12); color: white;
        border: none; border-radius: 50%;
        width: 48px; height: 48px; font-size: 1.4rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; z-index: 10001;
    }

    .modal-close:hover { background: rgba(255,255,255,0.22); transform: rotate(90deg); }

    /* Lightbox */
    .lightbox {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.96); z-index: 9999;
        align-items: center; justify-content: center;
    }

    .lightbox.active { display: flex; }

    .lightbox-inner { max-width: 90vw; max-height: 90vh; }

    .lightbox-inner img {
        max-width: 100%; max-height: 90vh; object-fit: contain; display: block; border-radius: 4px;
    }

    .lb-close {
        position: fixed; top: 20px; right: 26px; color: white; font-size: 2rem;
        cursor: pointer; background: rgba(255,255,255,0.1);
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; z-index: 10000; border: none;
    }

    .lb-close:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }

    .lb-nav {
        position: fixed; top: 50%; transform: translateY(-50%); color: white; font-size: 2rem;
        cursor: pointer; background: rgba(255,255,255,0.1);
        width: 54px; height: 54px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; user-select: none; z-index: 10000; border: none;
    }

    .lb-nav:hover { background: rgba(255,255,255,0.2); }
    .lb-prev { left: 20px; }
    .lb-next { right: 20px; }

    .lb-info {
        position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
        color: white; font-size: 0.9rem; background: rgba(0,0,0,0.65);
        padding: 8px 18px; border-radius: 20px; z-index: 10000;
        white-space: nowrap; max-width: 80vw; overflow: hidden; text-overflow: ellipsis;
    }

    .lb-thumbs {
        position: fixed; bottom: 60px; left: 50%; transform: translateX(-50%);
        display: flex; gap: 6px; z-index: 10000; max-width: 80vw; overflow-x: auto;
        padding: 6px 8px; background: rgba(0,0,0,0.5); border-radius: 10px;
    }

    .lb-thumb {
        width: 52px; height: 52px; border-radius: 6px; object-fit: cover;
        cursor: pointer; opacity: 0.55; transition: all 0.2s ease;
        border: 2px solid transparent; flex-shrink: 0;
    }

    .lb-thumb.active { opacity: 1; border-color: #D4AF37; }
    .lb-thumb:hover { opacity: 0.9; }

    @media (max-width: 768px) {
        .show-hero h1 { font-size: 2rem; }
        .photos-grid { grid-template-columns: repeat(2, 1fr); }
        .photo-item:first-child { grid-column: span 2; grid-row: span 1; aspect-ratio: 16/9; }
        .videos-grid { grid-template-columns: repeat(2, 1fr); }
        .videos-grid .video-grid-item:first-child { grid-column: span 2; grid-row: span 1; aspect-ratio: 16/9; }
        .show-section { padding: 3rem 1rem; }
        .lb-nav { width: 44px; height: 44px; font-size: 1.5rem; }
        .lb-prev { left: 8px; } .lb-next { right: 8px; }
        .lb-thumbs { max-width: 95vw; }
    }

    @media (max-width: 480px) {
        .photos-grid,
        .videos-grid { grid-template-columns: 1fr; }
        .photo-item:first-child,
        .videos-grid .video-grid-item:first-child { grid-column: span 1; aspect-ratio: 4/3; }
    }
</style>
@endpush

@section('content')
<section class="show-hero">
    <div>
        <h1>{{ $gallery->title }}</h1>
        @if($gallery->category)
        <p>{{ $gallery->category }}</p>
        @endif
    </div>
</section>

<section class="show-section">

    <a href="{{ route('gallery.public') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Gallery
    </a>

    @if(!empty($gallery->description))
    <div class="show-description">{{ $gallery->description }}</div>
    @endif

    {{-- ===== FOTO ===== --}}
    <div class="photo-count">{{ $totalPhotos }} {{ $totalPhotos == 1 ? 'Photo' : 'Photos' }}</div>

    <div class="photos-grid">
        @foreach($allPhotos as $index => $photo)
        <div class="photo-item" onclick="openLb({{ $index }})">
            <img src="{{ asset('storage/' . ImageHelper::thumb($photo)) }}"
                 alt="{{ $gallery->title }} - Photo {{ $index + 1 }}"
                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                 onerror="this.onerror=null; this.src='{{ asset('storage/' . $photo) }}';">
            <div class="zoom-icon">
                <i class="fas fa-expand"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== VIDEO (jika ada, tampilkan dengan grid yang sama) ===== --}}
    @if(!empty($gallery->videos) && count($gallery->videos) > 0)
    <h3 class="videos-section-title">Videos</h3>
    <div class="video-count">{{ count($gallery->videos) }} {{ count($gallery->videos) == 1 ? 'Video' : 'Videos' }}</div>

    <div class="videos-grid">
        @foreach($gallery->videos as $vidIndex => $video)
        <div class="video-grid-item"
             onclick="openVideoModal('{{ $video->youtube_embed_url ?? '' }}', '{{ addslashes($video->title ?? $gallery->title) }}')">

            @if(!empty($video->youtube_thumbnail))
            <img src="{{ $video->youtube_thumbnail }}"
                 alt="{{ $video->title ?? $gallery->title }}"
                 loading="{{ $vidIndex === 0 ? 'eager' : 'lazy' }}"
                 onerror="this.src='https://img.youtube.com/vi/{{ $video->youtube_id ?? '' }}/hqdefault.jpg'">
            @else
            <div style="width:100%;height:100%;background:#1a1a1a;display:flex;align-items:center;justify-content:center;">
                <i class="fab fa-youtube" style="font-size:3rem;color:#e74c3c;opacity:0.4;"></i>
            </div>
            @endif

            <div class="video-play-icon">
                <i class="fas fa-play" style="margin-left:3px;"></i>
            </div>

            <div class="video-grid-overlay">
                <div class="video-grid-content">
                    @if(!empty($video->category))
                    <div class="video-grid-category">{{ $video->category }}</div>
                    @endif
                    <div class="video-grid-title">{{ $video->title ?? $gallery->title }}</div>
                    @if(!empty($video->description))
                    <div class="video-grid-desc">{{ Str::limit($video->description, 100) }}</div>
                    @endif
                    <div class="video-grid-badge">
                        <i class="fab fa-youtube"></i> Watch Video
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</section>

{{-- Lightbox Foto --}}
<div id="lightbox" class="lightbox" onclick="closeLb(event)">
    <button class="lb-close" onclick="closeLb()"><i class="fas fa-times"></i></button>
    <button class="lb-nav lb-prev" onclick="event.stopPropagation(); changeLb(-1)">
        <i class="fas fa-chevron-left"></i>
    </button>
    <div class="lightbox-inner">
        <img id="lb-img" src="" alt="">
    </div>
    <button class="lb-nav lb-next" onclick="event.stopPropagation(); changeLb(1)">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="lb-info" id="lb-info"></div>
    <div class="lb-thumbs" id="lb-thumbs"></div>
</div>

{{-- Video Modal --}}
<div id="videoModal" class="video-modal" onclick="closeVideoModal(event)">
    <button class="modal-close" onclick="closeVideoModal()">
        <i class="fas fa-times"></i>
    </button>
    <div class="video-modal-inner">
        <iframe id="modalIframe"
            src=""
            allowfullscreen
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
        </iframe>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── LIGHTBOX FOTO ──
const pool = @json($allPhotosUrl);
const title = @json($gallery->title);
let curIdx = 0;

function openLb(idx) {
    curIdx = idx;
    renderLb();
    buildThumbs();
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function renderLb() {
    document.getElementById('lb-img').src = pool[curIdx];
    document.getElementById('lb-info').textContent = title + ` (${curIdx + 1}/${pool.length})`;
    document.querySelectorAll('.lb-thumb').forEach((t, i) => t.classList.toggle('active', i === curIdx));
    const multi = pool.length > 1;
    document.querySelector('.lb-prev').style.display = multi ? 'flex' : 'none';
    document.querySelector('.lb-next').style.display = multi ? 'flex' : 'none';
    document.getElementById('lb-thumbs').style.display = multi ? 'flex' : 'none';
}

function buildThumbs() {
    const container = document.getElementById('lb-thumbs');
    container.innerHTML = '';
    if (pool.length <= 1) return;
    pool.forEach((src, i) => {
        const img = document.createElement('img');
        img.src = src;
        img.className = 'lb-thumb' + (i === curIdx ? ' active' : '');
        img.onclick = e => { e.stopPropagation(); curIdx = i; renderLb(); };
        container.appendChild(img);
    });
}

function changeLb(dir) {
    curIdx = (curIdx + dir + pool.length) % pool.length;
    renderLb();
}

function closeLb(e) {
    if (!e || e.target.id === 'lightbox' || e.target.closest?.('.lb-close')) {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }
}

// ── VIDEO MODAL ──
function openVideoModal(embedUrl, title) {
    if (!embedUrl) return;
    const modal  = document.getElementById('videoModal');
    const iframe = document.getElementById('modalIframe');
    iframe.src = embedUrl + '&autoplay=1';
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeVideoModal(e) {
    if (e && e.target !== document.getElementById('videoModal') && !e.target.closest('.modal-close')) return;
    const modal  = document.getElementById('videoModal');
    const iframe = document.getElementById('modalIframe');
    iframe.src   = '';
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft')  { if (document.getElementById('lightbox').classList.contains('active')) changeLb(-1); }
    if (e.key === 'ArrowRight') { if (document.getElementById('lightbox').classList.contains('active')) changeLb(1); }
    if (e.key === 'Escape') {
        closeLb();
        closeVideoModal({ target: document.getElementById('videoModal') });
    }
});

let touchStartX = 0;
document.getElementById('lightbox').addEventListener('touchstart', e => {
    touchStartX = e.changedTouches[0].screenX;
}, { passive: true });
document.getElementById('lightbox').addEventListener('touchend', e => {
    const dx = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(dx) > 50) changeLb(dx < 0 ? 1 : -1);
});
</script>
@endpush