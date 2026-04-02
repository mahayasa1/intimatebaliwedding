@extends('layouts.admin')

@section('title', 'Add New Package')
@section('page-title', 'Add New Package')

@push('styles')
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

    /* Image Upload Styling */
    .image-upload-wrapper {
        position: relative;
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .image-upload-wrapper:hover {
        border-color: #8B7355;
        background: #f5f5f5;
    }

    .image-upload-wrapper input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        font-size: 3rem;
        color: #8B7355;
        margin-bottom: 1rem;
    }

    .upload-text {
        color: #666;
        font-size: 0.95rem;
    }

    .upload-text strong {
        color: #8B7355;
    }

    .image-preview {
        margin-top: 1rem;
        display: none;
        position: relative;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .remove-image {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .remove-image:hover {
        background: #c0392b;
        transform: scale(1.1);
    }

    /* Multiple Photos Grid */
    .photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .photo-preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .photo-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-photo {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .remove-photo:hover {
        background: #c0392b;
        transform: scale(1.1);
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

        .photos-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Package Information -->
        <div class="form-card">
            <h3 class="section-title">
                Package Information
            </h3>
            
            <div class="form-group">
                <label for="name" class="form-label">
                    Package Name <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') error @enderror"
                    value="{{ old('name') }}"
                    required
                    placeholder="e.g., Beach Wedding Package"
                >
                @error('name')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image" class="form-label">
                    Main Package Image <span class="required">*</span>
                </label>
                <div class="image-upload-wrapper">
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*"
                        required
                        onchange="previewImage(event)"
                    >
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>PNG, JPG, WEBP (max. 20MB)</small>
                    </div>
                </div>
                <div class="image-preview" id="imagePreview">
                    <button type="button" class="remove-image" onclick="removeImage()">
                        <i class="fas fa-times"></i>
                    </button>
                    <img id="preview" src="" alt="Preview">
                </div>
                @error('image')
                <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">Upload a high-quality main image for this package</div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control @error('description') error @enderror"
                    placeholder="Describe the package and what it includes..."
                >{{ old('description') }}</textarea>
                @error('description')
                <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">Provide details about what's included in this package</div>
            </div>
        </div>

        <!-- Gallery Photos -->
        <div class="form-card">
            <h3 class="section-title">
                Package Gallery Photos
            </h3>

            <div class="form-group">
                <label for="photos" class="form-label">
                    Additional Photos
                </label>
                <div class="image-upload-wrapper">
                    <input 
                        type="file" 
                        id="photos" 
                        name="photos[]" 
                        accept="image/*"
                        multiple
                        onchange="previewMultipleImages(event)"
                    >
                    <div class="upload-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>PNG, JPG, WEBP (max. 20MB each) - Multiple files allowed</small>
                    </div>
                </div>
                @error('photos.*')
                <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">Upload multiple photos to showcase this package</div>
            </div>

            <div id="photosGrid" class="photos-grid" style="display: none;"></div>
        </div>

        <!-- Actions -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                    ← Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Create Package
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Preview main image
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Remove main image
function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('preview').src = '';
}

// Resize image sebelum preview & upload
async function resizeImage(file, maxWidth = 1920) {
    return new Promise((resolve) => {
        const img = new Image();
        const url = URL.createObjectURL(file);
        img.onload = () => {
            URL.revokeObjectURL(url);
            if (img.width <= maxWidth) {
                resolve(file); // tidak perlu resize
                return;
            }
            const canvas = document.createElement('canvas');
            const ratio = maxWidth / img.width;
            canvas.width = maxWidth;
            canvas.height = img.height * ratio;
            canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
            canvas.toBlob((blob) => {
                resolve(new File([blob], file.name, { type: 'image/jpeg' }));
            }, 'image/jpeg', 0.85);
        };
        img.src = url;
    });
}

// Preview multiple photos (dengan resize otomatis)
let photoFiles = [];

async function previewMultipleImages(event) {
    const files = Array.from(event.target.files);

    // Resize semua file dulu
    const resized = await Promise.all(files.map(f => resizeImage(f)));
    photoFiles = [...photoFiles, ...resized];

    // Update file input dengan file yang sudah di-resize
    const dataTransfer = new DataTransfer();
    photoFiles.forEach(file => dataTransfer.items.add(file));
    document.getElementById('photos').files = dataTransfer.files;

    displayPhotos();
}

function displayPhotos() {
    const grid = document.getElementById('photosGrid');
    grid.innerHTML = '';
    
    if (photoFiles.length > 0) {
        grid.style.display = 'grid';
        
        photoFiles.forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'photo-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Photo ${index + 1}">
                    <button type="button" class="remove-photo" onclick="removePhoto(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                grid.appendChild(div);
            }
            
            reader.readAsDataURL(file);
        });
    } else {
        grid.style.display = 'none';
    }
}

function removePhoto(index) {
    photoFiles.splice(index, 1);
    
    // Update file input
    const dataTransfer = new DataTransfer();
    photoFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    document.getElementById('photos').files = dataTransfer.files;
    
    displayPhotos();
}
</script>
@endsection