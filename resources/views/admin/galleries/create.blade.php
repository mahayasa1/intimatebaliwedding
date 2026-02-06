@extends('layouts.admin')

@section('title', 'Add New Photo')
@section('page-title', 'Add New Photo to Gallery')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }

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

    .section-icon {
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

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-family: 'Work Sans', sans-serif;
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
    }

    .form-label .required {
        color: #e74c3c;
        margin-left: 0.25rem;
    }

    .form-control {
        font-family: 'Work Sans', sans-serif;
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
        transform: translateY(-2px);
    }

    .form-control.error {
        border-color: #e74c3c;
        background: #fff5f5;
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
        line-height: 1.6;
    }

    .error-message {
        font-family: 'Work Sans', sans-serif;
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .error-message i {
        font-size: 1rem;
    }

    .form-help {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        font-style: italic;
    }

    /* Image Upload */
    .image-upload-wrapper {
        position: relative;
    }

    .image-upload-area {
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .image-upload-area:hover {
        border-color: #8B7355;
        background: #f5f5f5;
    }

    .image-upload-area.dragover {
        border-color: #8B7355;
        background: linear-gradient(135deg, rgba(139, 115, 85, 0.05) 0%, rgba(107, 86, 68, 0.05) 100%);
        border-style: solid;
    }

    .upload-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #8B7355;
        opacity: 0.4;
    }

    .upload-text {
        font-family: 'Work Sans', sans-serif;
        color: #666;
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .upload-hint {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        font-size: 0.9rem;
    }

    .image-preview {
        display: none;
        margin-top: 1.5rem;
        border-radius: 12px;
        overflow: hidden;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        position: relative;
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

    .image-preview.show {
        display: block;
    }

    .preview-remove {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(231, 76, 60, 0.9);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .preview-remove:hover {
        background: #e74c3c;
        transform: scale(1.1);
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

    /* Responsive */
    @media (max-width: 768px) {
        .form-card {
            padding: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Photo Information -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">
                    <i class="fa-solid fa-image"></i>
                </span>
                Photo Information
            </h3>
            
            <div class="form-group">
                <label for="title" class="form-label">
                    Photo Title <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    class="form-control @error('title') error @enderror"
                    value="{{ old('title') }}"
                    required
                    placeholder="e.g., Beach Wedding Ceremony"
                >
                @error('title')
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control @error('description') error @enderror"
                    placeholder="Describe the photo..."
                >{{ old('description') }}</textarea>
                @error('description')
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
                @enderror
                <div class="form-help">
                    <i class="fa-solid fa-circle-info"></i>
                    Optional description to provide context about the photo
                </div>
            </div>
        </div>

        <!-- Photo Upload -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </span>
                Upload Photo
            </h3>

            <div class="form-group">
                <label for="foto" class="form-label">
                    Photo File <span class="required">*</span>
                </label>
                <div class="image-upload-wrapper">
                    <label for="foto" class="image-upload-area" id="upload-area">
                        <div class="upload-icon">
                            <i class="fa-solid fa-camera-retro"></i>
                        </div>
                        <div class="upload-text">
                            <i class="fa-solid fa-hand-pointer"></i>
                            Click to upload or drag and drop
                        </div>
                        <div class="upload-hint">JPG, PNG, WEBP (Max 2MB)</div>
                    </label>
                    <input 
                        type="file" 
                        id="foto" 
                        name="foto" 
                        class="@error('foto') error @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        style="display: none;"
                        required
                    >
                    <div class="image-preview" id="image-preview">
                        <img src="" alt="Preview" id="preview-img">
                        <button type="button" class="preview-remove" id="remove-preview">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                @error('foto')
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
                @enderror
                <div class="form-help">
                    <i class="fa-solid fa-circle-info"></i>
                    Maximum file size: 2MB. Supported formats: JPEG, PNG, WEBP
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Add to Gallery
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Image Upload Preview
    const fotoInput = document.getElementById('foto');
    const uploadArea = document.getElementById('upload-area');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const removeBtn = document.getElementById('remove-preview');

    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.add('show');
                uploadArea.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function() {
        fotoInput.value = '';
        imagePreview.classList.remove('show');
        uploadArea.style.display = 'block';
    });

    // Drag and Drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fotoInput.files = files;
            const event = new Event('change');
            fotoInput.dispatchEvent(event);
        }
    });
</script>
@endpush