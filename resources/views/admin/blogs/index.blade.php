@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page-title', 'Blog Posts Management')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .btn-add {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    /* Search and Filter */
    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-input {
        font-family: 'Work Sans', sans-serif;
        width: 100%;
        padding: 0.875rem 1.25rem 0.875rem 3rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.25rem;
        opacity: 0.4;
    }

    .filter-select {
        font-family: 'Work Sans', sans-serif;
        padding: 0.875rem 1.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
    }

    /* Blog Grid */
    .blogs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .blog-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .blog-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
    }

    .blog-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .meta-badge {
        font-family: 'Work Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.35rem 0.75rem;
        background: #f0f0f0;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #666;
    }

    .meta-badge.published {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
    }

    .meta-badge.draft {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        color: white;
    }

    .blog-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .blog-excerpt {
        font-family: 'Work Sans', sans-serif;
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .blog-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }

    .blog-author {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.85rem;
        color: #999;
    }

    .blog-author strong {
        color: #666;
    }

    .blog-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-view {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .btn-view:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-edit {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .empty-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        margin-bottom: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .blogs-grid {
            grid-template-columns: 1fr;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .header-section {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="header-section">
    <h2 class="header-title">All Blog Posts</h2>
    <a href="{{ route('admin.blogs.create') }}" class="btn-add">
        + Add New Post
    </a>
</div>

<!-- Search and Filter -->
<div class="filter-section">
    <form action="{{ route('admin.blogs.index') }}" method="GET">
        <div class="filter-grid">
            <div class="search-box">
                <span class="search-icon"></span>
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search blog posts..."
                    value="{{ request('search') }}"
                >
            </div>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
    </form>
</div>

<!-- Blog Posts Grid -->
@if($blogs->count() > 0)
<div class="blogs-grid">
    @foreach($blogs as $blog)
    <div class="blog-card">
        @if($blog->image)
        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="blog-image">
        @else
        <div class="blog-image" style="background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
            <i class="fas fa-image"></i>
        </div>
        @endif
        
        <div class="blog-content">
            <div class="blog-meta">
                <span class="meta-badge {{ $blog->is_published ? 'published' : 'draft' }}">
                    {{ $blog->is_published ? '✓ Published' : 'Draft' }}
                </span>
                <span class="meta-badge">
                    <i class="fas fa-calendar-alt"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Not set' }}
                </span>
            </div>
            
            <h3 class="blog-title">{{ $blog->title }}</h3>
            
            @if($blog->excerpt)
            <p class="blog-excerpt">{{ $blog->excerpt }}</p>
            @else
            <p class="blog-excerpt">{{ Str::limit(strip_tags($blog->content), 120) }}</p>
            @endif
            
            <div class="blog-footer">
                <div class="blog-author">
                    @if($blog->author)
                    By <strong>{{ $blog->author }}</strong>
                    @else
                    <strong>No author</strong>
                    @endif
                </div>
                <div class="blog-actions">
                    <a href="{{ route('admin.blogs.show', $blog) }}" class="btn-icon btn-view" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn-icon btn-edit" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this blog post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div style="margin-top: 2rem;">
    {{ $blogs->links() }}
</div>
@else
<div class="empty-state">
    <div class="empty-icon"><i class="fas fa-newspaper"></i></div>
    <h3 class="empty-title">No Blog Posts Found</h3>
    <p class="empty-text">
        @if(request('search'))
            No blog posts match your search criteria.
        @else
            Start creating your first blog post to share stories and updates.
        @endif
    </p>
    <a href="{{ route('admin.blogs.create') }}" class="btn-add">
        + Create First Blog Post
    </a>
</div>
@endif
@endsection