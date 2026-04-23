{{-- resources/views/admin/blogs/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Blog Post')
@section('page-title', 'Edit Blog Post')

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

    .section-icon {
        width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #8B7355, #6B5644);
        color: white; border-radius: 10px; font-size: 1.2rem;
    }

    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
    .form-group { margin-bottom: 1.5rem; }

    .form-label {
        font-family: 'Work Sans', sans-serif; display: block; color: #333;
        font-weight: 600; margin-bottom: 0.75rem; font-size: 0.9rem;
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
    textarea.form-control { min-height: 140px; resize: vertical; line-height: 1.6; }

    .error-message { color: #e74c3c; font-size: 0.85rem; margin-top: 0.5rem; }
    .form-help { color: #999; font-size: 0.85rem; margin-top: 0.5rem; font-style: italic; }

    /* Current PDF box */
    .current-pdf-box {
        display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem;
        background: #f0ebe4; border-radius: 10px; border: 1px solid #d4b896; margin-bottom: 1rem;
    }
    .current-pdf-info span { display: block; font-size: 0.8rem; color: #999; margin-bottom: 0.2rem; }
    .current-pdf-info a { font-weight: 600; color: #8B7355; text-decoration: none; font-size: 0.92rem; }
    .current-pdf-info a:hover { color: #6B5644; text-decoration: underline; }

    /* Current image box */
    .current-image-box {
        margin-bottom: 1rem; padding: 1.25rem; background: #f8f9fa; border-radius: 12px; border: 1px solid #e8e8e8;
    }
    .current-image-label { font-weight: 600; color: #666; font-size: 0.82rem; display: block; margin-bottom: 0.75rem; }
    .current-image-box img { max-width: 340px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: block; }

    /* Upload areas */
    .upload-area {
        border: 2px dashed #e0e0e0; border-radius: 12px; padding: 1.75rem 2rem;
        text-align: center; cursor: pointer; transition: all 0.3s ease; background: #fafafa;
    }
    .upload-area:hover { border-color: #8B7355; background: #f5f5f5; }
    .upload-area.pdf-area { border-color: #c5a47e; background: #fdf8f3; }
    .upload-area.pdf-area:hover { border-color: #8B7355; background: #f5ede0; }

    .upload-icon { font-size: 2.25rem; margin-bottom: 0.6rem; opacity: 0.5; }
    .upload-text { color: #555; font-size: 0.92rem; font-family: 'Work Sans', sans-serif; margin-bottom: 0.2rem; }
    .upload-hint { color: #aaa; font-size: 0.82rem; font-family: 'Work Sans', sans-serif; }

    .image-preview { display: none; margin-top: 1rem; border-radius: 10px; overflow: hidden; max-width: 340px; margin-left: auto; margin-right: auto; }
    .image-preview img { width: 100%; height: auto; display: block; }
    .image-preview.show { display: block; }

    .pdf-selected {
        display: none; margin-top: 0.75rem; padding: 0.65rem 1rem;
        background: #f0ebe4; border-radius: 8px; border: 1px solid #d4b896;
        font-family: 'Work Sans', sans-serif; font-size: 0.88rem; color: #6B5644;
        align-items: center; gap: 0.5rem;
    }
    .pdf-selected.show { display: flex; }

    /* Server badge */
    .server-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7;
        border-radius: 20px; padding: 0.3rem 0.75rem; font-size: 0.75rem;
        font-family: 'Work Sans', sans-serif; font-weight: 600; margin-top: 0.5rem;
    }

    /* Checkbox */
    .checkbox-wrapper {
        display: flex; align-items: center; gap: 0.75rem; padding: 1rem;
        background: #f8f9fa; border-radius: 12px; border: 1px solid #e8e8e8; cursor: pointer;
    }
    .checkbox-wrapper input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
    .checkbox-label { font-family: 'Work Sans', sans-serif; color: #333; font-weight: 500; cursor: pointer; }

    /* Actions */
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
    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf
        @method('PUT')

        {{-- Blog Information --}}
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">📝</span>
                Blog Post Information
            </h3>

            <div class="form-group">
                <label for="title" class="form-label">
                    Post Title <span class="required">*</span>
                </label>
                <input type="text" id="title" name="title"
                    class="form-control @error('title') error @enderror"
                    value="{{ old('title', $blog->title) }}" required
                    placeholder="e.g., Best Time for Bali Wedding">
                @error('title')<div class="error-message">{{ $message }}</div>@enderror
                <div class="form-help">The slug will be automatically generated from the title</div>
            </div>

            <div class="form-grid">
                <div class="form-group" style="margin-bottom:0">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" id="author" name="author"
                        class="form-control @error('author') error @enderror"
                        value="{{ old('author', $blog->author) }}" placeholder="e.g., Wedding Planner Team">
                    @error('author')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label for="published_at" class="form-label">Publish Date</label>
                    <input type="date" id="published_at" name="published_at"
                        class="form-control @error('published_at') error @enderror"
                        value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d') : '') }}">
                    @error('published_at')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group" style="margin-top:1.5rem">
                <label for="excerpt" class="form-label">Excerpt</label>
                <textarea id="excerpt" name="excerpt"
                    class="form-control @error('excerpt') error @enderror"
                    placeholder="Brief summary of the blog post...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                @error('excerpt')<div class="error-message">{{ $message }}</div>@enderror
                <div class="form-help">Optional short description shown in blog listings</div>
            </div>

            {{-- PDF --}}
            <div class="form-group">
                <label class="form-label">
                    {{ $blog->pdf ? 'Replace PDF (Optional)' : 'Upload PDF' }}
                </label>
                @if($blog->pdf)
                <div class="current-pdf-box">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    <div class="current-pdf-info">
                        <span>Current PDF</span>
                        <a href="{{ asset('storage/' . $blog->pdf) }}" target="_blank">
                            {{ basename($blog->pdf) }} — View ↗
                        </a>
                    </div>
                </div>
                @endif

                <div class="upload-area pdf-area" id="pdfArea" onclick="document.getElementById('pdf').click()">
                    <div class="upload-icon"><i class="fas fa-file-pdf"></i></div>
                    <div class="upload-text">Upload new PDF to replace</div>
                    <div class="upload-hint">
                        {{ $blog->pdf ? 'Leave empty to keep current PDF' : 'PDF only · Max 50 MB' }}
                    </div>
                </div>
                <input type="file" id="pdf" name="pdf" accept="application/pdf" style="display:none;">
                <div class="pdf-selected" id="pdfSelected">
                    <i class="fas fa-check-circle"></i>
                    <span id="pdfName"></span>
                </div>
                @error('pdf')<div class="error-message">{{ $message }}</div>@enderror
                <div class="form-help">Uploading a new PDF will replace the existing content</div>
            </div>

            {{-- Publish checkbox --}}
            <div class="form-group" style="margin-bottom:0">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="is_published" value="1"
                        {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                    <span class="checkbox-label">Publish this post</span>
                </label>
            </div>
        </div>

        {{-- Featured Image --}}
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">🖼️</span>
                Featured Image
            </h3>

            @if($blog->image)
            <div class="current-image-box">
                <span class="current-image-label">Current image:</span>
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
            </div>
            @endif

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">
                    {{ $blog->image ? 'Replace Image (Optional)' : 'Upload Image' }}
                </label>
                <div class="upload-area" id="imageArea" onclick="document.getElementById('image').click()">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text">Click to upload or drag and drop</div>
                    <div class="upload-hint">JPG, PNG, WEBP · Max 20 MB</div>
                </div>
                <input type="file" id="image" name="image"
                    class="@error('image') error @enderror"
                    accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none;">
                <div class="image-preview" id="imagePreview">
                    <img src="" alt="Preview" id="previewImg">
                </div>
                @error('image')<div class="error-message">{{ $message }}</div>@enderror
                <div class="server-badge">
                    <i class="fas fa-server"></i>
                    Gambar dikompres &amp; dikonversi ke WebP otomatis oleh server
                </div>
                <div class="form-help">
                    {{ $blog->image ? 'Leave empty to keep current image.' : '' }}
                    This image will be shown at the top of the blog post.
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">← Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    💾 Update Blog Post
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
/* ── Image preview ── */
const imageInput   = document.getElementById('image');
const imageArea    = document.getElementById('imageArea');
const imagePreview = document.getElementById('imagePreview');
const previewImg   = document.getElementById('previewImg');

imageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        imagePreview.classList.add('show');
    };
    reader.readAsDataURL(file);
});

imageArea.addEventListener('dragover', e => { e.preventDefault(); imageArea.style.borderColor = '#8B7355'; });
imageArea.addEventListener('dragleave', () => { imageArea.style.borderColor = ''; });
imageArea.addEventListener('drop', function (e) {
    e.preventDefault();
    imageArea.style.borderColor = '';
    const dt = new DataTransfer();
    Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
    imageInput.files = dt.files;
    imageInput.dispatchEvent(new Event('change'));
});

/* ── PDF preview ── */
const pdfInput    = document.getElementById('pdf');
const pdfArea     = document.getElementById('pdfArea');
const pdfSelected = document.getElementById('pdfSelected');
const pdfName     = document.getElementById('pdfName');

pdfInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    pdfName.textContent = file.name;
    pdfSelected.classList.add('show');
});

pdfArea.addEventListener('dragover', e => e.preventDefault());
pdfArea.addEventListener('drop', function (e) {
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