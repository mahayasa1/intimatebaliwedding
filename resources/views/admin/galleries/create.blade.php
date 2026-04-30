@extends('layouts.admin')

@section('title', 'Add New Gallery Item')
@section('page-title', 'Add New Gallery Item')

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

    textarea.form-control { min-height: 120px; resize: vertical; line-height: 1.6; }

    .error-message {
        font-family: 'Work Sans', sans-serif; color: #e74c3c; font-size: 0.85rem;
        margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;
    }

    .form-help {
        font-family: 'Work Sans', sans-serif; color: #999;
        font-size: 0.85rem; margin-top: 0.5rem; font-style: italic;
    }

    /* Type toggle */
    .type-toggle { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; }
    .type-option { position: relative; }
    .type-option input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }

    .type-label {
        display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem;
        border: 2px solid #e0e0e0; border-radius: 12px; cursor: pointer;
        transition: all 0.3s ease; background: white; font-family: 'Work Sans', sans-serif;
    }

    .type-label:hover { border-color: #8B7355; background: #faf8f5; }
    .type-option input[type="radio"]:checked + .type-label {
        border-color: #8B7355; background: linear-gradient(135deg, rgba(139,115,85,0.08), rgba(107,86,68,0.06));
    }

    .type-icon {
        width: 46px; height: 46px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: white; flex-shrink: 0;
    }

    .type-icon.photo { background: linear-gradient(135deg, #8B7355, #6B5644); }
    .type-icon.video { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .type-info h4 { font-weight: 700; color: #1a1a1a; margin: 0 0 0.2rem; font-size: 1rem; }
    .type-info p { font-size: 0.8rem; color: #888; margin: 0; }

    /* Video preview */
    .video-preview {
        display: none; margin-top: 1rem; border-radius: 12px; overflow: hidden;
        aspect-ratio: 16/9; background: #000;
    }
    .video-preview.show { display: block; }
    .video-preview iframe { width: 100%; height: 100%; border: none; }

    .youtube-info {
        display: none; margin-top: 0.75rem; padding: 0.75rem 1rem;
        background: #fff3cd; border: 1px solid #ffd54f; border-radius: 8px;
        font-family: 'Work Sans', sans-serif; font-size: 0.88rem; color: #7a5c00;
        align-items: center; gap: 0.5rem;
    }
    .youtube-info.show { display: flex; }

    /* Upload area */
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

    /* Image preview */
    .image-preview { display: none; margin-top: 1rem; position: relative; }
    .image-preview img { max-width: 100%; max-height: 300px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: block; }
    .image-preview.show { display: block; }

    /* Photos grid */
    .photos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem; }
    .photo-preview-item { position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .photo-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }

    /* Server badge */
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
        gap: 0.5rem; padding: 0.875rem 1.75rem; border-radius: 12px;
        text-decoration: none; font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none; cursor: pointer; font-size: 0.95rem;
    }
    .btn-secondary { background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; }
    .btn-secondary:hover { transform: translateY(-2px); }
    .btn-primary { background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%); color: white; }
    .btn-primary:hover { transform: translateY(-2px); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    @media (max-width: 768px) {
        .form-card { padding: 1.5rem; }
        .type-toggle { grid-template-columns: 1fr; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" id="galleryForm">
        @csrf

        {{-- TYPE SELECTOR --}}
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon"><i class="fa-solid fa-photo-film"></i></span>
                Jenis Konten
            </h3>
            <div class="type-toggle">
                <div class="type-option">
                    <input type="radio" id="type_photo" name="type" value="photo" checked>
                    <label for="type_photo" class="type-label">
                        <div class="type-icon photo"><i class="fa-solid fa-image"></i></div>
                        <div class="type-info"><h4>Foto</h4><p>Upload gambar ke gallery</p></div>
                    </label>
                </div>
                <div class="type-option">
                    <input type="radio" id="type_video" name="type" value="video">
                    <label for="type_video" class="type-label">
                        <div class="type-icon video"><i class="fa-brands fa-youtube"></i></div>
                        <div class="type-info"><h4>Video YouTube</h4><p>Embed video dari YouTube</p></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- INFO DASAR --}}
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon"><i class="fa-solid fa-circle-info"></i></span>
                Informasi
            </h3>
            <div class="form-group">
                <label for="title" class="form-label">Judul <span class="required">*</span></label>
                <input type="text" id="title" name="title"
                    class="form-control @error('title') error @enderror"
                    value="{{ old('title') }}" required placeholder="e.g., Beach Wedding Ceremony">
                @error('title')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description"
                    class="form-control @error('description') error @enderror"
                    placeholder="Deskripsi singkat...">{{ old('description') }}</textarea>
                @error('description')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="category" class="form-label">Kategori</label>
                <input type="text" id="category" name="category"
                    class="form-control @error('category') error @enderror"
                    value="{{ old('category') }}" placeholder="e.g., Hero, Beach, Garden">
                @error('category')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="order" class="form-label">Urutan Tampil</label>
                <input type="number" id="order" name="order"
                    class="form-control @error('order') error @enderror"
                    value="{{ old('order', 0) }}" min="0">
                <div class="form-help">Angka lebih kecil = tampil lebih dulu</div>
            </div>
        </div>

        {{-- VIDEO SECTION --}}
        <div class="form-card" id="videoSection" style="display:none;">
            <h3 class="section-title">
                <span class="section-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <i class="fa-brands fa-youtube"></i>
                </span>
                URL Video YouTube
            </h3>
            <div class="form-group">
                <label for="video_url" class="form-label">URL YouTube <span class="required">*</span></label>
                <input type="url" id="video_url" name="video_url"
                    class="form-control @error('video_url') error @enderror"
                    value="{{ old('video_url') }}"
                    placeholder="https://www.youtube.com/watch?v=..."
                    oninput="previewYoutube(this.value)">
                @error('video_url')<div class="error-message">{{ $message }}</div>@enderror
                <div class="form-help">Format: youtube.com/watch?v=..., youtu.be/..., youtube.com/shorts/...</div>
            </div>
            <div class="youtube-info" id="youtubeInfo">
                <i class="fa-brands fa-youtube" style="font-size:1.2rem; flex-shrink:0;"></i>
                <span id="youtubeInfoText">Video ditemukan</span>
            </div>
            <div class="video-preview" id="videoPreview">
                <iframe id="videoIframe" src="" allowfullscreen
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            </div>
        </div>

        {{-- FOTO UTAMA --}}
        <div class="form-card" id="photoSection">
            <h3 class="section-title">
                <span class="section-icon"><i class="fa-solid fa-star"></i></span>
                Foto Utama <span style="font-size:0.8rem;color:#999;font-weight:400;">(Cover / Grid)</span>
            </h3>
            <div class="form-group">
                <label class="form-label">Upload Foto Utama <span class="required">*</span></label>
                <div class="upload-area" id="mainImageArea">
                    <input type="file" id="mainImageInput" name="foto"
                        accept="image/jpeg,image/png,image/jpg,image/webp" required>
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> atau drag & drop</div>
                    <div style="font-family:'Work Sans',sans-serif;color:#aaa;font-size:0.8rem;margin-top:0.25rem;">
                        JPG, PNG, WEBP · Max 30MB
                    </div>
                </div>
                <div class="compress-progress" id="mainImgProgress">
                    <div class="progress-label" id="mainImgLabel">Mengompres…</div>
                    <div class="progress-bar-wrap"><div class="progress-bar" id="mainImgBar"></div></div>
                </div>
                <div class="image-preview" id="mainImgPreview">
                    <img src="" alt="Preview" id="mainImgPreviewImg">
                </div>
                @error('foto')<div class="error-message">{{ $message }}</div>@enderror
                <div class="server-badge" style="margin-top:0.75rem;">
                    <i class="fas fa-server"></i>
                    Dikompres 2× — client + server (WebP)
                </div>
            </div>
        </div>

        {{-- FOTO TAMBAHAN --}}
        <div class="form-card" id="photosSection">
            <h3 class="section-title">
                <span class="section-icon"><i class="fa-solid fa-images"></i></span>
                Foto Tambahan <span style="font-size:0.8rem;color:#999;font-weight:400;">(Opsional)</span>
            </h3>
            <div class="form-group">
                <label class="form-label">Upload Foto Tambahan</label>
                <div class="upload-area" id="photosArea">
                    <input type="file" id="photosInput" name="photos[]"
                        accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
                    <div class="upload-icon"><i class="fas fa-images"></i></div>
                    <div class="upload-text"><strong>Click to upload</strong> multiple foto</div>
                    <div style="font-family:'Work Sans',sans-serif;color:#aaa;font-size:0.8rem;margin-top:0.25rem;">
                        JPG, PNG, WEBP · Bisa pilih banyak
                    </div>
                </div>
                <div class="compress-progress" id="photosProgress">
                    <div class="progress-label" id="photosLabel">Mengompres…</div>
                    <div class="progress-bar-wrap"><div class="progress-bar" id="photosBar"></div></div>
                </div>
                <div class="photos-grid" id="photosGrid" style="display:none;"></div>
                @error('photos.*')<div class="error-message">{{ $message }}</div>@enderror
                <div class="server-badge" style="margin-top:0.75rem;">
                    <i class="fas fa-server"></i>
                    Semua foto dikompres 2× — client + server (WebP)
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan ke Gallery
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form          = document.getElementById('galleryForm');
    const submitBtn     = document.getElementById('submitBtn');
    const typeRadios    = document.querySelectorAll('input[name="type"]');
    const videoSection  = document.getElementById('videoSection');
    const photoSection  = document.getElementById('photoSection');
    const photosSection = document.getElementById('photosSection');
    const videoUrlInput = document.getElementById('video_url');

    // ── TYPE TOGGLE ──────────────────────────────────────────────
    function setType(type) {
        if (type === 'video') {
            videoSection.style.display  = 'block';
            photoSection.style.display  = 'none';
            photosSection.style.display = 'none';
            document.getElementById('mainImageInput').required = false;
            if (videoUrlInput) videoUrlInput.required = true;
        } else {
            videoSection.style.display  = 'none';
            photoSection.style.display  = 'block';
            photosSection.style.display = 'block';
            document.getElementById('mainImageInput').required = true;
            if (videoUrlInput) videoUrlInput.required = false;
        }
    }

    typeRadios.forEach(r => r.addEventListener('change', function () { setType(this.value); }));
    const checked = document.querySelector('input[name="type"]:checked');
    setType(checked ? checked.value : 'photo');

    // ── YOUTUBE PREVIEW ──────────────────────────────────────────
    function getYoutubeId(url) {
        if (!url) return null;
        const reg = /(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const m   = url.match(reg);
        return m ? m[1] : null;
    }

    window.previewYoutube = function (url) {
        const id = getYoutubeId(url);
        if (id) {
            document.getElementById('videoIframe').src = 'https://www.youtube.com/embed/' + id + '?rel=0';
            document.getElementById('videoPreview').classList.add('show');
            document.getElementById('youtubeInfo').classList.add('show');
        } else {
            document.getElementById('videoIframe').src = '';
            document.getElementById('videoPreview').classList.remove('show');
            document.getElementById('youtubeInfo').classList.remove('show');
        }
    };

    videoUrlInput?.addEventListener('input', function () { previewYoutube(this.value); });

    // ── PROGRESS HELPER ──────────────────────────────────────────
    function showProgress(barId, labelId, progressId, pct, text, done) {
        const prog  = document.getElementById(progressId);
        const bar   = document.getElementById(barId);
        const label = document.getElementById(labelId);
        if (!prog) return;
        prog.classList.add('show');
        bar.style.width = pct + '%';
        label.textContent = text;
        if (done) {
            bar.style.background = '#27ae60';
            label.style.color    = '#155724';
            setTimeout(() => {
                prog.classList.remove('show');
                bar.style.background = '';
                label.style.color    = '';
            }, 2500);
        }
    }

    // ── MAIN IMAGE — client compress ─────────────────────────────
    const mainInput   = document.getElementById('mainImageInput');
    const mainArea    = document.getElementById('mainImageArea');
    const mainPreview = document.getElementById('mainImgPreview');
    const mainPrevImg = document.getElementById('mainImgPreviewImg');

    mainInput.addEventListener('change', async function () {
        const raw = this.files[0];
        if (!raw) return;

        showProgress('mainImgBar', 'mainImgLabel', 'mainImgProgress', 40, 'Mengompres…', false);
        mainArea.style.display = 'none';

        const result = await ImageCompressor.compress(raw, { maxWidth: 1920, maxHeight: 1920, quality: 0.82 });
        ImageCompressor.replaceFiles(mainInput, [result]);

        showProgress('mainImgBar', 'mainImgLabel', 'mainImgProgress', 100, '✓ Siap dikirim', true);

        const reader = new FileReader();
        reader.onload = e => {
            mainPrevImg.src = e.target.result;
            mainPreview.classList.add('show');
        };
        reader.readAsDataURL(result);
    });

    // Drag & drop main
    mainArea.addEventListener('dragover',  e => { e.preventDefault(); mainArea.classList.add('drag-over'); });
    mainArea.addEventListener('dragleave', () => mainArea.classList.remove('drag-over'));
    mainArea.addEventListener('drop', function (e) {
        e.preventDefault();
        mainArea.classList.remove('drag-over');
        const dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
        mainInput.files = dt.files;
        mainInput.dispatchEvent(new Event('change'));
    });

    // ── ADDITIONAL PHOTOS — client compress ──────────────────────
    const photosInput = document.getElementById('photosInput');
    const photosArea  = document.getElementById('photosArea');
    const photosGrid  = document.getElementById('photosGrid');
    let   photoFiles  = [];

    photosInput.addEventListener('change', async function () {
        const raw = Array.from(this.files);
        if (!raw.length) return;

        showProgress('photosBar', 'photosLabel', 'photosProgress', 0, `Mengompres 0 / ${raw.length}…`, false);

        const compressed = [];
        for (let i = 0; i < raw.length; i++) {
            const result = await ImageCompressor.compress(raw[i], { maxWidth: 1920, maxHeight: 1920, quality: 0.82 });
            compressed.push(result);
            showProgress('photosBar', 'photosLabel', 'photosProgress',
                Math.round(((i + 1) / raw.length) * 100),
                `Mengompres ${i + 1} / ${raw.length}…`, false);
        }

        photoFiles = [...photoFiles, ...compressed];
        ImageCompressor.replaceFiles(photosInput, photoFiles);

        showProgress('photosBar', 'photosLabel', 'photosProgress', 100, `✓ ${photoFiles.length} foto siap`, true);
        renderPhotoPreviews();
    });

    function renderPhotoPreviews() {
        photosGrid.innerHTML = '';
        if (!photoFiles.length) { photosGrid.style.display = 'none'; return; }
        photosGrid.style.display = 'grid';
        photoFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'photo-preview-item';
                div.innerHTML = `<img src="${e.target.result}" alt="Photo ${i + 1}">`;
                photosGrid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    // Drag & drop photos
    photosArea.addEventListener('dragover',  e => { e.preventDefault(); photosArea.classList.add('drag-over'); });
    photosArea.addEventListener('dragleave', () => photosArea.classList.remove('drag-over'));
    photosArea.addEventListener('drop', function (e) {
        e.preventDefault();
        photosArea.classList.remove('drag-over');
        const dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
        photosInput.files = dt.files;
        photosInput.dispatchEvent(new Event('change'));
    });

    // ── FORM SUBMIT ──────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        const selected = document.querySelector('input[name="type"]:checked').value;

        if (selected === 'video') {
            const url = videoUrlInput.value.trim();
            if (!url) {
                e.preventDefault();
                alert('Masukkan URL YouTube.');
                videoUrlInput.focus();
                return;
            }
            if (!getYoutubeId(url)) {
                e.preventDefault();
                alert('URL YouTube tidak valid.');
                videoUrlInput.focus();
                return;
            }
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
    });

});
</script>
@endpush