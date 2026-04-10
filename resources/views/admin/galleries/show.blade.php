@extends('layouts.admin')

@section('title', 'Photo Details')
@section('page-title', 'Photo Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .detail-container { max-width: 1200px; margin: 0 auto; }

    /* Main photo */
    .main-photo-section {
        margin-bottom: 2rem; border-radius: 16px; overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12); background: white; padding: 2rem;
    }

    .main-photo-wrapper {
        position: relative; border-radius: 12px; overflow: hidden;
        background: #f5f5f5; cursor: pointer;
        max-height: 550px; display: flex; align-items: center; justify-content: center;
    }

    .main-photo-wrapper img {
        width: 100%; height: auto; max-height: 550px;
        object-fit: contain; display: block; transition: transform 0.3s ease;
    }

    .main-photo-wrapper:hover img { transform: scale(1.02); }

    .photo-zoom-hint {
        position: absolute; bottom: 12px; right: 12px;
        background: rgba(0,0,0,0.6); color: white;
        font-size: 0.78rem; padding: 5px 10px; border-radius: 6px;
        display: flex; align-items: center; gap: 5px;
        font-family: 'Work Sans', sans-serif; opacity: 0;
        transition: opacity 0.3s ease;
    }

    .main-photo-wrapper:hover .photo-zoom-hint { opacity: 1; }

    /* Additional photos */
    .additional-photos-section {
        background: white; padding: 2.5rem; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e8e8; margin-bottom: 2rem;
    }

    .card-title {
        font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700;
        color: #1a1a1a; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;
        padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0;
    }

    .card-title-icon {
        width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white; border-radius: 10px; font-size: 1.1rem;
    }

    .photos-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .photo-grid-item {
        position: relative; aspect-ratio: 1; border-radius: 10px; overflow: hidden;
        cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .photo-grid-item:hover {
        transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .photo-grid-item img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.3s ease;
    }

    .photo-grid-item:hover img { transform: scale(1.08); }

    .photo-grid-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,0);
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; color: white; font-size: 1.5rem;
    }

    .photo-grid-item:hover .photo-grid-overlay { background: rgba(0,0,0,0.35); }

    /* Detail Card */
    .detail-card {
        background: white; padding: 2rem; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e8e8; margin-bottom: 2rem;
    }

    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; }

    .info-item {
        padding: 1.15rem; background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 12px; border: 1px solid #e8e8e8; transition: all 0.3s ease;
    }

    .info-item:hover { transform: translateX(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.06); }

    .info-label {
        font-family: 'Work Sans', sans-serif; font-size: 0.78rem; color: #999;
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.6rem; font-weight: 600;
    }

    .info-value {
        font-family: 'Work Sans', sans-serif; font-size: 1rem; color: #1a1a1a; font-weight: 500;
    }

    /* Lightbox */
    .lightbox {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.96); z-index: 9999;
        align-items: center; justify-content: center;
    }

    .lightbox.active { display: flex; }

    .lightbox-content { max-width: 90vw; max-height: 90vh; position: relative; }

    .lightbox-content img {
        max-width: 100%; max-height: 90vh; object-fit: contain; border-radius: 4px; display: block;
    }

    .lb-close {
        position: fixed; top: 20px; right: 28px; color: white;
        font-size: 1.75rem; cursor: pointer; background: rgba(255,255,255,0.1);
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; z-index: 10001; border: none;
    }

    .lb-close:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }

    .lb-nav {
        position: fixed; top: 50%; transform: translateY(-50%); color: white;
        font-size: 1.75rem; cursor: pointer; background: rgba(255,255,255,0.1);
        width: 52px; height: 52px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; user-select: none; z-index: 10001; border: none;
    }

    .lb-nav:hover { background: rgba(255,255,255,0.22); }
    .lb-prev { left: 20px; }
    .lb-next { right: 20px; }

    .lb-counter {
        position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
        color: white; font-size: 0.88rem; background: rgba(0,0,0,0.65);
        padding: 8px 18px; border-radius: 20px; z-index: 10001;
        font-family: 'Work Sans', sans-serif;
    }

    /* Action Buttons */
    .action-section {
        background: white; padding: 2rem; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e8e8;
    }

    .action-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }

    .btn {
        font-family: 'Work Sans', sans-serif; display: inline-flex; align-items: center;
        gap: 0.5rem; padding: 0.875rem 1.75rem; border-radius: 12px; text-decoration: none;
        font-weight: 600; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none; cursor: pointer; font-size: 0.95rem;
    }

    .btn-secondary { background: linear-gradient(135deg, #95a5a6, #7f8c8d); color: white; }
    .btn-secondary:hover { transform: translateY(-2px); }
    .btn-primary { background: linear-gradient(135deg, #8B7355, #6B5644); color: white; }
    .btn-primary:hover { transform: translateY(-2px); }
    .btn-danger { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
    .btn-danger:hover { transform: translateY(-2px); }

    @media (max-width: 768px) {
        .detail-card, .main-photo-section, .additional-photos-section { padding: 1.5rem; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
        .photos-grid { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); }
        .lb-nav { width: 42px; height: 42px; font-size: 1.4rem; }
        .lb-prev { left: 8px; } .lb-next { right: 8px; }
    }
</style>
@endpush

@section('content')
<div class="detail-container">

    {{-- Main Photo --}}
    <div class="main-photo-section">
        <h3 class="card-title">
            <span class="card-title-icon"><i class="fa-solid fa-star"></i></span>
            Main Photo
        </h3>

        @if($gallery->image)
        <div class="main-photo-wrapper" onclick="openLightbox('main', 0)">
            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}"
                 onerror="this.onerror=null; this.src='{{ asset('assets/placeholder.jpg') }}';">
            <div class="photo-zoom-hint">
                <i class="fa-solid fa-expand"></i> Click to expand
            </div>
        </div>
        @else
        <div style="height:200px;background:#f5f5f5;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#ccc;font-size:3rem;">
            <i class="fa-solid fa-image"></i>
        </div>
        @endif
    </div>

    {{-- Additional Photos --}}
    @php $photos = is_array($gallery->photo) ? $gallery->photo : []; @endphp
    @if(count($photos) > 0)
    <div class="additional-photos-section">
        <h3 class="card-title">
            <span class="card-title-icon"><i class="fa-solid fa-images"></i></span>
            Additional Photos
            <span style="font-size:0.85rem;color:#999;font-weight:400;margin-left:0.5rem;">
                ({{ count($photos) }} foto)
            </span>
        </h3>

        <div class="photos-grid">
            @foreach($photos as $index => $photoPath)
            <div class="photo-grid-item" onclick="openLightbox('additional', {{ $index }})">
                <img src="{{ asset('storage/' . $photoPath) }}" alt="Photo {{ $index + 1 }}"
                     loading="lazy"
                     onerror="this.onerror=null; this.parentElement.style.background='#f5f5f5';">
                <div class="photo-grid-overlay">
                    <i class="fa-solid fa-search-plus"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Info --}}
    <div class="detail-card">
        <h3 class="card-title">
            <span class="card-title-icon"><i class="fa-solid fa-circle-info"></i></span>
            Photo Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Title</div>
                <div class="info-value">{{ $gallery->title }}</div>
            </div>

            @if($gallery->description)
            <div class="info-item">
                <div class="info-label">Description</div>
                <div class="info-value" style="font-size:0.92rem;line-height:1.5;color:#555;">
                    {{ $gallery->description }}
                </div>
            </div>
            @endif

            @if($gallery->category)
            <div class="info-item">
                <div class="info-label">Category</div>
                <div class="info-value">{{ $gallery->category }}</div>
            </div>
            @endif

            <div class="info-item">
                <div class="info-label">Display Order</div>
                <div class="info-value">{{ $gallery->order ?? 0 }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Additional Photos</div>
                <div class="info-value">{{ count($photos) }} foto</div>
            </div>

            <div class="info-item">
                <div class="info-label">Uploaded On</div>
                <div class="info-value">{{ $gallery->created_at->format('d F Y, H:i') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $gallery->updated_at->format('d F Y, H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="action-section">
        <div class="action-buttons">
            <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Gallery
            </a>
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i> Edit Photo
            </a>
            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST"
                style="margin:0;" onsubmit="return confirm('Hapus foto ini? Tindakan ini tidak bisa dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <button class="lb-close" onclick="closeLightbox()">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <button class="lb-nav lb-prev" onclick="event.stopPropagation(); changeLb(-1)">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <div class="lightbox-content">
        <img id="lb-img" src="" alt="">
    </div>
    <button class="lb-nav lb-next" onclick="event.stopPropagation(); changeLb(1)">
        <i class="fa-solid fa-chevron-right"></i>
    </button>
    <div class="lb-counter">
        <span id="lb-cur">1</span> / <span id="lb-total">1</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Build image pool
const mainPhoto      = @json($gallery->image ? asset('storage/' . $gallery->image) : null);
const additionalPhotos = @json(array_map(fn($p) => asset('storage/' . $p), $photos));

let pool = [];
let currentIdx = 0;

function openLightbox(source, index) {
    if (source === 'main') {
        pool = mainPhoto ? [mainPhoto, ...additionalPhotos] : additionalPhotos;
        currentIdx = 0;
    } else {
        // additional only
        pool = additionalPhotos;
        currentIdx = index;
    }

    if (!pool.length) return;

    document.getElementById('lb-img').src = pool[currentIdx];
    document.getElementById('lb-cur').textContent = currentIdx + 1;
    document.getElementById('lb-total').textContent = pool.length;

    // Show/hide nav arrows
    const showNav = pool.length > 1;
    document.querySelector('.lb-prev').style.display = showNav ? 'flex' : 'none';
    document.querySelector('.lb-next').style.display = showNav ? 'flex' : 'none';

    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(e) {
    if (!e || e.target.id === 'lightbox' || e.target.closest?.('.lb-close')) {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }
}

function changeLb(dir) {
    currentIdx = (currentIdx + dir + pool.length) % pool.length;
    document.getElementById('lb-img').src = pool[currentIdx];
    document.getElementById('lb-cur').textContent = currentIdx + 1;
}

document.addEventListener('keydown', e => {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === 'ArrowLeft') changeLb(-1);
    if (e.key === 'ArrowRight') changeLb(1);
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endpush