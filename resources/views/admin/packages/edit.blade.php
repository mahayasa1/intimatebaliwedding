@extends('layouts.admin')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container { max-width: 900px; margin: 0 auto; }

    .form-card {
        background: white; padding: 2.5rem;
        border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8; margin-bottom: 2rem;
    }

    .section-title {
        font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700;
        color: #1a1a1a; margin-bottom: 1.5rem; display: flex; align-items: center;
        gap: 0.75rem; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0;
    }

    .form-group { margin-bottom: 1.5rem; }

    .form-label {
        font-family: 'Work Sans', sans-serif; display: block; color: #333;
        font-weight: 600; margin-bottom: 0.75rem; font-size: 0.9rem; letter-spacing: 0.3px;
    }

    .form-label .required { color: #e74c3c; margin-left: 0.25rem; }

    .form-control {
        font-family: 'Work Sans', sans-serif; width: 100%;
        padding: 0.875rem 1.25rem; border: 2px solid #e0e0e0; border-radius: 12px;
        font-size: 0.95rem; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white; box-sizing: border-box;
    }

    .form-control:focus {
        outline: none; border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1); transform: translateY(-2px);
    }

    textarea.form-control { min-height: 140px; resize: vertical; line-height: 1.6; }

    .current-image { margin-bottom: 1rem; position: relative; display: inline-block; }
    .current-image img { max-width: 300px; max-height: 200px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .current-image-label { display: block; font-size: 0.85rem; color: #666; margin-bottom: 0.5rem; }

    .image-upload-wrapper {
        position: relative; border: 2px dashed #e0e0e0; border-radius: 12px;
        padding: 2rem; text-align: center; transition: all 0.3s ease; background: #fafafa;
    }

    .image-upload-wrapper:hover { border-color: #8B7355; background: #f5f5f5; }

    .image-upload-wrapper input[type="file"] {
        position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer;
    }

    .upload-icon { font-size: 3rem; color: #8B7355; margin-bottom: 1rem; }
    .upload-text { color: #666; font-size: 0.95rem; }
    .upload-text strong { color: #8B7355; }

    .existing-photos-grid, .photos-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem; margin-top: 1rem;
    }

    .existing-photo-item, .photo-preview-item {
        position: relative; aspect-ratio: 1; border-radius: 8px;
        overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .existing-photo-item img, .photo-preview-item img { width: 100%; height: 100%; object-fit: cover; }

    .remove-existing-photo, .remove-photo {
        position: absolute; top: 5px; right: 5px; background: #e74c3c; color: white;
        border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; transition: all 0.3s ease; z-index: 10;
    }

    .remove-existing-photo:hover, .remove-photo:hover { background: #c0392b; transform: scale(1.1); }

    .form-help {
        font-family: 'Work Sans', sans-serif; color: #999;
        font-size: 0.85rem; margin-top: 0.5rem; font-style: italic;
    }

    /* Subpackage Styles */
    .subpackage-item {
        background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 12px;
        padding: 1.5rem; margin-bottom: 1rem; transition: all 0.3s ease;
    }

    .subpackage-item:hover { border-color: #8B7355; }

    .subpackage-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;
    }

    .subpackage-number {
        font-family: 'Work Sans', sans-serif; font-weight: 700; color: #8B7355;
        font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;
    }

    .btn-remove-sub {
        background: #e74c3c; color: white; border: none; border-radius: 8px;
        padding: 0.4rem 0.8rem; cursor: pointer; font-size: 0.8rem;
        font-family: 'Work Sans', sans-serif; transition: all 0.3s ease;
    }

    .btn-remove-sub:hover { background: #c0392b; transform: scale(1.05); }

    .subpackage-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .subpackage-grid .full-width { grid-column: 1 / -1; }

    .btn-add-sub {
        font-family: 'Work Sans', sans-serif; display: inline-flex; align-items: center;
        gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; color: #8B7355;
        border: 2px dashed #8B7355; border-radius: 12px; cursor: pointer;
        font-weight: 600; font-size: 0.9rem; transition: all 0.3s ease;
        width: 100%; justify-content: center; margin-top: 0.5rem;
    }

    .btn-add-sub:hover { background: #f5f0eb; }

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

    .btn-secondary {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white;
        box-shadow: 0 2px 8px rgba(149, 165, 166, 0.2);
    }

    .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3); }

    .btn-primary {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%); color: white;
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3); }

    @media (max-width: 768px) {
        .form-card { padding: 1.5rem; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
        .existing-photos-grid, .photos-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
        .subpackage-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="removed_photos" id="removed_photos" value="">

        <!-- Package Information -->
        <div class="form-card">
            <h3 class="section-title">Package Information</h3>
            
            <div class="form-group">
                <label for="name" class="form-label">Package Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $package->name) }}" required>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Main Package Image</label>
                @if($package->image)
                <div class="current-image">
                    <span class="current-image-label">Current Image:</span>
                    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}">
                </div>
                @endif
                <div class="image-upload-wrapper">
                    <input type="file" id="image" name="image" accept="image/*">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>PNG, JPG, WEBP (max. 20MB) - Leave empty to keep current image</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control">{{ old('description', $package->description) }}</textarea>
            </div>
        </div>

        <!-- Subpackages -->
        <div class="form-card">
            <h3 class="section-title">
                <i class="fas fa-list-ul" style="color:#8B7355;font-size:1.2rem;"></i>
                Sub-packages / Options
            </h3>
            <div class="form-help" style="margin-bottom:1.5rem;">Tambahkan pilihan atau tier yang tersedia dalam package ini (opsional)</div>

            <div id="subpackageList">
                {{-- Populated by JS with existing subpackages --}}
            </div>

            <button type="button" class="btn-add-sub" onclick="addSubpackage()">
                <i class="fas fa-plus"></i> Add Sub-package
            </button>
        </div>

        <!-- Gallery Photos -->
        <div class="form-card">
            <h3 class="section-title">Package Gallery Photos</h3>

            @if($package->photo && count($package->photo) > 0)
            <div class="form-group">
                <label class="form-label">Existing Photos</label>
                <div class="existing-photos-grid" id="existingPhotosGrid">
                    @foreach($package->photo as $index => $photoPath)
                    <div class="existing-photo-item" data-photo="{{ $photoPath }}">
                        <img src="{{ asset('storage/' . $photoPath) }}" alt="Photo {{ $index + 1 }}">
                        <button type="button" class="remove-existing-photo" onclick="removeExistingPhoto('{{ $photoPath }}', this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <div class="form-help">Click × to remove photos</div>
            </div>
            @endif

            <div class="form-group">
                <label for="photos" class="form-label">Add New Photos</label>
                <div class="image-upload-wrapper">
                    <input type="file" id="photos" name="photos[]" accept="image/*" multiple onchange="previewMultipleImages(event)">
                    <div class="upload-icon"><i class="fas fa-images"></i></div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>PNG, JPG, WEBP (max. 20MB each) - Multiple files allowed</small>
                    </div>
                </div>
            </div>

            <div id="photosGrid" class="photos-grid" style="display: none;"></div>
        </div>

        <!-- Actions -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">← Cancel</a>
                <button type="submit" class="btn btn-primary">Update Package</button>
            </div>
        </div>
    </form>
</div>

<script>
// ==================== SUBPACKAGES ====================
let subIndex = 0;

function addSubpackage(data = null) {
    const list = document.getElementById('subpackageList');
    const idx = subIndex++;
    const name = data ? (data.name || '') : '';
    const desc = data ? (data.description || '') : '';
    const existingImage = data ? (data.image || '') : '';
    const existingPhotos = data ? (data.photo || []) : [];

    const existingImgHtml = existingImage
        ? `<div style="margin-bottom:0.75rem;">
               <span style="font-size:0.8rem;color:#666;">Current image:</span><br>
               <img src="/storage/${existingImage}" style="max-width:180px;max-height:130px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);margin-top:4px;">
               <input type="hidden" name="subpackages[${idx}][existing_image]" value="${existingImage}">
           </div>`
        : '';

    const existingPhotosHtml = existingPhotos.length
        ? `<div style="margin-bottom:0.75rem;">
               <span style="font-size:0.8rem;color:#666;">Current photos:</span>
               <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:6px;">
                   ${existingPhotos.map(p => `
                       <img src="/storage/${p}" style="width:80px;height:80px;object-fit:cover;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,.1);">
                       <input type="hidden" name="subpackages[${idx}][existing_photos][]" value="${p}">
                   `).join('')}
               </div>
           </div>`
        : '';

    const div = document.createElement('div');
    div.className = 'subpackage-item';
    div.id = `sub_${idx}`;
    div.innerHTML = `
        <div class="subpackage-header">
            <span class="subpackage-number">Sub-package #${list.children.length + 1}</span>
            <button type="button" class="btn-remove-sub" onclick="removeSubpackage('sub_${idx}')">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>
        <div class="subpackage-grid">
            <div class="form-group full-width">
                <label class="form-label">Name <span class="required">*</span></label>
                <input type="text" name="subpackages[${idx}][name]" class="form-control"
                    value="${name}" placeholder="e.g., Basic, Standard, Premium">
            </div>
            <div class="form-group full-width">
                <label class="form-label">Description</label>
                <textarea name="subpackages[${idx}][description]" class="form-control"
                    style="min-height:100px;" placeholder="Describe what's included...">${desc}</textarea>
            </div>
            <div class="form-group full-width">
                <label class="form-label">Main Image</label>
                ${existingImgHtml}
                <div class="image-upload-wrapper" style="padding:1.25rem;">
                    <input type="file" name="subpackages[${idx}][image]" accept="image/*"
                        onchange="previewSubImage(event, 'sub_img_preview_${idx}')">
                    <div style="font-size:1.5rem;color:#8B7355;margin-bottom:0.5rem;"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong>${existingImage ? ' to replace' : ''}<br><small>PNG, JPG, WEBP (max. 20MB)</small></div>
                </div>
                <div id="sub_img_preview_${idx}" style="display:none;margin-top:0.75rem;">
                    <img style="max-width:200px;max-height:150px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);" src="" alt="preview">
                </div>
            </div>
            <div class="form-group full-width">
                <label class="form-label">Additional Photos</label>
                ${existingPhotosHtml}
                <div class="image-upload-wrapper" style="padding:1.25rem;">
                    <input type="file" name="subpackages[${idx}][photos][]" accept="image/*" multiple
                        onchange="previewSubPhotos(event, 'sub_photos_grid_${idx}')">
                    <div style="font-size:1.5rem;color:#8B7355;margin-bottom:0.5rem;"><i class="fas fa-images"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> additional photos<br><small>PNG, JPG, WEBP (max. 20MB each)</small></div>
                </div>
                <div id="sub_photos_grid_${idx}" class="photos-grid" style="display:none;margin-top:0.75rem;"></div>
            </div>
        </div>
    `;
    list.appendChild(div);
    renumberSubpackages();
}

function removeSubpackage(id) {
    document.getElementById(id)?.remove();
    renumberSubpackages();
}

function renumberSubpackages() {
    document.querySelectorAll('#subpackageList .subpackage-number').forEach((el, i) => {
        el.textContent = `Sub-package #${i + 1}`;
    });
}

// Load existing subpackages from DB
const existingSubpackages = @json($package->subpackages);
existingSubpackages.forEach(sub => addSubpackage(sub));

// ==================== SUBPACKAGE IMAGE PREVIEW ====================
function previewSubImage(event, previewId) {
    const file = event.target.files[0];
    if (!file) return;
    const container = document.getElementById(previewId);
    const reader = new FileReader();
    reader.onload = e => {
        container.style.display = 'block';
        container.querySelector('img').src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function previewSubPhotos(event, gridId) {
    const files = Array.from(event.target.files);
    const grid = document.getElementById(gridId);
    grid.innerHTML = '';
    if (files.length === 0) { grid.style.display = 'none'; return; }
    grid.style.display = 'grid';
    files.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'photo-preview-item';
            div.innerHTML = `<img src="${e.target.result}" alt="Photo ${i + 1}" style="width:100%;height:100%;object-fit:cover;">`;
            grid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// ==================== EXISTING PHOTOS ====================
let removedPhotos = [];

function removeExistingPhoto(photoPath, button) {
    if (confirm('Are you sure you want to remove this photo?')) {
        removedPhotos.push(photoPath);
        document.getElementById('removed_photos').value = JSON.stringify(removedPhotos);
        button.closest('.existing-photo-item').remove();
        const grid = document.getElementById('existingPhotosGrid');
        if (grid && grid.children.length === 0) {
            grid.parentElement.remove();
        }
    }
}

// ==================== NEW PHOTOS ====================
let photoFiles = [];

function previewMultipleImages(event) {
    const files = Array.from(event.target.files);
    photoFiles = [...photoFiles, ...files];
    displayPhotos();
}

function displayPhotos() {
    const grid = document.getElementById('photosGrid');
    grid.innerHTML = '';
    if (photoFiles.length > 0) {
        grid.style.display = 'grid';
        photoFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'photo-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Photo ${index + 1}">
                    <button type="button" class="remove-photo" onclick="removePhoto(${index})">
                        <i class="fas fa-times"></i>
                    </button>`;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } else {
        grid.style.display = 'none';
    }
}

function removePhoto(index) {
    photoFiles.splice(index, 1);
    const dt = new DataTransfer();
    photoFiles.forEach(f => dt.items.add(f));
    document.getElementById('photos').files = dt.files;
    displayPhotos();
}
</script>
@endsection