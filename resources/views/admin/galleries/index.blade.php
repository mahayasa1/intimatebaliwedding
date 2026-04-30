@extends('layouts.admin')

@section('title', 'Gallery')
@section('page-title', 'Gallery Management')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

body{
    font-family:'Work Sans',sans-serif;
    background:#f8f8f8;
}

/* HEADER */
.header-section{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:1rem;
    flex-wrap:wrap;
    margin-bottom:2rem;
}

.header-title{
    font-family:'Playfair Display',serif;
    font-size:1.8rem;
    font-weight:700;
    margin:0;
}

.btn-add{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:.5rem;
    padding:.9rem 1.4rem;
    border-radius:12px;
    text-decoration:none;
    color:#fff;
    font-weight:600;
    border:none;
    background:linear-gradient(135deg,#8B7355,#6B5644);
}

.btn-add:hover{
    color:#fff;
    opacity:.95;
}

/* FILTER */
.filter-box{
    background:#fff;
    padding:16px;
    border-radius:14px;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    margin-bottom:20px;
}

.filter-grid{
    display:grid;
    grid-template-columns:1fr 180px 120px;
    gap:12px;
}

.search-input{
    width:100%;
    height:46px;
    border-radius:10px;
    padding:0 16px;
    border:1px solid #ddd;
    font-size:14px;
    outline:none;
}

/* GRID */
.gallery-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:1.5rem;
}

.gallery-card{
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(0,0,0,.06);
}

.gallery-thumb{
    height:260px;
    position:relative;
    overflow:hidden;
    background:#f2f2f2;
}

.gallery-thumb img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.badge-type{
    position:absolute;
    top:12px;
    left:12px;
    z-index:5;
    background:rgba(0,0,0,.65);
    color:#fff;
    padding:.35rem .7rem;
    font-size:.72rem;
    border-radius:8px;
    font-weight:600;
}

.video-play{
    position:absolute;
    inset:0;
    display:flex;
    justify-content:center;
    align-items:center;
    background:rgba(0,0,0,.25);
}

