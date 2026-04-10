@extends('layouts.admin')

@section('title', 'Add New Photo')
@section('page-title', 'Add New Photo to Gallery')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container { max-width: 900px; margin: 0 auto; }

    .form-card {
        background: white; padding: 2.5rem; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e8e8; margin-bottom: 2rem;
    }

    .section-title {
        font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: #1a1a1a;
        margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;
        padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0;
    }

    .section-icon {
        width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white; border-radius: 10px; font-size: 1.25rem;
    }

    .form-group { margin-bottom: 1.5rem; }

    .form-label {
        font-family: 'Work Sans', sans-serif; display: block; color: #333;
        font-weight: 600; margin-bottom: 0.75rem; font-size: 0.9rem; letter-spacing: 0.3px;
    }

    .form-label .required { color: #e74c3c; margin-left: 0.25rem; }

    .form-control {
        font-family: 'Work Sans', sans-serif; width: 100%; padding: 0.875rem 1.25rem;
        border: 2px solid #e0e0e0; border-radius: 12px; font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: white;
    }

    .form-control:focus {
        outline: none; border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1); transform: translateY(-2px);
    }

    .form-control.error { border-color: #e74c3c; background: #fff5f5; }

    textarea.form-control { min-height: 120px; resize: vertical; line-height: 1.6; }

    .error-message {
        font-family: 'Work Sans', sans-serif; color: #e74c3c; font-size: 0.85rem;
        margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;
    }

    .form-help {
        font-family: 'Work Sans', sans-serif; color: #999;
        font-size: 0.85rem; margin-top: 0.5rem; font-style: italic;
    }

    /* Upload Area */
    .image-upload-wrapper {
        position: relative; border: 2px dashed #e0e0e0; border-radius: 12px;
        padding: 2rem; text-align: center; transition: all 0.3s ease; background: #fafafa;
    }

    .image-upload-wrapper:hover { border-color: #8B7355; background: #f5f5f5; }
    .image-upload-wrapper.dragover {
        border-color: #8B7355; border-style: solid;
        background: rgba(139,115,85,0.05);
    }

    .image-upload-wrapper input[type="file"] {
        position: absolute; width: 100%; height: 100%; top: 0; left: 0;
        opacity: 0; cursor: pointer; z-index: 2;
    }

    .upload-icon { font-size: 3rem; color: #8B7355; opacity: 0.4; margin-bottom: 0.75rem; }
    .upload-text { color: #666; font-size: 0.95rem; font-family: 'Work Sans', sans-serif; }
    .upload-text strong { color: #8B7355; }

    /* Main preview */
    .main-preview-wrapper {
        display: none; margin-top: 1rem; position: relative;
        max-width: 400px; margin-left: auto; margin-right: auto;
    }

    .main-preview-wrapper img {
        width: 100%; height: auto; border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12); display: block;
    }

    .main-preview-wrapper.show { display: block; }

    .remove-main {
        position: absolute; top: 8px; right: 8px;
        background: rgba(231,76,60,0.9); color: white; border: none;
        border-radius: 50%; width: 32px; height: 32px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }

    .remove-main:hover { background: #e74c3c; transform: scale(1.1); }

    /* Photos grid preview */
    .photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 1rem; margin-top: 1rem;
    }

    .photo-preview-item {
        position: relative; aspect-ratio: 1; border-radius: 10px; overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .photo-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .remove-photo {
        position: absolute; top: 5px; right: 5px;
        background: rgba(231,76,60,0.9); color: white; border: none;
        border-radius: 50%; width: 28px; height: 28px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; transition: all 0.3s ease;
    }

    .remove-photo:hover { background: #e74c3c; transform: scale(1.1); }

    .photo-size-badge {
        position: absolute; bottom: 5px; left: 5px;
        background: rgba(0,0,0,0.6); color: white;
        font-size: 0.65rem; padding: 2px 6px; border-radius: 4px;
        font-family: 'Work Sans', sans-serif;
    }

    /* Compression indicator */
    .compress-info {
        display: flex; align-items: center; gap: 0.5rem;
        background: linear-gradient(135deg, #e8f4f8 0%, #d1ecf1 100%);
        border: 1px solid #90caf9; border-radius: 8px;
        padding: 0.75rem 1rem; margin-top: 0.75rem; font-size: 0.85rem;
        color: #0d47a1; font-family: 'Work Sans', sans-serif;
    }

    .compress-info i { color: #1565c0; }

    /* Progress */
    .upload-progress {
        display: none; margin-top: 1rem;
    }

    .progress-bar-wrap {
        background: #f0f0f0; border-radius: 8px; overflow: hidden; height: 8px;
    }

    .progress-bar {
        height: 100%; background: linear-gradient(90deg, #8B7355, #D4AF37);
        border-radius: 8px; transition: width 0.3s ease; width: 0%;
    }

    .progress-label {
        font-size: 0.8rem; color: #666; margin-top: 0.5rem; text-align: center;
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
        gap: 0.5rem; padding: 0.875rem 1.75rem; border-radius: 12px;
        text-decoration: none; font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none; cursor: pointer; font-size: 0.95rem;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        color: white; box-shadow: 0 2px 8px rgba(149, 165, 166, 0.2);
    }

    .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3); }

    .btn-primary {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white; box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    @media (max-width: 768px) {
        .form-card { padding: 1.5rem; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
        .photos-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" id="galleryForm">
        @csrf

        {{-- Photo Information --}}
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon"><i class="fa-solid fa-image"></i></span>
                Photo Information
            </h3>

            <div class="form-group">
                <label for="title" class="form-label">
                    Photo Title <span class="required">*</span>
                </label>
                <input type="text" id="title" name="title"
                    class="form-control @error('title') error @enderror"
                    value="{{ old('title') }}" required placeholder="e.g., Beach Wedding Ceremony">
                @error('title')
                <div class="error-message"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description"
                    class="form-control @error('description') error @enderror"
                    placeholder="Describe the photo...">{{ old('description') }}</textarea>
                @error('description')
                <div class="error-message"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <input type="text" id="category" name="category"
                    class="form-control @error('category') error @enderror"
                    value="{{ old('category') }}" placeholder="e.g., Hero, Beach, Garden">
                @error('category')
                <div class="error-message"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="order" class="form-label">Display Order</label>
                <input type="number" id="order" name="order"
                    class="form-control @error('order') error @enderror"
                    value="{{ old('order', 0) }}" min="0">
                @error('order')
                <div class="error-message"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</div>
                @enderror
                <div class="form-help">Lower number = shown first (0 = default)</div>
            </div>
        </div>

        {{-- Main Photo Upload --}}
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon"><i class="fa-solid fa-star"></i></span>
                Main Photo <span style="font-size:0.8rem;color:#999;font-weight:400;">(Cover / Grid)</span>
            </h3>

            <div class="form-group">
                <div class="image-upload-wrapper" id="mainUploadArea">
                    <input type="file" id="foto" name="foto"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        required onchange="handleMainImage(event)">
                    <div class="upload-icon"><i class="fa-solid fa-camera-retro"></i></div>
                    <div class="upload-text">
                        <strong>Click or drag & drop</strong><br>
                        <small>JPG, PNG, WEBP — maks 20MB — akan dikompres otomatis ke maks 1920px</small>
                    </div>
                </div>
                <div class="main-preview-wrapper" id="mainPreview">
                    <img src="" alt="Preview" id="mainPreviewImg">
                    <button type="button" class="remove-main" onclick="removeMainImage()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @error('foto')
                <div class="error-message"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Additional Photos --}}
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon"><i class="fa-solid fa-images"></i></span>
                Additional Photos
                <span style="font-size:0.8rem;color:#999;font-weight:400;">(Opsional — bisa banyak sekaligus)</span>
            </h3>

            <div class="compress-info">
                <i class="fa-solid fa-bolt"></i>
                Semua foto akan dikompres otomatis di browser sebelum diupload — resolusi maks 1920px, kualitas 85%.
                Tidak perlu resize manual.
            </div>

            <div class="form-group" style="margin-top:1.25rem;">
                <div class="image-upload-wrapper" id="photosUploadArea">
                    <input type="file" id="photos" name="photos[]"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        multiple onchange="handleAdditionalPhotos(event)">
                    <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <div class="upload-text">
                        <strong>Click atau drag & drop</strong> untuk upload banyak foto<br>
                        <small>JPG, PNG, WEBP — pilih sekaligus banyak file</small>
                    </div>
                </div>

                <div class="upload-progress" id="uploadProgress">
                    <div class="progress-bar-wrap">
                        <div class="progress-bar" id="progressBar"></div>
                    </div>
                    <div class="progress-label" id="progressLabel">Memproses...</div>
                </div>

                <div id="photosGrid" class="photos-grid" style="display:none;"></div>
                @error('photos.*')
                <div class="error-message"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Add to Gallery
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// ==================== COMPRESSION UTILS ====================

/**
 * Compress an image File to max maxWidth px, quality 0–1.
 * Returns a new File.
 */
async function compressImage(file, maxWidth = 1920, quality = 0.85) {
    return new Promise((resolve) => {
        const img = new Image();
        const url = URL.createObjectURL(file);

        img.onload = () => {
            URL.revokeObjectURL(url);

            // If already small enough, skip compression
            if (img.width <= maxWidth) {
                resolve(file);
                return;
            }

            const ratio  = maxWidth / img.width;
            const canvas = document.createElement('canvas');
            canvas.width  = maxWidth;
            canvas.height = Math.round(img.height * ratio);

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(
                (blob) => {
                    const compressed = new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), {
                        type: 'image/jpeg',
                        lastModified: Date.now(),
                    });
                    resolve(compressed);
                },
                'image/jpeg',
                quality
            );
        };

        img.onerror = () => { URL.revokeObjectURL(url); resolve(file); };
        img.src = url;
    });
}

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

// ==================== MAIN IMAGE ====================

let mainFileCompressed = null;

async function handleMainImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const compressed = await compressImage(file, 1920, 0.85);
    mainFileCompressed = compressed;

    // Inject compressed file back
    injectFileToInput('foto', [compressed]);

    // Preview
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('mainPreviewImg').src = e.target.result;
        document.getElementById('mainPreview').classList.add('show');
        document.getElementById('mainUploadArea').style.display = 'none';
    };
    reader.readAsDataURL(compressed);
}

function removeMainImage() {
    document.getElementById('foto').value = '';
    mainFileCompressed = null;
    document.getElementById('mainPreview').classList.remove('show');
    document.getElementById('mainPreviewImg').src = '';
    document.getElementById('mainUploadArea').style.display = 'block';
}

// ==================== ADDITIONAL PHOTOS ====================

let additionalFiles = [];

async function handleAdditionalPhotos(event) {
    const rawFiles = Array.from(event.target.files);
    if (!rawFiles.length) return;

    // Show progress
    const progressWrap = document.getElementById('uploadProgress');
    const progressBar  = document.getElementById('progressBar');
    const progressLabel = document.getElementById('progressLabel');
    progressWrap.style.display = 'block';
    progressBar.style.width = '0%';
    progressLabel.textContent = `Memproses 0 / ${rawFiles.length} foto...`;

    const newCompressed = [];
    for (let i = 0; i < rawFiles.length; i++) {
        const compressed = await compressImage(rawFiles[i], 1920, 0.85);
        newCompressed.push(compressed);

        const pct = Math.round(((i + 1) / rawFiles.length) * 100);
        progressBar.style.width = pct + '%';
        progressLabel.textContent = `Memproses ${i + 1} / ${rawFiles.length} foto...`;
    }

    additionalFiles = [...additionalFiles, ...newCompressed];
    progressWrap.style.display = 'none';

    // Inject to input
    injectFileToInput('photos', additionalFiles);
    renderPhotoGrid();
}

function removeAdditionalPhoto(index) {
    additionalFiles.splice(index, 1);
    injectFileToInput('photos', additionalFiles);
    renderPhotoGrid();
}

function renderPhotoGrid() {
    const grid = document.getElementById('photosGrid');
    grid.innerHTML = '';

    if (additionalFiles.length === 0) {
        grid.style.display = 'none';
        return;
    }

    grid.style.display = 'grid';

    additionalFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'photo-preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Photo ${index + 1}">
                <button type="button" class="remove-photo" onclick="removeAdditionalPhoto(${index})">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <span class="photo-size-badge">${formatBytes(file.size)}</span>
            `;
            grid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// ==================== INJECT FILES HELPER ====================

function injectFileToInput(inputId, files) {
    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    document.getElementById(inputId).files = dt.files;
}

// ==================== DRAG & DROP ====================

function setupDragDrop(areaId, inputId, multi = false) {
    const area = document.getElementById(areaId);
    if (!area) return;

    area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('dragover'); });
    area.addEventListener('dragleave', () => area.classList.remove('dragover'));
    area.addEventListener('drop', e => {
        e.preventDefault();
        area.classList.remove('dragover');
        const input = document.getElementById(inputId);
        const files  = e.dataTransfer.files;
        if (!files.length) return;

        // Simulate change event
        const dt = new DataTransfer();
        Array.from(files).forEach(f => dt.items.add(f));
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    });
}

setupDragDrop('mainUploadArea', 'foto', false);
setupDragDrop('photosUploadArea', 'photos', true);

// ==================== SUBMIT GUARD ====================

document.getElementById('galleryForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading...';
});
</script>
@endpush