{{-- resources/views/admin/packages/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add New Package')
@section('page-title', 'Add New Package')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container { max-width: 900px; margin: 0 auto; }

    .form-card {
        background: white; padding: 2.5rem; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e8e8; margin-bottom: 2rem;
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
        font-family: 'Work Sans', sans-serif; width: 100%; padding: 0.875rem 1.25rem;
        border: 2px solid #e0e0e0; border-radius: 12px; font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: white;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none; border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1); transform: translateY(-2px);
    }

    .form-control.error { border-color: #e74c3c; background: #fff5f5; }
    textarea.form-control { min-height: 140px; resize: vertical; line-height: 1.6; }

    /* Category suggestions */
    .category-input-wrapper { position: relative; }

    .category-suggestions {
        position: absolute; top: calc(100% + 4px); left: 0; right: 0;
        background: white; border: 2px solid #e0e0e0; border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12); z-index: 100; display: none;
        max-height: 200px; overflow-y: auto;
    }

    .category-suggestions.show { display: block; }

    .category-suggestion-item {
        padding: 0.75rem 1.25rem; cursor: pointer;
        font-family: 'Work Sans', sans-serif; font-size: 0.9rem; color: #333;
        transition: background 0.2s ease; display: flex; align-items: center; gap: 0.5rem;
    }

    .category-suggestion-item:hover { background: #f5f0eb; color: #8B7355; }

    .category-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.75rem; }

    .category-tag {
        display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.3rem 0.75rem;
        background: linear-gradient(135deg, rgba(139,115,85,0.12), rgba(107,86,68,0.12));
        color: #8B7355; border: 1px solid rgba(139,115,85,0.25); border-radius: 20px;
        font-size: 0.78rem; font-weight: 600; font-family: 'Work Sans', sans-serif;
        cursor: pointer; transition: all 0.2s ease;
    }

    .category-tag:hover { background: #8B7355; color: white; border-color: #8B7355; }

    .form-help {
        font-family: 'Work Sans', sans-serif; color: #999;
        font-size: 0.85rem; margin-top: 0.5rem; font-style: italic;
    }

    .error-message {
        font-family: 'Work Sans', sans-serif; color: #e74c3c; font-size: 0.85rem;
        margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;
    }

    /* Upload */
    .upload-area {
        border: 2px dashed #e0e0e0; border-radius: 12px;
        padding: 2rem; text-align: center; cursor: pointer;
        transition: all 0.3s ease; background: #fafafa;
        position: relative; overflow: hidden;
    }

    .upload-area:hover, .upload-area.drag-over { border-color: #8B7355; background: #f5f0eb; }

    .upload-area input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }

    .upload-icon { font-size: 3rem; color: #8B7355; margin-bottom: 1rem; opacity: 0.5; }
    .upload-text { color: #666; font-size: 0.95rem; }
    .upload-text strong { color: #8B7355; }

    /* Progress */
    .compress-progress { display: none; margin-top: 0.75rem; padding: 0.75rem 1rem; background: #f0f7ff; border: 1px solid #90caf9; border-radius: 8px; }
    .compress-progress.show { display: block; }
    .progress-bar-wrap { background: #dde; border-radius: 4px; height: 6px; overflow: hidden; margin-top: 6px; }
    .progress-bar { height: 100%; background: linear-gradient(90deg, #8B7355, #D4AF37); border-radius: 4px; transition: width 0.2s ease; width: 0%; }
    .progress-label { font-family: 'Work Sans', sans-serif; font-size: 0.82rem; color: #1565c0; }

    /* Preview */
    .image-preview { margin-top: 1rem; display: none; position: relative; }
    .image-preview img { max-width: 100%; max-height: 300px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: block; }

    .remove-image {
        position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white;
        border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;
    }

    .remove-image:hover { background: #c0392b; transform: scale(1.1); }

    .photos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem; }

    .photo-preview-item { position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .photo-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .remove-photo {
        position: absolute; top: 5px; right: 5px; background: #e74c3c; color: white;
        border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; transition: all 0.3s ease;
    }

    .remove-photo:hover { background: #c0392b; transform: scale(1.1); }

    /* Subpackages */
    .subpackage-item {
        background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 12px;
        padding: 1.5rem; margin-bottom: 1rem; position: relative; transition: all 0.3s ease;
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

    /* Actions */
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

    .btn-secondary { background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; }
    .btn-secondary:hover { transform: translateY(-2px); }
    .btn-primary { background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%); color: white; }
    .btn-primary:hover { transform: translateY(-2px); }

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
    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" id="packageForm">
        @csrf

        {{-- Package Information --}}
        <div class="form-card">
            <h3 class="section-title">Package Information</h3>

            <div class="form-group">
                <label for="name" class="form-label">Package Name <span class="required">*</span></label>
                <input type="text" id="name" name="name"
                    class="form-control @error('name') error @enderror"
                    value="{{ old('name') }}" required placeholder="e.g., Beach Wedding Package">
                @error('name')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <div class="category-input-wrapper">
                    <input type="text" id="category" name="category"
                        class="form-control @error('category') error @enderror"
                        value="{{ old('category') }}"
                        placeholder="e.g., Intimate, Garden, Beach, Chapel"
                        autocomplete="off">
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
                @error('category')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Main Package Image <span class="required">*</span></label>
                <div class="upload-area" id="imageUploadArea">
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required>
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> or drag and drop<br><small>PNG, JPG, WEBP — dikompres otomatis sebelum upload</small></div>
                </div>
                <div class="compress-progress" id="imageProgress">
                    <div class="progress-label" id="imageProgressLabel">Mengompres…</div>
                    <div class="progress-bar-wrap"><div class="progress-bar" id="imageProgressBar"></div></div>
                </div>
                <div class="image-preview" id="imagePreview">
                    <button type="button" class="remove-image" onclick="removeMainImage()"><i class="fas fa-times"></i></button>
                    <img id="imagePreviewImg" src="" alt="Preview">
                </div>
                @error('image')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description"
                    class="form-control @error('description') error @enderror"
                    placeholder="Describe the package...">{{ old('description') }}</textarea>
                @error('description')<div class="error-message">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Subpackages --}}
        <div class="form-card">
            <h3 class="section-title">
                <i class="fas fa-list-ul" style="color:#8B7355;font-size:1.2rem;"></i>
                Sub-packages / Options
            </h3>
            <div class="form-help" style="margin-bottom:1.5rem;">Tambahkan pilihan atau tier yang tersedia (opsional)</div>
            <div id="subpackageList"></div>
            <button type="button" class="btn-add-sub" onclick="addSubpackage()">
                <i class="fas fa-plus"></i> Add Sub-package
            </button>
        </div>

        {{-- Gallery Photos --}}
        <div class="form-card">
            <h3 class="section-title">Package Gallery Photos</h3>
            <div class="form-group">
                <label for="photos" class="form-label">Additional Photos</label>
                <div class="upload-area" id="photosUploadArea">
                    <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
                    <div class="upload-icon"><i class="fas fa-images"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> or drag and drop<br><small>PNG, JPG, WEBP — Multiple files — dikompres otomatis</small></div>
                </div>
                <div class="compress-progress" id="photosProgress">
                    <div class="progress-label" id="photosProgressLabel">Mengompres…</div>
                    <div class="progress-bar-wrap"><div class="progress-bar" id="photosProgressBar"></div></div>
                </div>
                @error('photos.*')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div id="photosGrid" class="photos-grid" style="display:none;"></div>
        </div>

        {{-- Actions --}}
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">← Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">Create Package</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
/* ── CATEGORY ── */
function selectCategory(cat) {
    document.getElementById('category').value = cat;
    document.getElementById('categorySuggestions')?.classList.remove('show');
}

const categoryInput       = document.getElementById('category');
const categorySuggestions = document.getElementById('categorySuggestions');

if (categoryInput && categorySuggestions) {
    categoryInput.addEventListener('focus', () => {
        if (categorySuggestions.children.length > 0) categorySuggestions.classList.add('show');
    });
    categoryInput.addEventListener('blur', () => setTimeout(() => categorySuggestions.classList.remove('show'), 200));
    categoryInput.addEventListener('input', function () {
        const val = this.value.toLowerCase();
        let hasVisible = false;
        categorySuggestions.querySelectorAll('.category-suggestion-item').forEach(item => {
            const match = item.textContent.toLowerCase().includes(val);
            item.style.display = match ? '' : 'none';
            if (match) hasVisible = true;
        });
        categorySuggestions.classList.toggle('show', hasVisible && val.length > 0);
    });
}

/* ── MAIN IMAGE ── */
const imageInput = document.getElementById('image');

imageInput.addEventListener('change', async function () {
    const raw = this.files[0];
    if (!raw) return;
    showProgress('image', 40, 'Mengompres gambar…', false);
    const result = await ImageCompressor.compress(raw, { maxWidth: 1920, maxHeight: 1920, quality: 0.82 });
    ImageCompressor.replaceFiles(imageInput, [result]);
    showProgress('image', 100, '✓ Gambar siap diupload', true);

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('imagePreviewImg').src = e.target.result;
        document.getElementById('imagePreview').style.display = 'block';
        document.getElementById('imageUploadArea').style.display = 'none';
    };
    reader.readAsDataURL(result);
});

function removeMainImage() {
    imageInput.value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imagePreviewImg').src = '';
    document.getElementById('imageUploadArea').style.display = 'block';
}

/* ── GALLERY PHOTOS ── */
let photoFiles    = [];
const photosInput = document.getElementById('photos');

photosInput.addEventListener('change', async function () {
    const rawFiles = Array.from(this.files);
    if (!rawFiles.length) return;
    showProgress('photos', 0, `Mengompres 0 / ${rawFiles.length}…`, false);

    const compressed = [];
    for (let i = 0; i < rawFiles.length; i++) {
        const result = await ImageCompressor.compress(rawFiles[i], { maxWidth: 1920, maxHeight: 1920, quality: 0.82 });
        compressed.push(result);
        showProgress('photos', Math.round(((i + 1) / rawFiles.length) * 100), `Mengompres ${i + 1} / ${rawFiles.length}…`, false);
    }

    photoFiles = [...photoFiles, ...compressed];
    ImageCompressor.replaceFiles(photosInput, photoFiles);
    showProgress('photos', 100, `✓ ${photoFiles.length} foto siap diupload`, true);
    renderPhotos();
});

function renderPhotos() {
    const grid = document.getElementById('photosGrid');
    grid.innerHTML = '';
    if (!photoFiles.length) { grid.style.display = 'none'; return; }
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
}

function removePhoto(index) {
    photoFiles.splice(index, 1);
    ImageCompressor.replaceFiles(photosInput, photoFiles);
    renderPhotos();
}

/* ── PROGRESS HELPER ── */
function showProgress(prefix, pct, text, done) {
    const prog  = document.getElementById(prefix + 'Progress');
    const bar   = document.getElementById(prefix + 'ProgressBar');
    const label = document.getElementById(prefix + 'ProgressLabel');
    if (!prog) return;
    prog.classList.add('show');
    bar.style.width = pct + '%';
    label.textContent = text;
    if (done) {
        bar.style.background = '#27ae60';
        label.style.color    = '#155724';
        setTimeout(() => { prog.classList.remove('show'); bar.style.background = ''; label.style.color = ''; }, 2500);
    }
}

/* ── DRAG & DROP ── */
['imageUploadArea', 'photosUploadArea'].forEach(id => {
    const area = document.getElementById(id);
    if (!area) return;
    area.addEventListener('dragover',  e => { e.preventDefault(); area.classList.add('drag-over'); });
    area.addEventListener('dragleave', ()  => area.classList.remove('drag-over'));
    area.addEventListener('drop', e => {
        e.preventDefault(); area.classList.remove('drag-over');
        const inputId = id === 'imageUploadArea' ? 'image' : 'photos';
        const input   = document.getElementById(inputId);
        const dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    });
});

/* ── SUBPACKAGES ── */
let subIndex = 0;

function addSubpackage(data) {
    const list = document.getElementById('subpackageList');
    const idx  = subIndex++;
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
                <div class="upload-area" style="padding:1.25rem;" id="sub_area_${idx}">
                    <input type="file" id="sub_img_${idx}" name="subpackages[${idx}][image]"
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div style="font-size:1.5rem;color:#8B7355;margin-bottom:0.5rem;opacity:0.5;"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong><br><small>PNG, JPG, WEBP — dikompres otomatis</small></div>
                </div>
                <div class="compress-progress" id="sub_img_prog_${idx}">
                    <div class="progress-label" id="sub_img_label_${idx}">Mengompres…</div>
                    <div class="progress-bar-wrap"><div class="progress-bar" id="sub_img_bar_${idx}"></div></div>
                </div>
                <div id="sub_img_preview_${idx}" style="display:none;margin-top:0.75rem;">
                    <img style="max-width:200px;max-height:150px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);" src="" alt="preview">
                </div>
            </div>
            <div class="form-group full-width">
                <label class="form-label">Additional Photos</label>
                <div class="upload-area" style="padding:1.25rem;" id="sub_photos_area_${idx}">
                    <input type="file" id="sub_photos_${idx}" name="subpackages[${idx}][photos][]"
                        accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
                    <div style="font-size:1.5rem;color:#8B7355;margin-bottom:0.5rem;opacity:0.5;"><i class="fas fa-images"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> multiple photos — dikompres otomatis</div>
                </div>
                <div class="compress-progress" id="sub_photos_prog_${idx}">
                    <div class="progress-label" id="sub_photos_label_${idx}">Mengompres…</div>
                    <div class="progress-bar-wrap"><div class="progress-bar" id="sub_photos_bar_${idx}"></div></div>
                </div>
                <div id="sub_photos_grid_${idx}" class="photos-grid" style="display:none;margin-top:0.75rem;"></div>
            </div>
        </div>
    `;
    list.appendChild(div);
    renumberSubpackages();

    /* Compress sub main image */
    const subImgInput = document.getElementById(`sub_img_${idx}`);
    subImgInput.addEventListener('change', async function () {
        const raw = this.files[0];
        if (!raw) return;
        showSubProgress(idx, 'img', 40, 'Mengompres…', false);
        const result = await ImageCompressor.compress(raw, { maxWidth: 1920, maxHeight: 1920, quality: 0.82 });
        ImageCompressor.replaceFiles(subImgInput, [result]);
        showSubProgress(idx, 'img', 100, '✓ Siap', true);
        const reader = new FileReader();
        reader.onload = e => {
            const container = document.getElementById(`sub_img_preview_${idx}`);
            container.style.display = 'block';
            container.querySelector('img').src = e.target.result;
        };
        reader.readAsDataURL(result);
    });

    /* Compress sub additional photos */
    let subPhotoFiles = [];
    const subPhotosInput = document.getElementById(`sub_photos_${idx}`);
    subPhotosInput.addEventListener('change', async function () {
        const raw = Array.from(this.files);
        if (!raw.length) return;
        showSubProgress(idx, 'photos', 0, `Mengompres 0 / ${raw.length}…`, false);
        const compressed = [];
        for (let i = 0; i < raw.length; i++) {
            const result = await ImageCompressor.compress(raw[i], { maxWidth: 1920, maxHeight: 1920, quality: 0.82 });
            compressed.push(result);
            showSubProgress(idx, 'photos', Math.round(((i + 1) / raw.length) * 100), `Mengompres ${i + 1} / ${raw.length}…`, false);
        }
        subPhotoFiles = [...subPhotoFiles, ...compressed];
        ImageCompressor.replaceFiles(subPhotosInput, subPhotoFiles);
        showSubProgress(idx, 'photos', 100, `✓ ${subPhotoFiles.length} foto siap`, true);

        const grid = document.getElementById(`sub_photos_grid_${idx}`);
        grid.innerHTML = '';
        if (!subPhotoFiles.length) { grid.style.display = 'none'; return; }
        grid.style.display = 'grid';
        subPhotoFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const d = document.createElement('div');
                d.className = 'photo-preview-item';
                d.innerHTML = `<img src="${e.target.result}" alt="Photo ${i + 1}" style="width:100%;height:100%;object-fit:cover;">`;
                grid.appendChild(d);
            };
            reader.readAsDataURL(file);
        });
    });
}

function showSubProgress(idx, type, pct, text, done) {
    const prog  = document.getElementById(`sub_${type}_prog_${idx}`);
    const bar   = document.getElementById(`sub_${type}_bar_${idx}`);
    const label = document.getElementById(`sub_${type}_label_${idx}`);
    if (!prog) return;
    prog.classList.add('show');
    bar.style.width = pct + '%';
    label.textContent = text;
    if (done) {
        bar.style.background = '#27ae60';
        label.style.color    = '#155724';
        setTimeout(() => { prog.classList.remove('show'); bar.style.background = ''; label.style.color = ''; }, 2500);
    }
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

@if(old('subpackages'))
    const oldSubs = @json(old('subpackages'));
    Object.values(oldSubs).forEach(sub => addSubpackage(sub));
@endif

/* ── SUBMIT ── */
document.getElementById('packageForm').addEventListener('submit', function () {
    const btn    = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '⏳ Saving…';
});
</script>
@endpush