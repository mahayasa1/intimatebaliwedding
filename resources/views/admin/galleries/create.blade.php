{{-- resources/views/admin/galleries/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add New Gallery Item')
@section('page-title', 'Add New Gallery Item')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

.form-container { max-width: 900px; margin: 0 auto; }

.form-card{
    background:#fff;
    padding:2.5rem;
    border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,.06);
    border:1px solid #e8e8e8;
    margin-bottom:2rem;
}

.section-title{
    font-family:'Playfair Display',serif;
    font-size:1.5rem;
    font-weight:700;
    color:#1a1a1a;
    margin-bottom:1.5rem;
    display:flex;
    align-items:center;
    gap:.75rem;
    padding-bottom:1rem;
    border-bottom:2px solid #f0f0f0;
}

.section-icon{
    width:40px;
    height:40px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    background:linear-gradient(135deg,#8B7355 0%,#6B5644 100%);
    color:#fff;
    font-size:1.1rem;
}

.form-group{ margin-bottom:1.5rem; }

.form-label{
    display:block;
    margin-bottom:.75rem;
    font-family:'Work Sans',sans-serif;
    font-size:.9rem;
    font-weight:600;
    color:#333;
}

.form-label .required{ color:#e74c3c; }

.form-control{
    width:100%;
    padding:.875rem 1.25rem;
    border:2px solid #e0e0e0;
    border-radius:12px;
    font-size:.95rem;
    font-family:'Work Sans',sans-serif;
    transition:.25s ease;
    box-sizing:border-box;
}

.form-control:focus{
    outline:none;
    border-color:#8B7355;
    box-shadow:0 0 0 4px rgba(139,115,85,.1);
}

textarea.form-control{
    min-height:130px;
    resize:vertical;
    line-height:1.6;
}

.error-message{
    color:#e74c3c;
    font-size:.85rem;
    margin-top:.5rem;
    font-family:'Work Sans',sans-serif;
}

.form-help{
    font-size:.84rem;
    color:#999;
    margin-top:.45rem;
    font-style:italic;
    font-family:'Work Sans',sans-serif;
}

/* toggle */
.type-toggle{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:1rem;
}

.type-option input{
    position:absolute;
    opacity:0;
    pointer-events:none;
}

.type-label{
    display:flex;
    align-items:center;
    gap:1rem;
    padding:1.2rem 1.4rem;
    border:2px solid #e0e0e0;
    border-radius:14px;
    cursor:pointer;
    transition:.25s ease;
}

.type-label:hover{
    border-color:#8B7355;
    background:#faf8f5;
}

.type-option input:checked + .type-label{
    border-color:#8B7355;
    background:linear-gradient(135deg,rgba(139,115,85,.08),rgba(107,86,68,.05));
}

.type-icon{
    width:48px;
    height:48px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:1.25rem;
    flex-shrink:0;
}

.type-icon.photo{
    background:linear-gradient(135deg,#8B7355,#6B5644);
}

.type-icon.video{
    background:linear-gradient(135deg,#e74c3c,#c0392b);
}

.type-info h4{
    margin:0 0 .2rem;
    font-size:1rem;
    color:#111;
}

.type-info p{
    margin:0;
    font-size:.82rem;
    color:#888;
}

/* upload */
.upload-area{
    border:2px dashed #e0e0e0;
    border-radius:12px;
    padding:2rem;
    text-align:center;
    cursor:pointer;
    transition:.25s ease;
    background:#fafafa;
    position:relative;
    overflow:hidden;
}

.upload-area:hover,
.upload-area.drag-over{
    border-color:#8B7355;
    background:#f5f0eb;
}

.upload-area input[type=file]{
    position:absolute;
    inset:0;
    opacity:0;
    width:100%;
    height:100%;
    cursor:pointer;
}

.upload-icon{
    font-size:2.8rem;
    color:#8B7355;
    opacity:.55;
    margin-bottom:1rem;
}

.upload-text{
    color:#666;
    font-size:.95rem;
}

.upload-text strong{
    color:#8B7355;
}

/* progress */
.compress-progress{
    display:none;
    margin-top:.8rem;
    padding:.75rem 1rem;
    background:#f0f7ff;
    border:1px solid #90caf9;
    border-radius:10px;
}

.compress-progress.show{ display:block; }

.progress-label{
    font-size:.82rem;
    color:#1565c0;
    font-family:'Work Sans',sans-serif;
}

.progress-bar-wrap{
    height:6px;
    background:#dde;
    border-radius:10px;
    overflow:hidden;
    margin-top:7px;
}

.progress-bar{
    width:0%;
    height:100%;
    background:linear-gradient(90deg,#8B7355,#D4AF37);
    transition:.2s ease;
}

/* preview */
.image-preview{
    margin-top:1rem;
    display:none;
    position:relative;
}

.image-preview img{
    max-width:100%;
    max-height:320px;
    display:block;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.remove-image{
    position:absolute;
    top:10px;
    right:10px;
    width:32px;
    height:32px;
    border:none;
    border-radius:50%;
    background:#e74c3c;
    color:#fff;
    cursor:pointer;
}

.photos-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(150px,1fr));
    gap:1rem;
    margin-top:1rem;
}

.photo-preview-item{
    position:relative;
    aspect-ratio:1;
    overflow:hidden;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

.photo-preview-item img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.remove-photo{
    position:absolute;
    top:6px;
    right:6px;
    width:28px;
    height:28px;
    border:none;
    border-radius:50%;
    background:#e74c3c;
    color:#fff;
    cursor:pointer;
    font-size:.75rem;
}

/* youtube */
.video-preview{
    display:none;
    margin-top:1rem;
    border-radius:12px;
    overflow:hidden;
    background:#000;
    aspect-ratio:16/9;
}

.video-preview.show{ display:block; }

.video-preview iframe{
    width:100%;
    height:100%;
    border:none;
}

.youtube-info{
    display:none;
    margin-top:.8rem;
    padding:.8rem 1rem;
    border-radius:10px;
    background:#fff3cd;
    border:1px solid #ffd54f;
    color:#7a5c00;
    font-size:.88rem;
    gap:.5rem;
    align-items:center;
}

.youtube-info.show{ display:flex; }

/* action */
.action-section{
    background:#fff;
    padding:2rem;
    border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,.06);
    border:1px solid #e8e8e8;
}

.action-buttons{
    display:flex;
    gap:1rem;
    flex-wrap:wrap;
}

.btn{
    border:none;
    cursor:pointer;
    padding:.875rem 1.75rem;
    border-radius:12px;
    font-weight:600;
    text-decoration:none;
    font-family:'Work Sans',sans-serif;
    transition:.25s ease;
}

.btn-secondary{
    background:linear-gradient(135deg,#95a5a6,#7f8c8d);
    color:#fff;
}

.btn-primary{
    background:linear-gradient(135deg,#8B7355,#6B5644);
    color:#fff;
}

.btn:hover{
    transform:translateY(-2px);
}

@media(max-width:768px){
    .form-card{ padding:1.5rem; }
    .type-toggle{ grid-template-columns:1fr; }
    .action-buttons{ flex-direction:column; }
    .btn{ width:100%; text-align:center; }
    .photos-grid{ grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); }
}
</style>
@endpush

@section('content')
<div class="form-container">

<form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" id="galleryForm">
@csrf

{{-- TYPE --}}
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
                <div class="type-info">
                    <h4>Foto</h4>
                    <p>Upload gambar gallery</p>
                </div>
            </label>
        </div>

        <div class="type-option">
            <input type="radio" id="type_video" name="type" value="video">
            <label for="type_video" class="type-label">
                <div class="type-icon video"><i class="fa-brands fa-youtube"></i></div>
                <div class="type-info">
                    <h4>YouTube</h4>
                    <p>Embed video</p>
                </div>
            </label>
        </div>
    </div>
</div>

{{-- INFO --}}
<div class="form-card">
    <h3 class="section-title">
        <span class="section-icon"><i class="fa-solid fa-circle-info"></i></span>
        Informasi
    </h3>

    <div class="form-group">
        <label class="form-label">Judul <span class="required">*</span></label>
        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
    </div>

    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
        <label class="form-label">Kategori</label>
        <input type="text" name="category" class="form-control" value="{{ old('category') }}">
    </div>

    <div class="form-group">
        <label class="form-label">Urutan</label>
        <input type="number" name="order" class="form-control" value="{{ old('order',0) }}">
    </div>
</div>

{{-- VIDEO --}}
<div class="form-card" id="videoSection" style="display:none;">
    <h3 class="section-title">
        <span class="section-icon" style="background:linear-gradient(135deg,#e74c3c,#c0392b)">
            <i class="fa-brands fa-youtube"></i>
        </span>
        URL Video
    </h3>

    <div class="form-group">
        <label class="form-label">YouTube URL <span class="required">*</span></label>
        <input type="url" id="video_url" name="video_url" class="form-control"
               placeholder="https://youtube.com/watch?v=...">
    </div>

    <div class="youtube-info" id="youtubeInfo">
        <i class="fa-brands fa-youtube"></i>
        <span>Video ditemukan</span>
    </div>

    <div class="video-preview" id="videoPreview">
        <iframe id="videoIframe" allowfullscreen></iframe>
    </div>
</div>

{{-- PHOTO --}}
<div id="photoWrap">

<div class="form-card">
    <h3 class="section-title">
        <span class="section-icon"><i class="fa-solid fa-star"></i></span>
        Foto Utama
    </h3>

    <div class="form-group">

        <div class="upload-area" id="fotoArea">
            <input type="file" id="fotoInput" name="foto"
                   accept="image/jpeg,image/png,image/jpg,image/webp">
            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="upload-text">
                <strong>Click to upload</strong><br>
                PNG, JPG, WEBP — dikompres otomatis
            </div>
        </div>

        <div class="compress-progress" id="fotoProg">
            <div class="progress-label" id="fotoLabel">Mengompres...</div>
            <div class="progress-bar-wrap">
                <div class="progress-bar" id="fotoBar"></div>
            </div>
        </div>

        <div class="image-preview" id="fotoPreview">
            <img src="">
            <button type="button" class="remove-image" onclick="removeMainImage()">
                <i class="fa fa-times"></i>
            </button>
        </div>

    </div>
</div>

<div class="form-card">
    <h3 class="section-title">
        <span class="section-icon"><i class="fa-solid fa-images"></i></span>
        Foto Tambahan
    </h3>

    <div class="form-group">

        <div class="upload-area" id="photosArea">
            <input type="file" id="photosInput" name="photos[]"
                   multiple
                   accept="image/jpeg,image/png,image/jpg,image/webp">
            <div class="upload-icon"><i class="fas fa-images"></i></div>
            <div class="upload-text">
                <strong>Click to upload</strong><br>
                Bisa pilih banyak foto — dikompres otomatis
            </div>
        </div>

        <div class="compress-progress" id="photosProg">
            <div class="progress-label" id="photosLabel">Mengompres...</div>
            <div class="progress-bar-wrap">
                <div class="progress-bar" id="photosBar"></div>
            </div>
        </div>

        <div id="photosGrid" class="photos-grid" style="display:none;"></div>

    </div>
</div>

</div>

{{-- ACTION --}}
<div class="action-section">
    <div class="action-buttons">
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
            ← Cancel
        </a>

        <button type="submit" class="btn btn-primary" id="submitBtn">
            Save Gallery
        </button>
    </div>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

let photoFiles = [];

const form         = document.getElementById('galleryForm');
const submitBtn    = document.getElementById('submitBtn');

const fotoInput    = document.getElementById('fotoInput');
const photosInput  = document.getElementById('photosInput');

const fotoPreview  = document.getElementById('fotoPreview');
const photosGrid   = document.getElementById('photosGrid');

const photoWrap    = document.getElementById('photoWrap');
const videoSection = document.getElementById('videoSection');
const videoInput   = document.getElementById('video_url');

const iframe       = document.getElementById('videoIframe');
const previewBox   = document.getElementById('videoPreview');
const infoBox      = document.getElementById('youtubeInfo');

/* ---------- TYPE TOGGLE ---------- */
document.querySelectorAll('input[name=type]').forEach(radio=>{
    radio.addEventListener('change',function(){
        if(this.value === 'video'){
            photoWrap.style.display = 'none';
            videoSection.style.display = 'block';
            fotoInput.required = false;
            videoInput.required = true;
        }else{
            photoWrap.style.display = 'block';
            videoSection.style.display = 'none';
            fotoInput.required = true;
            videoInput.required = false;
        }
    });
});

/* ---------- MAIN IMAGE ---------- */
fotoInput.addEventListener('change', async function () {

    const raw = this.files[0];
    if(!raw) return;

    showProgress('foto',40,'Mengompres...',false);

    const result = await ImageCompressor.compress(raw,{
        maxWidth:1920,
        maxHeight:1920,
        quality:0.82
    });

    ImageCompressor.replaceFiles(fotoInput,[result]);

    showProgress('foto',100,'✓ Siap',true);

    const reader = new FileReader();
    reader.onload = e=>{
        fotoPreview.style.display = 'block';
        fotoPreview.querySelector('img').src = e.target.result;
    };
    reader.readAsDataURL(result);
});

/* ---------- MULTIPLE ---------- */
photosInput.addEventListener('change', async function () {

    const raw = Array.from(this.files);
    if(!raw.length) return;

    showProgress('photos',0,'Mengompres 0 / '+raw.length,false);

    const compressed = [];

    for(let i=0;i<raw.length;i++){

        const result = await ImageCompressor.compress(raw[i],{
            maxWidth:1920,
            maxHeight:1920,
            quality:0.82
        });

        compressed.push(result);

        const pct = Math.round(((i+1)/raw.length)*100);

        showProgress(
            'photos',
            pct,
            'Mengompres '+(i+1)+' / '+raw.length,
            false
        );
    }

    photoFiles = [...photoFiles, ...compressed];

    ImageCompressor.replaceFiles(photosInput, photoFiles);

    showProgress(
        'photos',
        100,
        '✓ '+photoFiles.length+' foto siap',
        true
    );

    renderPhotos();
});

/* ---------- RENDER GRID ---------- */
window.removePhoto = function(index){
    photoFiles.splice(index,1);
    ImageCompressor.replaceFiles(photosInput, photoFiles);
    renderPhotos();
}

function renderPhotos(){

    photosGrid.innerHTML = '';

    if(!photoFiles.length){
        photosGrid.style.display = 'none';
        return;
    }

    photosGrid.style.display = 'grid';

    photoFiles.forEach((file,index)=>{

        const reader = new FileReader();

        reader.onload = e=>{

            const div = document.createElement('div');
            div.className = 'photo-preview-item';

            div.innerHTML = `
                <img src="${e.target.result}">
                <button type="button" class="remove-photo"
                        onclick="removePhoto(${index})">
                    <i class="fa fa-times"></i>
                </button>
            `;

            photosGrid.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}

/* ---------- REMOVE MAIN ---------- */
window.removeMainImage = function(){
    fotoInput.value = '';
    fotoPreview.style.display = 'none';
}

/* ---------- PROGRESS ---------- */
function showProgress(type,pct,text,done){

    const prog  = document.getElementById(type+'Prog');
    const bar   = document.getElementById(type+'Bar');
    const label = document.getElementById(type+'Label');

    prog.classList.add('show');
    bar.style.width = pct + '%';
    label.textContent = text;

    if(done){
        bar.style.background = '#27ae60';
        label.style.color = '#155724';

        setTimeout(()=>{
            prog.classList.remove('show');
            bar.style.background = '';
            label.style.color = '';
        },2200);
    }
}

/* ---------- YOUTUBE ---------- */
function getYoutubeId(url){
    const reg = /(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(reg);
    return match ? match[1] : null;
}

videoInput.addEventListener('input',function(){

    const id = getYoutubeId(this.value);

    if(id){
        iframe.src = 'https://www.youtube.com/embed/'+id+'?rel=0';
        previewBox.classList.add('show');
        infoBox.classList.add('show');
    }else{
        iframe.src = '';
        previewBox.classList.remove('show');
        infoBox.classList.remove('show');
    }
});

/* ---------- SUBMIT ---------- */
form.addEventListener('submit',function(e){

    const selected = document.querySelector('input[name=type]:checked').value;

    if(selected === 'video'){
        const url = videoInput.value.trim();

        if(url === ''){
            e.preventDefault();
            alert('Masukkan URL YouTube');
            return;
        }

        if(!getYoutubeId(url)){
            e.preventDefault();
            alert('URL YouTube tidak valid');
            return;
        }
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Saving...';
});

});
</script>
@endpush