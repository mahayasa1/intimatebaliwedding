@extends('layouts.admin')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

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

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
        line-height: 1.6;
    }

    /* Current Image Display */
    .current-image {
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    .current-image img {
        max-width: 300px;
        max-height: 200px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .current-image-label {
        display: block;
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 0.5rem;
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

    /* Existing Photos Grid */
    .existing-photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .existing-photo-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .existing-photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-existing-photo {
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
        z-index: 10;
    }

    .remove-existing-photo:hover {
        background: #c0392b;
        transform: scale(1.1);
    }

    /* New Photos Grid */
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

        .existing-photos-grid,
        .photos-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
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
                    class="form-control"
                    value="{{ old('name', $package->name) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="image" class="form-label">
                    Main Package Image
                </label>
                
                @if($package->image)
                <div class="current-image">
                    <span class="current-image-label">Current Image:</span>
                    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}">
                </div>
                @endif

                <div class="image-upload-wrapper">
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*"
                    >
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>PNG, JPG, WEBP (max. 20MB) - Leave empty to keep current image</small>
                    </div>
                </div>
                <div class="form-help">Upload a new image to replace the current one</div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control"
                >{{ old('description', $package->description) }}</textarea>
            </div>
        </div>

        <!-- Gallery Photos -->
        <div class="form-card">
            <h3 class="section-title">
                Package Gallery Photos
            </h3>

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
                <label for="photos" class="form-label">
                    Add New Photos
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
                <div class="form-help">Upload additional photos to add to the gallery</div>
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
                    Update Package
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Track removed photos
let removedPhotos = [];

// Remove existing photo
function removeExistingPhoto(photoPath, button) {
    if (confirm('Are you sure you want to remove this photo?')) {
        // Add to removed list
        removedPhotos.push(photoPath);
        document.getElementById('removed_photos').value = JSON.stringify(removedPhotos);
        
        // Remove from UI
        button.closest('.existing-photo-item').remove();
        
        // Check if grid is empty
        const grid = document.getElementById('existingPhotosGrid');
        if (grid && grid.children.length === 0) {
            grid.parentElement.remove();
        }
    }
}

// Preview new photos
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