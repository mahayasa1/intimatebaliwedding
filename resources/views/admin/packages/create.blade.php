@extends('layouts.admin')

@section('title', 'Add New Package')
@section('page-title', 'Add New Package')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container { max-width: 900px; margin: 0 auto; }

    .form-card {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 2rem;
    }

    .section-title {
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

    .form-group { margin-bottom: 1.5rem; }

    .form-label {
        font-family: 'Work Sans', sans-serif;
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
    }

    .form-label .required { color: #e74c3c; margin-left: 0.25rem; }

    .form-control {
        font-family: 'Work Sans', sans-serif;
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
        transform: translateY(-2px);
    }

    .form-control.error { border-color: #e74c3c; background: #fff5f5; }
    textarea.form-control { min-height: 140px; resize: vertical; line-height: 1.6; }

    /* Category Tags Input */
    .category-input-wrapper {
        position: relative;
    }

    .category-suggestions {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        z-index: 100;
        display: none;
        max-height: 200px;
        overflow-y: auto;
    }

    .category-suggestions.show { display: block; }

    .category-suggestion-item {
        padding: 0.75rem 1.25rem;
        cursor: pointer;
        font-family: 'Work Sans', sans-serif;
        font-size: 0.9rem;
        color: #333;
        transition: background 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .category-suggestion-item:hover { background: #f5f0eb; color: #8B7355; }
    .category-suggestion-item i { color: #D4AF37; font-size: 0.75rem; }

    .category-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .category-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        background: linear-gradient(135deg, rgba(139,115,85,0.12), rgba(107,86,68,0.12));
        color: #8B7355;
        border: 1px solid rgba(139,115,85,0.25);
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Work Sans', sans-serif;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .category-tag:hover { background: #8B7355; color: white; border-color: #8B7355; }

    .form-help {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        font-style: italic;
    }

    .image-upload-wrapper {
        position: relative;
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .image-upload-wrapper:hover { border-color: #8B7355; background: #f5f5f5; }

    .image-upload-wrapper input[type="file"] {
        position: absolute; width: 100%; height: 100%;
        top: 0; left: 0; opacity: 0; cursor: pointer;
    }

    .upload-icon { font-size: 3rem; color: #8B7355; margin-bottom: 1rem; }
    .upload-text { color: #666; font-size: 0.95rem; }
    .upload-text strong { color: #8B7355; }

    .image-preview { margin-top: 1rem; display: none; position: relative; }
    .image-preview img { max-width: 100%; max-height: 300px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    .remove-image {
        position: absolute; top: 10px; right: 10px;
        background: #e74c3c; color: white; border: none; border-radius: 50%;
        width: 32px; height: 32px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }

    .remove-image:hover { background: #c0392b; transform: scale(1.1); }

    .photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem; margin-top: 1rem;
    }

    .photo-preview-item {
        position: relative; aspect-ratio: 1;
        border-radius: 8px; overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .photo-preview-item img { width: 100%; height: 100%; object-fit: cover; }

    .remove-photo {
        position: absolute; top: 5px; right: 5px;
        background: #e74c3c; color: white; border: none; border-radius: 50%;
        width: 28px; height: 28px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; transition: all 0.3s ease;
    }

    .remove-photo:hover { background: #c0392b; transform: scale(1.1); }

    .error-message {
        font-family: 'Work Sans', sans-serif;
        color: #e74c3c; font-size: 0.85rem; margin-top: 0.5rem;
        display: flex; align-items: center; gap: 0.5rem;
    }

    /* Subpackage Styles */
    .subpackage-item {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .subpackage-item:hover { border-color: #8B7355; }

    .subpackage-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .subpackage-number {
        font-family: 'Work Sans', sans-serif;
        font-weight: 700;
        color: #8B7355;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-remove-sub {
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        cursor: pointer;
        font-size: 0.8rem;
        font-family: 'Work Sans', sans-serif;
        transition: all 0.3s ease;
    }

    .btn-remove-sub:hover { background: #c0392b; transform: scale(1.05); }

    .subpackage-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .subpackage-grid .full-width { grid-column: 1 / -1; }

    .btn-add-sub {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: white;
        color: #8B7355;
        border: 2px dashed #8B7355;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
        margin-top: 0.5rem;
    }

    .btn-add-sub:hover { background: #f5f0eb; }

    /* Action Buttons */
    .action-section {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .action-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }

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

    .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3); }

    .btn-primary {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3); }

    @media (max-width: 768px) {
        .form-card { padding: 1.5rem; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
        .photos-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
        .subpackage-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Package Information -->
        <div class="form-card">
            <h3 class="section-title">Package Information</h3>
            
            <div class="form-group">
                <label for="name" class="form-label">Package Name <span class="required">*</span></label>
                <input type="text" id="name" name="name"
                    class="form-control @error('name') error @enderror"
                    value="{{ old('name') }}" required
                    placeholder="e.g., Beach Wedding Package">
                @error('name')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Category Field -->
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <div class="category-input-wrapper">
                    <input
                        type="text"
                        id="category"
                        name="category"
                        class="form-control @error('category') error @enderror"
                        value="{{ old('category') }}"
                        placeholder="e.g., Intimate, Garden, Beach, Chapel"
                        autocomplete="off"
                    >
                    @if($categories->count() > 0)
                    <div class="category-suggestions" id="categorySuggestions">
                        @foreach($categories as $cat)
                        <div class="category-suggestion-item" onclick="selectCategory('{{ $cat }}')">
                            <i class="fas fa-tag"></i> {{ $cat }}
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @if($categories->count() > 0)
                <div class="category-tags">
                    @foreach($categories as $cat)
                    <span class="category-tag" onclick="selectCategory('{{ $cat }}')">
                        <i class="fas fa-tag"></i> {{ $cat }}
                    </span>
                    @endforeach
                </div>
                @endif
                @error('category')
                <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">Kategori untuk memfilter paket di halaman depan</div>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Main Package Image <span class="required">*</span></label>
                <div class="image-upload-wrapper">
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>PNG, JPG, WEBP (max. 20MB)</small>
                    </div>
                </div>
                <div class="image-preview" id="imagePreview">
                    <button type="button" class="remove-image" onclick="removeImage()"><i class="fas fa-times"></i></button>
                    <img id="preview" src="" alt="Preview">
                </div>
                @error('image')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description"
                    class="form-control @error('description') error @enderror"
                    placeholder="Describe the package and what it includes...">{{ old('description') }}</textarea>
                @error('description')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Subpackages -->
        <div class="form-card">
            <h3 class="section-title">
                <i class="fas fa-list-ul" style="color:#8B7355;font-size:1.2rem;"></i>
                Sub-packages / Options
            </h3>
            <div class="form-help" style="margin-bottom:1.5rem;">Tambahkan pilihan atau tier yang tersedia dalam package ini (opsional)</div>

            <div id="subpackageList"></div>

            <button type="button" class="btn-add-sub" onclick="addSubpackage()">
                <i class="fas fa-plus"></i> Add Sub-package
            </button>
        </div>

        <!-- Gallery Photos -->
        <div class="form-card">
            <h3 class="section-title">Package Gallery Photos</h3>

            <div class="form-group">
                <label for="photos" class="form-label">Additional Photos</label>
                <div class="image-upload-wrapper">
                    <input type="file" id="photos" name="photos[]" accept="image/*" multiple onchange="previewMultipleImages(event)">
                    <div class="upload-icon"><i class="fas fa-images"></i></div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>PNG, JPG, WEBP (max. 20MB each) - Multiple files allowed</small>
                    </div>
                </div>
                @error('photos.*')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div id="photosGrid" class="photos-grid" style="display: none;"></div>
        </div>

        <!-- Actions -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">← Cancel</a>
                <button type="submit" class="btn btn-primary">Create Package</button>
            </div>
        </div>
    </form>
</div>

<script>
// ==================== CATEGORY ====================
function selectCategory(cat) {
    document.getElementById('category').value = cat;
    document.getElementById('categorySuggestions')?.classList.remove('show');
}

const categoryInput = document.getElementById('category');
const categorySuggestions = document.getElementById('categorySuggestions');

if (categoryInput && categorySuggestions) {
    categoryInput.addEventListener('focus', () => {
        if (categorySuggestions.children.length > 0) {
            categorySuggestions.classList.add('show');
        }
    });
    categoryInput.addEventListener('blur', () => {
        setTimeout(() => categorySuggestions.classList.remove('show'), 200);
    });
    categoryInput.addEventListener('input', function() {
        const val = this.value.toLowerCase();
        const items = categorySuggestions.querySelectorAll('.category-suggestion-item');
        let hasVisible = false;
        items.forEach(item => {
            const match = item.textContent.toLowerCase().includes(val);
            item.style.display = match ? '' : 'none';
            if (match) hasVisible = true;
        });
        categorySuggestions.classList.toggle('show', hasVisible && val.length > 0);
    });
}

// ==================== MAIN IMAGE ====================
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('preview').src = '';
}

// ==================== SUBPACKAGES ====================
let subIndex = 0;

function addSubpackage(data = null) {
    const list = document.getElementById('subpackageList');
    const idx = subIndex++;
    const name = data ? (data.name || '') : '';
    const desc = data ? (data.description || '') : '';

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
                <div class="image-upload-wrapper" style="padding:1.25rem;">
                    <input type="file" name="subpackages[${idx}][image]" accept="image/*"
                        onchange="previewSubImage(event, 'sub_img_preview_${idx}')">
                    <div style="font-size:1.5rem;color:#8B7355;margin-bottom:0.5rem;"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> main image<br><small>PNG, JPG, WEBP (max. 20MB)</small></div>
                </div>
                <div id="sub_img_preview_${idx}" style="display:none;margin-top:0.75rem;">
                    <img style="max-width:200px;max-height:150px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);" src="" alt="preview">
                </div>
            </div>
            <div class="form-group full-width">
                <label class="form-label">Additional Photos</label>
                <div class="image-upload-wrapper" style="padding:1.25rem;">
                    <input type="file" name="subpackages[${idx}][photos][]" accept="image/*" multiple
                        onchange="previewSubPhotos(event, 'sub_photos_grid_${idx}')">
                    <div style="font-size:1.5rem;color:#8B7355;margin-bottom:0.5rem;"><i class="fas fa-images"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> multiple photos<br><small>PNG, JPG, WEBP (max. 20MB each)</small></div>
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

// ==================== GALLERY PHOTOS ====================
let photoFiles = [];

async function resizeImage(file, maxWidth = 1920) {
    return new Promise(resolve => {
        const img = new Image();
        const url = URL.createObjectURL(file);
        img.onload = () => {
            URL.revokeObjectURL(url);
            if (img.width <= maxWidth) { resolve(file); return; }
            const canvas = document.createElement('canvas');
            const ratio = maxWidth / img.width;
            canvas.width = maxWidth;
            canvas.height = img.height * ratio;
            canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
            canvas.toBlob(blob => resolve(new File([blob], file.name, { type: 'image/jpeg' })), 'image/jpeg', 0.85);
        };
        img.src = url;
    });
}

async function previewMultipleImages(event) {
    const files = Array.from(event.target.files);
    const resized = await Promise.all(files.map(f => resizeImage(f)));
    photoFiles = [...photoFiles, ...resized];

    const dt = new DataTransfer();
    photoFiles.forEach(f => dt.items.add(f));
    document.getElementById('photos').files = dt.files;
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

@if(old('subpackages'))
    const oldSubs = @json(old('subpackages'));
    Object.values(oldSubs).forEach(sub => addSubpackage(sub));
@endif
</script>
@endsection