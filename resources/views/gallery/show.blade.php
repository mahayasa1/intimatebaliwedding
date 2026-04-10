@extends('layouts.app')
@php use App\Helpers\ImageHelper; @endphp

@section('title', $gallery->title . ' - Intimate Bali Wedding')

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

    /* Grid foto */
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
        .show-section { padding: 3rem 1rem; }
        .lb-nav { width: 44px; height: 44px; font-size: 1.5rem; }
        .lb-prev { left: 8px; } .lb-next { right: 8px; }
        .lb-thumbs { max-width: 95vw; }
    }

    @media (max-width: 480px) {
        .photos-grid { grid-template-columns: 1fr; }
        .photo-item:first-child { grid-column: span 1; aspect-ratio: 4/3; }
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

</section>

<!-- Lightbox -->
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
@endsection

@push('scripts')
<script>
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

document.addEventListener('keydown', e => {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === 'ArrowLeft')  changeLb(-1);
    if (e.key === 'ArrowRight') changeLb(1);
    if (e.key === 'Escape')     closeLb();
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