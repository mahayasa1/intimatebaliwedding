@extends('layouts.admin')

@section('title', 'Blog Post Details')
@section('page-title', 'Blog Post Details')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Featured Image */
    .featured-image-section {
        margin-bottom: 2rem;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .featured-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        display: block;
    }

    /* Detail Card */
    .detail-card {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 2rem;
    }

    .card-title {
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

    .card-title-icon {
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

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .info-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.8rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .info-value {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.1rem;
        color: #1a1a1a;
        font-weight: 500;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .status-badge.published {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
    }

    .status-badge.draft {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        color: white;
    }

    /* Blog Title */
    .blog-title-display {
        font-family: 'Playfair Display', serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.3;
        margin-bottom: 1rem;
    }

    .blog-excerpt-display {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.15rem;
        color: #666;
        line-height: 1.7;
        font-style: italic;
        padding: 1.5rem;
        background: #f8f9fa;
        border-left: 4px solid #8B7355;
        border-radius: 8px;
    }

    /* Content Display */
    .content-display {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        white-space: pre-wrap;
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

    .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
    }

    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-card {
            padding: 1.5rem;
        }

        .blog-title-display {
            font-size: 1.75rem;
        }

        .featured-image {
            height: 250px;
        }

        .info-grid {
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
<div class="detail-container">
    <!-- Featured Image -->
    @if($blog->image)
    <div class="featured-image-section">
        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="featured-image">
    </div>
    @endif

    <!-- Blog Title & Excerpt -->
    <div class="detail-card">
        <h1 class="blog-title-display">{{ $blog->title }}</h1>
        
        @if($blog->excerpt)
        <div class="blog-excerpt-display">
            {{ $blog->excerpt }}
        </div>
        @endif
    </div>

    <!-- Blog Information -->
    <div class="detail-card">
        <h3 class="card-title">
            <i class="fas fa-info-circle"></i>
            Post Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="status-badge {{ $blog->is_published ? 'published' : 'draft' }}">
                        {{ $blog->is_published ? '✓ Published' : '📄 Draft' }}
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Author</div>
                <div class="info-value">{{ $blog->author ?? 'Not specified' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Publish Date</div>
                <div class="info-value">
                    {{ $blog->published_at ? $blog->published_at->format('F d, Y') : 'Not set' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Slug</div>
                <div class="info-value" style="font-size: 0.9rem; color: #666;">{{ $blog->slug }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $blog->created_at->format('F d, Y \a\t h:i A') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $blog->updated_at->format('F d, Y \a\t h:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Blog Content -->
    <div class="detail-card">
        <h3 class="card-title">
            <span class="card-title-icon">📝</span>
            Content
        </h3>
        <div class="content-display">{{ $blog->content }}</div>
    </div>

    <!-- Actions -->
    <div class="action-section">
        <div class="action-buttons">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                ← Back to List
            </a>
            <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-external-link-alt"></i> View on Website
            </a>
            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Post
            </a>
            <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection