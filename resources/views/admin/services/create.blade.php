@extends('layouts.admin')

@section('title', 'Add New Service')
@section('page-title', 'Add New Service')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .form-container {
        max-width: 1000px;
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

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
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

    .error-message::before {
        content: '⚠️';
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
        padding: 2rem;
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
    }

    .upload-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .upload-text {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        color: #999;
        font-size: 0.85rem;
    }

    .image-preview {
        display: none;
        margin-top: 1rem;
        border-radius: 12px;
        overflow: hidden;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

    .image-preview.show {
        display: block;
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

        .form-grid {
            grid-template-columns: 1fr;
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
    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Service Information -->
        <div class="form-card">
            <h3 class="section-title">
                Service Information
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="name" class="form-label">
                        Service Name <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control @error('name') error @enderror"
                        value="{{ old('name') }}"
                        required
                        placeholder="e.g., Wedding Photography"
                    >
                    @error('name')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="package_id" class="form-label">
                        Package <span class="required">*</span>
                    </label>
                    <select 
                        id="package_id" 
                        name="package_id" 
                        class="form-control @error('package_id') error @enderror"
                        required
                    >
                        <option value="">Select Package</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('package_id')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control @error('description') error @enderror"
                    placeholder="Describe the service..."
                >{{ old('description') }}</textarea>
                @error('description')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Service Image -->
        <div class="form-card">
            <h3 class="section-title">
                Service Image
            </h3>

            <div class="form-group">
                <label for="foto" class="form-label">Upload Image</label>
                <div class="image-upload-wrapper">
                    <label for="foto" class="image-upload-area" id="upload-area">
                        <div class="upload-icon">📸</div>
                        <div class="upload-text">Click to upload or drag and drop</div>
                        <div class="upload-hint">JPG, PNG, WEBP (Max 2MB)</div>
                    </label>
                    <input 
                        type="file" 
                        id="foto" 
                        name="foto" 
                        class="@error('foto') error @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        style="display: none;"
                    >
                    <div class="image-preview" id="image-preview">
                        <img src="" alt="Preview" id="preview-img">
                    </div>
                </div>
                @error('foto')
                <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">Maximum file size: 2MB. Supported formats: JPEG, PNG, WEBP</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                    ← Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    💾 Create Service
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

    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.add('show');
            }
            reader.readAsDataURL(file);
        }
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