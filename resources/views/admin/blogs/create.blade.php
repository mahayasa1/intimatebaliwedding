{{-- resources/views/admin/blogs/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add New Blog Post')
@section('page-title', 'Add New Blog Post')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container { max-width: 1000px; margin: 0 auto; }

    .form-card {
        background: white; padding: 2.5rem; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e8e8; margin-bottom: 2rem;
    }

    .section-title {
        font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700;
        color: #1a1a1a; margin-bottom: 1.5rem; display: flex; align-items: center;
        gap: 0.75rem; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0;
    }

    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
    .form-group { margin-bottom: 1.5rem; }

    .form-label {
        font-family: 'Work Sans', sans-serif; display: block; color: #333;
        font-weight: 600; margin-bottom: 0.75rem; font-size: 0.9rem; letter-spacing: 0.3px;
    }
    .form-label .required { color: #e74c3c; margin-left: 0.25rem; }

    .form-control {
        font-family: 'Work Sans', sans-serif; width: 100%; padding: 0.875rem 1.25rem;
        border: 2px solid #e0e0e0; border-radius: 12px; font-size: 0.95rem;
        transition: all 0.3s ease; background: white;
    }
    .form-control:focus {
        outline: none; border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
    }
    .form-control.error { border-color: #e74c3c; background: #fff5f5; }
    textarea.form-control { min-height: 140px; resize: vertical; line-height: 1.6; }

    .error-message {
        font-family: 'Work Sans', sans-serif; color: #e74c3c; font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-help {
        font-family: 'Work Sans', sans-serif; color: #999;
        font-size: 0.85rem; margin-top: 0.5rem; font-style: italic;
    }

    /* Upload area */
    .upload-area {
        border: 2px dashed #e0e0e0; border-radius: 12px; padding: 2rem;
        text-align: center; cursor: pointer; transition: all 0.3s ease;
        background: #fafafa; position: relative; overflow: hidden;
    }
    .upload-area:hover, .upload-area.drag-over { border-color: #8B7355; background: #f5f0eb; }
    .upload-area input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .upload-icon { font-size: 2.75rem; margin-bottom: 0.75rem; opacity: 0.5; }
    .upload-text { color: #555; font-size: 0.95rem; font-family: 'Work Sans', sans-serif; margin-bottom: 0.25rem; }
    .upload-hint { color: #aaa; font-size: 0.82rem; font-family: 'Work Sans', sans-serif; }

    /* Image preview */
    .image-preview { display: none; margin-top: 1rem; border-radius: 10px; overflow: hidden; max-width: 380px; margin-left: auto; margin-right: auto; }
    .image-preview img { width: 100%; height: auto; display: block; }
    .image-preview.show { display: block; }

    /* PDF selected */
    .pdf-selected {
        display: none; margin-top: 0.75rem; padding: 0.75rem 1rem;
        background: #f0ebe4; border-radius: 8px; border: 1px solid #d4b896;
        font-family: 'Work Sans', sans-serif; font-size: 0.9rem; color: #6B5644;
        align-items: center; gap: 0.5rem;
    }
    .pdf-selected.show { display: flex; }

    /* Checkbox */
    .checkbox-wrapper {
        display: flex; align-items: center; gap: 0.75rem; padding: 1rem;
        background: #f8f9fa; border-radius: 12px; border: 1px solid #e8e8e8;
        cursor: pointer;
    }
    .checkbox-wrapper input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
    .checkbox-label { font-family: 'Work Sans', sans-serif; color: #333; font-weight: 500; cursor: pointer; }

    /* Server-side info badge */
    .server-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7;
        border-radius: 20px; padding: 0.3rem 0.75rem; font-size: 0.75rem;
        font-family: 'Work Sans', sans-serif; font-weight: 600; margin-top: 0.5rem;
    }

    /* Action */
    .action-section {
        background: white; padding: 2rem; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e8e8;
    }
    .action-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }

    .btn {
        font-family: 'Work Sans', sans-serif; display: inline-flex; align-items: center;
        gap: 0.5rem; padding: 0.875rem 1.75rem; border-radius: 12px; text-decoration: none;
        font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; font-size: 0.95rem;
    }
    .btn-secondary { background: linear-gradient(135deg, #95a5a6, #7f8c8d); color: white; }
    .btn-secondary:hover { transform: translateY(-2px); }
    .btn-primary { background: linear-gradient(135deg, #8B7355, #6B5644); color: white; }
    .btn-primary:hover { transform: translateY(-2px); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    @media (max-width: 768px) {
        .form-card { padding: 1.5rem; }
        .form-grid { grid-template-columns: 1fr; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf

        {{-- Blog Information --}}
        <div class="form-card">
            <h3 class="section-title">Blog Post Information</h3>

            <div class="form-group">
                <label for="title" class="form-label">
                    Post Title <span class="required">*</span>
                </label>
                <input type="text" id="title" name="title"
                    class="form-control @error('title') error @enderror"
                    value="{{ old('title') }}" required placeholder="e.g., Best Time for Bali Wedding">
                @error('title')<div class="error-message">{{ $message }}</div>@enderror
                <div class="form-help">Slug akan di-generate otomatis dari judul</div>
            </div>

            <div class="form-grid">
                <div class="form-group" style="margin-bottom:0">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" id="author" name="author"
                        class="form-control @error('author') error @enderror"
                        value="{{ old('author') }}" placeholder="e.g., Wedding Planner Team">
                    @error('author')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label for="published_at" class="form-label">Publish Date</label>
                    <input type="date" id="published_at" name="published_at"
                        class="form-control @error('published_at') error @enderror"
                        value="{{ old('published_at', date('Y-m-d')) }}">
                    @error('published_at')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group" style="margin-top:1.5rem">
                <label for="excerpt" class="form-label">Excerpt</label>
                <textarea id="excerpt" name="excerpt"
                    class="form-control @error('excerpt') error @enderror"
                    placeholder="Brief summary of the blog post...">{{ old('excerpt') }}</textarea>
                @error('excerpt')<div class="error-message">{{ $message }}</div>@enderror
                <div class="form-help">Opsional — jika kosong akan diisi otomatis dari PDF</div>
            </div>

            <div class="form-group">
                <label class="form-label">Upload PDF <span class="required">*</span></label>
                <div class="upload-area" id="pdfArea">
                    <input type="file" id="pdf" name="pdf" accept="application/pdf" required>
                    <div class="upload-icon"><i class="fas fa-file-pdf"></i></div>
                    <div class="upload-text">Click to upload or drag and drop PDF</div>
                    <div class="upload-hint">PDF only · Max 50 MB</div>
                </div>
                <div class="pdf-selected" id="pdfSelected">
                    <i class="fas fa-check-circle"></i>
                    <span id="pdfName"></span>
                </div>
                @error('pdf')<div class="error-message">{{ $message }}</div>@enderror
                <div class="form-help">Content akan diekstrak otomatis dari PDF</div>
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="is_published" value="1"
                        {{ old('is_published') ? 'checked' : '' }}>
                    <span class="checkbox-label">Publish this post immediately</span>
                </label>
            </div>
        </div>

        {{-- Featured Image --}}
        <div class="form-card">
            <h3 class="section-title"><i class="fas fa-image"></i> Featured Image</h3>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Upload Image <span class="required">*</span></label>
                <div class="upload-area" id="imageArea">
                    <input type="file" id="image" name="image"
                        class="@error('image') error @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp" required>
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text">Click to upload or drag and drop</div>
                    <div class="upload-hint">JPG, PNG, WEBP · Max 20 MB</div>
                </div>
                <div class="image-preview" id="imagePreview">
                    <img src="" alt="Preview" id="previewImg">
                </div>
                @error('image')<div class="error-message">{{ $message }}</div>@enderror
                <div class="server-badge">
                    <i class="fas fa-server"></i>
                    Gambar dikompres &amp; dikonversi ke WebP otomatis oleh server
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">← Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    Create Blog Post
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
/* ── IMAGE preview only (no compression) ── */
const imageInput = document.getElementById('image');
const imageArea  = document.getElementById('imageArea');

imageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('imagePreview').classList.add('show');
        imageArea.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

/* Drag & drop image */
imageArea.addEventListener('dragover', e => { e.preventDefault(); imageArea.classList.add('drag-over'); });
imageArea.addEventListener('dragleave', () => imageArea.classList.remove('drag-over'));
imageArea.addEventListener('drop', e => {
    e.preventDefault();
    imageArea.classList.remove('drag-over');
    const dt = new DataTransfer();
    Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
    imageInput.files = dt.files;
    imageInput.dispatchEvent(new Event('change'));
});

/* ── PDF preview ── */
const pdfInput    = document.getElementById('pdf');
const pdfSelected = document.getElementById('pdfSelected');
const pdfName     = document.getElementById('pdfName');
const pdfArea     = document.getElementById('pdfArea');

pdfInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    pdfName.textContent = file.name;
    pdfSelected.classList.add('show');
});

pdfArea.addEventListener('dragover', e => e.preventDefault());
pdfArea.addEventListener('drop', e => {
    e.preventDefault();
    const dt = new DataTransfer();
    Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
    pdfInput.files = dt.files;
    pdfInput.dispatchEvent(new Event('change'));
});

/* ── Submit loading ── */
document.getElementById('blogForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
});
</script>
@endpush