.video-play i{
    width:55px;
    height:55px;
    border-radius:50%;
    background:#fff;
    color:#e74c3c;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* CONTENT */
.gallery-content{
    padding:1.2rem;
}

.gallery-title{
    font-family:'Playfair Display',serif;
    font-size:1.1rem;
    font-weight:700;
    margin-bottom:.5rem;
}

.gallery-desc{
    font-size:.9rem;
    color:#777;
    margin-bottom:1rem;
    min-height:42px;
}

.meta{
    display:flex;
    gap:.5rem;
    flex-wrap:wrap;
    margin-bottom:1rem;
}

.meta span{
    background:#f3f3f3;
    padding:.35rem .65rem;
    border-radius:8px;
    font-size:.75rem;
}

/* ACTION */
.actions{
    display:flex;
    gap:.5rem;
}

.btn-action{
    flex:1;
    border:none;
    text-decoration:none;
    padding:.7rem;
    border-radius:10px;
    color:#fff;
    font-size:.85rem;
    font-weight:600;
    text-align:center;
}

.btn-view{background:#3498db;}
.btn-edit{background:#8B7355;}
.btn-delete{background:#e74c3c;}

.btn-action:hover{
    color:#fff;
    opacity:.95;
}

/* PAGINATION */
.simple-pagination{
    margin-top:24px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

.btn-page{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:8px 14px;
    border-radius:8px;
    background:#fff;
    border:1px solid #ddd;
    text-decoration:none;
    color:#6B5644;
    font-size:13px;
    font-weight:600;
}

.btn-page:hover{
    background:#f4f4f4;
}

.page-info{
    font-size:13px;
    color:#777;
}

/* EMPTY */
.empty-box{
    background:#fff;
    padding:4rem 2rem;
    text-align:center;
    border-radius:18px;
    box-shadow:0 4px 20px rgba(0,0,0,.06);
}

/* MOBILE */
@media(max-width:768px){

    .filter-grid{
        grid-template-columns:1fr;
    }

    .gallery-grid{
        grid-template-columns:1fr;
    }

    .actions{
        flex-direction:column;
    }

    .btn-action{
        width:100%;
    }

    .simple-pagination{
        justify-content:center;
    }
}
</style>
@endpush

@section('content')

<div class="header-section">
    <h2 class="header-title">Gallery Collection</h2>

    <a href="{{ route('admin.galleries.create') }}" class="btn-add">
        <i class="fa-solid fa-plus"></i>
        Add New Item
    </a>
</div>

{{-- FILTER --}}
<div class="filter-box">

    <form method="GET" action="{{ route('admin.galleries.index') }}">

        <div class="filter-grid">

            {{-- SEARCH --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="search-input"
                placeholder="Search gallery..."
            >

            {{-- TYPE --}}
            <select name="type" class="search-input">
                <option value="">All Type</option>
                <option value="photo" {{ request('type') == 'photo' ? 'selected' : '' }}>
                    Photo
                </option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>
                    Video
                </option>
            </select>

            {{-- BUTTON --}}
            <button type="submit" class="btn-add">
                <i class="fa-solid fa-filter"></i>
                Filter
            </button>

        </div>

    </form>

</div>

@if($galleries->count())

<div class="gallery-grid">

@foreach($galleries as $gallery)

@php $isVideo = $gallery->isVideo(); @endphp

<div class="gallery-card">

    <div class="gallery-thumb">

        <span class="badge-type">
            {{ $isVideo ? 'VIDEO' : 'PHOTO' }}
        </span>

        @if($isVideo)

            @if($gallery->youtube_thumbnail)
                <img src="{{ $gallery->youtube_thumbnail }}">
            @endif

            <div class="video-play">
                <i class="fa-solid fa-play"></i>
            </div>

        @else

            @if($gallery->image)
                <img src="{{ asset('storage/'.$gallery->image) }}">
            @endif

        @endif

    </div>

    <div class="gallery-content">

        <div class="gallery-title">
            {{ $gallery->title }}
        </div>

        <div class="gallery-desc">
            {{ Str::limit($gallery->description,80) }}
        </div>

        <div class="meta">
            <span>{{ $gallery->created_at->format('d M Y') }}</span>

            @if($gallery->category)
                <span>{{ $gallery->category }}</span>
            @endif
        </div>

        <div class="actions">

            <a href="{{ route('admin.galleries.show',$gallery) }}"
               class="btn-action btn-view">
                View
            </a>

            <a href="{{ route('admin.galleries.edit',$gallery) }}"
               class="btn-action btn-edit">
                Edit
            </a>

            <form
                action="{{ route('admin.galleries.destroy',$gallery) }}"
                method="POST"
                style="flex:1"
                onsubmit="return confirm('Delete this item?')"
            >
                @csrf
                @method('DELETE')

                <button class="btn-action btn-delete w-100">
                    Delete
                </button>

            </form>

        </div>

    </div>

</div>

@endforeach

</div>

{{-- PAGINATION --}}
<div class="simple-pagination">

    @if ($galleries->onFirstPage())
        <span class="btn-page" style="opacity:.5;">Previous</span>
    @else
        <a href="{{ $galleries->previousPageUrl() }}" class="btn-page">
            <i class="fa-solid fa-angle-left"></i>
            Previous
        </a>
    @endif

    <div class="page-info">
        Page {{ $galleries->currentPage() }} / {{ $galleries->lastPage() }}
    </div>

    @if ($galleries->hasMorePages())
        <a href="{{ $galleries->nextPageUrl() }}" class="btn-page">
            Next
            <i class="fa-solid fa-angle-right"></i>
        </a>
    @else
        <span class="btn-page" style="opacity:.5;">Next</span>
    @endif

</div>

@else

<div class="empty-box">
    <h3>No Gallery Found</h3>
    <p>Create your first gallery item now.</p>

    <a href="{{ route('admin.galleries.create') }}" class="btn-add">
        Add First Item
    </a>
</div>

@endif

@endsection