{{-- resources/views/admin/galleries/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Gallery Item')
@section('page-title', 'Edit Gallery Item')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

.form-container { max-width:900px; margin:0 auto; }

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
    background:linear-gradient(135deg,#8B7355,#6B5644);
    color:#fff;
    border-radius:10px;
    font-size:1.2rem;
}

.form-group{ margin-bottom:1.5rem; }

.form-label{
    display:block;
    color:#333;
    font-weight:600;
    margin-bottom:.75rem;
    font-size:.9rem;
    font-family:'Work Sans',sans-serif;
}

.form-label .required{ color:#e74c3c; }

.form-control{
    width:100%;
    padding:.875rem 1.25rem;
    border:2px solid #e0e0e0;
    border-radius:12px;
    font-size:.95rem;
    background:#fff;
    transition:.25s ease;
    box-sizing:border-box;
    font-family:'Work Sans',sans-serif;
}

.form-control:focus{
    outline:none;
    border-color:#8B7355;
    box-shadow:0 0 0 4px rgba(139,115,85,.1);
}

textarea.form-control{
    min-height:120px;
    resize:vertical;
    line-height:1.6;
}

.error-message{
    color:#e74c3c;
    font-size:.85rem;
    margin-top:.5rem;
}

.form-help{
    color:#999;
    font-size:.84rem;
    margin-top:.45rem;
    font-style:italic;
}

/* toggle */
.type-toggle{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:1rem;
    margin-bottom:2rem;
}

.type-option{ position:relative; }

.type-option input{
    position:absolute;
    opacity:0;
    pointer-events:none;
}

.type-label{
    display:flex;
    align-items:center;
    gap:1rem;
    padding:1.25rem 1.5rem;
    border:2px solid #e0e0e0;
    border-radius:12px;
    cursor:pointer;
    transition:.25s ease;
}

.type-label:hover{
    border-color:#8B7355;
    background:#faf8f5;
}

.type-option input:checked + .type-label{
    border-color:#8B7355;
    background:linear-gradient(135deg,rgba(139,115,85,.08),rgba(107,86,68,.06));
}

.type-icon{
    width:46px;
    height:46px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:1.35rem;
}

.type-icon.photo{ background:linear-gradient(135deg,#8B7355,#6B5644); }
.type-icon.video{ background:linear-gradient(135deg,#e74c3c,#c0392b); }

.type-info h4{ margin:0 0 .2rem; }
.type-info p{ margin:0; font-size:.8rem; color:#888; }

/* upload */
.upload-area{
    border:2px dashed #e0e0e0;
    border-radius:12px;
    padding:1.75rem 2rem;
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
    cursor:pointer;
    width:100%;
    height:100%;
}

.upload-icon{
    font-size:2.5rem;
    color:#8B7355;
    opacity:.45;
    margin-bottom:.5rem;
}

.upload-text strong{ color:#8B7355; }

/* current image */
.current-image-box{
    margin-bottom:1rem;
    padding:1.25rem;
    background:#f8f9fa;
    border-radius:12px;
    border:1px solid #e8e8e8;
}

.current-image-box span{
    display:block;
    font-size:.82rem;
    color:#666;
    margin-bottom:.75rem;
    font-weight:600;
}

.current-image-box img{
    max-width:300px;
    max-height:220px;
    border-radius:10px;
    display:block;
    object-fit:cover;
}

/* progress */
.compress-progress{
    display:none;
    margin-top:.75rem;
    padding:.75rem 1rem;
    background:#f0f7ff;
    border:1px solid #90caf9;
    border-radius:8px;
}

.compress-progress.show{ display:block; }

.progress-label{
    font-size:.82rem;
    color:#1565c0;
}

.progress-bar-wrap{
    background:#dde;
    border-radius:4px;
    height:6px;
    overflow:hidden;
    margin-top:6px;
}

.progress-bar{
    height:100%;
    width:0%;
    background:linear-gradient(90deg,#8B7355,#D4AF37);
}

/* preview */
.main-preview-wrapper{
    display:none;
    margin-top:1rem;
    position:relative;
    max-width:350px;
}

.main-preview-wrapper.show{ display:block; }

.main-preview-wrapper img{
    width:100%;
    border-radius:10px;
    display:block;
}

.remove-new-main{
    position:absolute;
    top:8px;
    right:8px;
    width:30px;
    height:30px;
    border:none;
    border-radius:50%;
    background:#e74c3c;
    color:#fff;
    cursor:pointer;
}

/* grid */
.photos-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(130px,1fr));
    gap:1rem;
    margin-top:1rem;
}

.photo-item{
    position:relative;
    aspect-ratio:1;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

.photo-item img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.remove-photo{
    position:absolute;
    top:5px;
    right:5px;
    width:28px;
    height:28px;
    border:none;
    border-radius:50%;
    background:#e74c3c;
    color:#fff;
    cursor:pointer;
    font-size:.8rem;
}

/* youtube */
.video-preview{
    display:none;
    margin-top:1rem;
    border-radius:12px;
    overflow:hidden;
    aspect-ratio:16/9;
    background:#000;
}

.video-preview.show{ display:block; }

.video-preview iframe{
    width:100%;
    height:100%;
    border:none;
}

.youtube-info{
    display:none;
    margin-top:.75rem;
    padding:.75rem 1rem;
    background:#fff3cd;
    border:1px solid #ffd54f;
    border-radius:8px;
    color:#7a5c00;
}

.youtube-info.show{ display:block; }

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
    text-decoration:none;
    font-weight:600;
}

.btn-secondary{
    background:linear-gradient(135deg,#95a5a6,#7f8c8d);
    color:#fff;
}

.btn-primary{
    background:linear-gradient(135deg,#8B7355,#6B5644);
    color:#fff;
}

@media(max-width:768px){
    .form-card{ padding:1.5rem; }
    .type-toggle{ grid-template-columns:1fr; }
    .action-buttons{ flex-direction:column; }
    .btn{ width:100%; text-align:center; }
}
</style>
@endpush

@section('content')
<div class="form-container">

<form action="{{ route('admin.galleries.update',$gallery) }}"
      method="POST"
      enctype="multipart/form-data"
      id="editForm">
@csrf
@method('PUT')

<input type="hidden" name="removed_photos" id="removed_photos">

{{-- TYPE --}}
<div class="form-card">
    <h3 class="section-title">
        <span class="section-icon"><i class="fa-solid fa-photo-film"></i></span>
        Jenis Konten
    </h3>

    <div class="type-toggle">

        <div class="type-option">
            <input type="radio"
                   id="type_photo"
                   name="type"
                   value="photo"
                   {{ ($gallery->type ?? 'photo') === 'photo' ? 'checked' : '' }}>
            <label for="type_photo" class="type-label">
                <div class="type-icon photo"><i class="fa-solid fa-image"></i></div>
                <div class="type-info">
                    <h4>Foto</h4>
                    <p>Upload gambar</p>
                </div>
            </label>
        </div>

        <div class="type-option">
            <input type="radio"
                   id="type_video"
                   name="type"
                   value="video"
                   {{ ($gallery->type ?? '') === 'video' ? 'checked' : '' }}>
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
        <input type="text"
               name="title"
               class="form-control"
               required
               value="{{ old('title',$gallery->title) }}">
    </div>

    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description"
                  class="form-control">{{ old('description',$gallery->description) }}</textarea>
    </div>

    <div class="form-group">
        <label class="form-label">Kategori</label>
        <input type="text"
               name="category"
               class="form-control"
               value="{{ old('category',$gallery->category) }}">
    </div>

    <div class="form-group">
        <label class="form-label">Urutan</label>
        <input type="number"
               name="order"
               class="form-control"
               value="{{ old('order',$gallery->order ?? 0) }}">
    </div>
</div>

{{-- VIDEO --}}
<div class="form-card"
     id="videoSection"
     style="{{ ($gallery->type ?? '') === 'video' ? '' : 'display:none;' }}">

    <h3 class="section-title">
        <span class="section-icon" style="background:linear-gradient(135deg,#e74c3c,#c0392b)">
            <i class="fa-brands fa-youtube"></i>
        </span>
        URL Video YouTube
    </h3>

    <div class="form-group">
        <label class="form-label">URL YouTube</label>
        <input type="url"
               id="video_url"
               name="video_url"
               class="form-control"
               value="{{ old('video_url',$gallery->video_url) }}">
    </div>

    <div class="youtube-info" id="youtubeInfo">
        Video ditemukan
    </div>

    <div class="video-preview" id="videoPreview">
        <iframe id="videoIframe"></iframe>
    </div>
</div>

{{-- PHOTO MAIN --}}
<div class="form-card"
     id="photoSection"
     style="{{ ($gallery->type ?? '') === 'video' ? 'display:none;' : '' }}">

    <h3 class="section-title">
        <span class="section-icon"><i class="fa-solid fa-star"></i></span>
        Foto Utama
    </h3>

    @if($gallery->image)
    <div class="current-image-box">
        <span>Foto saat ini</span>
        <x-image :src="$gallery->image" alt="$gallery->title" />
    </div>
    @endif

    <div class="upload-area">
        <input type="file"
               id="fotoInput"
               name="foto"
               accept="image/jpeg,image/png,image/jpg,image/webp">

        <div class="upload-icon">
            <i class="fas fa-cloud-upload-alt"></i>
        </div>

        <div class="upload-text">
            <strong>Click to upload</strong><br>
            Ganti foto utama
        </div>
    </div>

    <div class="compress-progress" id="fotoProg">
        <div class="progress-label" id="fotoLabel">Mengompres...</div>
        <div class="progress-bar-wrap">
            <div class="progress-bar" id="fotoBar"></div>
        </div>
    </div>

    <div class="main-preview-wrapper" id="mainPreview">
        <img src="">
        <button type="button"
                class="remove-new-main"
                onclick="removeMainImage()">×</button>
    </div>
</div>

{{-- PHOTOS --}}
<div class="form-card"
     id="photosSection"
     style="{{ ($gallery->type ?? '') === 'video' ? 'display:none;' : '' }}">

    <h3 class="section-title">
        <span class="section-icon"><i class="fa-solid fa-images"></i></span>
        Foto Tambahan
    </h3>

    @if($gallery->photo && count($gallery->photo))
    <div class="form-group">
        <label class="form-label">Foto saat ini</label>

        <div class="photos-grid" id="existingGrid">
            @foreach($gallery->photo as $i => $photo)
            <div class="photo-item" id="old_{{ $i }}">
                <x-image :src="$photo" alt="photo" />
                <button type="button"
                        class="remove-photo"
                        onclick="removeExistingPhoto('{{ $photo }}','old_{{ $i }}')">×</button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="upload-area">
        <input type="file"
               id="photosInput"
               name="photos[]"
               multiple
               accept="image/jpeg,image/png,image/jpg,image/webp">

        <div class="upload-icon">
            <i class="fas fa-images"></i>
        </div>

        <div class="upload-text">
            <strong>Tambah foto baru</strong><br>
            Bisa banyak foto
        </div>
    </div>

    <div class="compress-progress" id="photosProg">
        <div class="progress-label" id="photosLabel">Mengompres...</div>
        <div class="progress-bar-wrap">
            <div class="progress-bar" id="photosBar"></div>
        </div>
    </div>

    <div id="newPhotosGrid" class="photos-grid" style="display:none;"></div>
</div>

{{-- ACTION --}}
<div class="action-section">
    <div class="action-buttons">
        <a href="{{ route('admin.galleries.index') }}"
           class="btn btn-secondary">Cancel</a>

        <button type="submit"
                class="btn btn-primary"
                id="submitBtn">
            Update Gallery
        </button>
    </div>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){

const form          = document.getElementById('editForm');
const submitBtn     = document.getElementById('submitBtn');

const typeRadios    = document.querySelectorAll('input[name="type"]');
const videoSection  = document.getElementById('videoSection');
const photoSection  = document.getElementById('photoSection');
const photosSection = document.getElementById('photosSection');

const fotoInput     = document.getElementById('fotoInput');
const photosInput   = document.getElementById('photosInput');
const videoInput    = document.getElementById('video_url');

const iframe        = document.getElementById('videoIframe');
const previewBox    = document.getElementById('videoPreview');
const infoBox       = document.getElementById('youtubeInfo');

const mainPreview   = document.getElementById('mainPreview');
const newGrid       = document.getElementById('newPhotosGrid');

let removedPaths = [];
let photoFiles   = [];

/* TYPE */
function setType(type){
    if(type === 'video'){
        videoSection.style.display='block';
        photoSection.style.display='none';
        photosSection.style.display='none';
    }else{
        videoSection.style.display='none';
        photoSection.style.display='block';
        photosSection.style.display='block';
    }
}

typeRadios.forEach(r=>{
    r.addEventListener('change',function(){
        setType(this.value);
    });
});

/* YOUTUBE */
function getYoutubeId(url){
    if(!url) return null;
    const reg=/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const m=url.match(reg);
    return m ? m[1] : null;
}

function previewYoutube(url){
    const id=getYoutubeId(url);

    if(id){
        iframe.src='https://www.youtube.com/embed/'+id+'?rel=0';
        previewBox.classList.add('show');
        infoBox.classList.add('show');
    }else{
        iframe.src='';
        previewBox.classList.remove('show');
        infoBox.classList.remove('show');
    }
}

videoInput?.addEventListener('input',function(){
    previewYoutube(this.value);
});

if(videoInput?.value){
    previewYoutube(videoInput.value);
}

/* MAIN IMAGE COMPRESS */
fotoInput?.addEventListener('change',async function(){

    const raw=this.files[0];
    if(!raw) return;

    showProgress('foto',40,'Mengompres...',false);

    const result = await ImageCompressor.compress(raw,{
        maxWidth:1920,
        maxHeight:1920,
        quality:0.82
    });

    ImageCompressor.replaceFiles(fotoInput,[result]);

    showProgress('foto',100,'✓ Siap',true);

    const reader=new FileReader();
    reader.onload=e=>{
        mainPreview.classList.add('show');
        mainPreview.querySelector('img').src=e.target.result;
    };
    reader.readAsDataURL(result);
});

/* REMOVE MAIN */
window.removeMainImage=function(){
    fotoInput.value='';
    mainPreview.classList.remove('show');
}

/* MULTI PHOTO */
photosInput?.addEventListener('change',async function(){

    const raw = Array.from(this.files);
    if(!raw.length) return;

    showProgress('photos',0,'Mengompres 0 / '+raw.length,false);

    const compressed=[];

    for(let i=0;i<raw.length;i++){

        const result = await ImageCompressor.compress(raw[i],{
            maxWidth:1920,
            maxHeight:1920,
            quality:0.82
        });

        compressed.push(result);

        showProgress(
            'photos',
            Math.round(((i+1)/raw.length)*100),
            'Mengompres '+(i+1)+' / '+raw.length,
            false
        );
    }

    photoFiles=[...photoFiles,...compressed];

    ImageCompressor.replaceFiles(photosInput,photoFiles);

    showProgress(
        'photos',
        100,
        '✓ '+photoFiles.length+' foto siap',
        true
    );

    renderPhotos();
});

function renderPhotos(){

    newGrid.innerHTML='';

    if(!photoFiles.length){
        newGrid.style.display='none';
        return;
    }

    newGrid.style.display='grid';

    photoFiles.forEach((file,index)=>{

        const reader=new FileReader();

        reader.onload=e=>{
            const div=document.createElement('div');
            div.className='photo-item';

            div.innerHTML=`
                <img src="${e.target.result}">
                <button type="button"
                        class="remove-photo"
                        onclick="removePhoto(${index})">×</button>
            `;

            newGrid.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}

window.removePhoto=function(index){
    photoFiles.splice(index,1);
    ImageCompressor.replaceFiles(photosInput,photoFiles);
    renderPhotos();
}

/* REMOVE EXISTING */
window.removeExistingPhoto=function(path,id){

    if(!confirm('Hapus foto ini?')) return;

    removedPaths.push(path);

    document.getElementById('removed_photos').value =
        JSON.stringify(removedPaths);

    document.getElementById(id)?.remove();
}

/* PROGRESS */
function showProgress(type,pct,text,done){

    const prog=document.getElementById(type+'Prog');
    const bar=document.getElementById(type+'Bar');
    const label=document.getElementById(type+'Label');

    prog.classList.add('show');
    bar.style.width=pct+'%';
    label.textContent=text;

    if(done){
        setTimeout(()=>{
            prog.classList.remove('show');
        },2200);
    }
}

/* SUBMIT */
form.addEventListener('submit',function(){

    submitBtn.disabled=true;
    submitBtn.innerHTML='⏳ Updating...';
});

setType(document.querySelector('input[name="type"]:checked').value);

});
</script>
@endpush