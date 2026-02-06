@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* User Profile Header */
    .profile-header {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(139, 115, 85, 0.3);
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .profile-email {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.05rem;
        color: #666;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: 'Work Sans', sans-serif;
    }

    .role-badge.admin {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
    }

    .role-badge.user {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    /* Detail Cards */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .detail-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.35rem;
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
        font-size: 1.1rem;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-item {
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        margin-bottom: 1rem;
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
        margin-bottom: 0.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-family: 'Work Sans', sans-serif;
        font-size: 1.05rem;
        color: #1a1a1a;
        font-weight: 500;
        word-break: break-word;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
    }

    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.85rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Activity Timeline */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e8e8e8;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.5rem;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #8B7355;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #8B7355;
    }

    .timeline-content {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .timeline-title {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .timeline-time {
        font-size: 0.85rem;
        color: #999;
        display: flex;
        align-items: center;
        gap: 0.35rem;
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

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.85rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-name {
            justify-content: center;
        }

        .profile-email {
            justify-content: center;
        }

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
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="profile-info">
            <h1 class="profile-name">
                {{ $user->name }}
                <span class="status-badge active">
                    <i class="fa-solid fa-circle-check"></i>
                    Active
                </span>
            </h1>
            <div class="profile-email">
                <i class="fa-solid fa-envelope"></i>
                {{ $user->email }}
            </div>
            <span class="role-badge {{ $user->role }}">
                @if($user->role == 'admin')
                    <i class="fa-solid fa-user-shield"></i> Administrator
                @else
                    <i class="fa-solid fa-user"></i> Regular User
                @endif
            </span>
        </div>
    </div>

    <!-- Detail Grid -->
    <div class="detail-grid">
        <!-- Account Information -->
        <div class="detail-card">
            <h3 class="card-title">
                <span class="card-title-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </span>
                Account Information
            </h3>
            <ul class="info-list">
                <li class="info-item">
                    <div class="info-label">
                        <i class="fa-solid fa-hashtag"></i>
                        User ID
                    </div>
                    <div class="info-value">#{{ $user->id }}</div>
                </li>
                <li class="info-item">
                    <div class="info-label">
                        <i class="fa-solid fa-user"></i>
                        Full Name
                    </div>
                    <div class="info-value">{{ $user->name }}</div>
                </li>
                <li class="info-item">
                    <div class="info-label">
                        <i class="fa-solid fa-envelope"></i>
                        Email Address
                    </div>
                    <div class="info-value">{{ $user->email }}</div>
                </li>
                <li class="info-item">
                    <div class="info-label">
                        <i class="fa-solid fa-shield-halved"></i>
                        Role
                    </div>
                    <div class="info-value">{{ ucfirst($user->role) }}</div>
                </li>
            </ul>
        </div>

        <!-- Account Activity -->
        <div class="detail-card">
            <h3 class="card-title">
                <span class="card-title-icon">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </span>
                Account Activity
            </h3>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-title">
                            <i class="fa-solid fa-calendar-plus"></i>
                            Account Created
                        </div>
                        <div class="timeline-time">
                            <i class="fa-solid fa-clock"></i>
                            {{ $user->created_at->format('F d, Y \a\t h:i A') }}
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-title">
                            <i class="fa-solid fa-pen"></i>
                            Last Updated
                        </div>
                        <div class="timeline-time">
                            <i class="fa-solid fa-clock"></i>
                            {{ $user->updated_at->format('F d, Y \a\t h:i A') }}
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-title">
                            <i class="fa-solid fa-calendar-check"></i>
                            Member For
                        </div>
                        <div class="timeline-time">
                            <i class="fa-solid fa-clock"></i>
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="detail-card">
        <h3 class="card-title">
            <span class="card-title-icon">
                <i class="fa-solid fa-chart-simple"></i>
            </span>
            Account Statistics
        </h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div class="stat-value">{{ $user->created_at->diffInDays(now()) }}</div>
                <div class="stat-label">Days Active</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="stat-value">{{ ucfirst($user->role) }}</div>
                <div class="stat-label">Access Level</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-value">Active</div>
                <div class="stat-label">Account Status</div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="action-section">
        <div class="action-buttons">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i> Edit User
            </a>
            @if($user->id != auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> Delete User
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection