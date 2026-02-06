@extends('layouts.admin')

@section('title', 'Package Details')
@section('page-title', 'Package Details')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

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

    .description-text {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        white-space: pre-wrap;
    }

    /* Services List */
    .services-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .service-item {
        padding: 1rem;
        background: white;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
    }

    .service-item:hover {
        background: #f8f9fa;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .service-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        border-radius: 8px;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .service-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #999;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
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

    /* Responsive */
    @media (max-width: 768px) {
        .detail-card {
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
<div class="detail-container">
    <!-- Package Information -->
    <div class="detail-card">
        <h3 class="card-title">
            <span class="card-title-icon">📦</span>
            Package Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Package Name</div>
                <div class="info-value">{{ $package->name }}</div>
            </div>

            @if($package->description)
            <div class="info-item">
                <div class="info-label">Description</div>
                <div class="description-text">{{ $package->description }}</div>
            </div>
            @endif

            <div class="info-item">
                <div class="info-label">Total Services</div>
                <div class="info-value">{{ $package->services->count() }} Service{{ $package->services->count() != 1 ? 's' : '' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $package->created_at->format('F d, Y \a\t h:i A') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $package->updated_at->format('F d, Y \a\t h:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Services in Package -->
    <div class="detail-card">
        <h3 class="card-title">
            <span class="card-title-icon">⚙️</span>
            Services in this Package
        </h3>
        
        @if($package->services->count() > 0)
        <ul class="services-list">
            @foreach($package->services as $service)
            <li class="service-item">
                <div class="service-icon">⚙️</div>
                <div class="service-name">{{ $service->name }}</div>
            </li>
            @endforeach
        </ul>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <p>No services assigned to this package yet.</p>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="action-section">
        <div class="action-buttons">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                ← Back to List
            </a>
            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-primary">
                ✏️ Edit Package
            </a>
            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this package? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection