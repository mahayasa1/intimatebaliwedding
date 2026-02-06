@extends('layouts.admin')

@section('title', 'Photo Details')
@section('page-title', 'Photo Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Photo Display */
    .photo-display-section {
        margin-bottom: 2rem;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        background: white;
        padding: 2rem;
    }

    .photo-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        max-height: 600px;
    }

    .photo-display {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
    }

    .photo-actions-overlay {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        gap: 0.5rem;
    }

    .btn-overlay {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        color: #666;
        font-size: 1.1rem;
    }

    .btn-overlay:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn-overlay.download {
        color: #3498db;
    }

    .btn-overlay.fullscreen {
        color: #8B7355;
    }

    /* Detail Card */
    .detail-card {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 2rem;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-title-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        border-radius: 10px;
        font-size: 1.25rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .info-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.8rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.1rem;
        color: #1a1a1a;
        font-weight: 500;
        word-break: break-word;
    }

    .photo-title-display {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.3;
        margin-bottom: 1rem;
    }

    .description-text {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        white-space: pre-wrap;
    }

    /* Action Buttons */
    .action-section {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(149, 165, 166, 0.2);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    /* Fullscreen Modal */
    .fullscreen-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .fullscreen-modal.active {
        display: flex;
    }

    .fullscreen-modal img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 8px;
    }

    .fullscreen-close {
        position: absolute;
        top: 2rem;
        right: 2rem;
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .fullscreen-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-card {
            padding: 1.5rem;
        }

        .photo-title-display {
            font-size: 1.5rem;
        }

        .photo-wrapper {
            max-height: 400px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .photo-display-section {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    <!-- Photo Display -->
    <div class="photo-display-section">
        <div class="photo-wrapper">
            <img src="{{ asset('storage/' . $gallery->foto) }}" alt="{{ $gallery->title }}" class="photo-display" id="main-photo">
            <div class="photo-actions-overlay">
                <button class="btn-overlay download" id="download-btn" title="Download">
                    <i class="fa-solid fa-download"></i>
                </button>
                <button class="btn-overlay fullscreen" id="fullscreen-btn" title="View Fullscreen">
                    <i class="fa-solid fa-expand"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Photo Title -->
    <div class="detail-card">
        <h1 class="photo-title-display">
            <i class="fa-solid fa-image" style="color: #8B7355; font-size: 0.9em;"></i>
            {{ $gallery->title }}
        </h1>
        
        @if($gallery->description)
        <div class="description-text">{{ $gallery->description }}</div>
        @endif
    </div>

    <!-- Photo Information -->
    <div class="detail-card">
        <h3 class="card-title">
            <span class="card-title-icon">
                <i class="fa-solid fa-circle-info"></i>
            </span>
            Photo Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-hashtag"></i>
                    Photo ID
                </div>
                <div class="info-value">#{{ $gallery->id }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-calendar-plus"></i>
                    Uploaded On
                </div>
                <div class="info-value">{{ $gallery->created_at->format('F d, Y \a\t h:i A') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-calendar-check"></i>
                    Last Updated
                </div>
                <div class="info-value">{{ $gallery->updated_at->format('F d, Y \a\t h:i A') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-file"></i>
                    File Path
                </div>
                <div class="info-value" style="font-size: 0.85rem; color: #666; word-break: break-all;">
                    {{ $gallery->foto }}
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="action-section">
        <div class="action-buttons">
            <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Gallery
            </a>
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i> Edit Photo
            </a>
            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this photo? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> Delete Photo
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Fullscreen Modal -->
<div class="fullscreen-modal" id="fullscreen-modal">
    <button class="fullscreen-close" id="fullscreen-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <img src="{{ asset('storage/' . $gallery->foto) }}" alt="{{ $gallery->title }}">
</div>
@endsection

@push('scripts')
<script>
    // Download functionality
    document.getElementById('download-btn').addEventListener('click', function() {
        const link = document.createElement('a');
        link.href = '{{ asset('storage/' . $gallery->foto) }}';
        link.download = '{{ $gallery->title }}.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Fullscreen functionality
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const fullscreenModal = document.getElementById('fullscreen-modal');
    const fullscreenClose = document.getElementById('fullscreen-close');

    fullscreenBtn.addEventListener('click', function() {
        fullscreenModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    fullscreenClose.addEventListener('click', function() {
        fullscreenModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });

    fullscreenModal.addEventListener('click', function(e) {
        if (e.target === fullscreenModal) {
            fullscreenModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });

    // ESC key to close fullscreen
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && fullscreenModal.classList.contains('active')) {
            fullscreenModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
</script>
@endpush