@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users Management')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.admin {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
    }

    .stat-icon.user {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .stat-label {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        color: #999;
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

    /* Users Table */
    .users-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        overflow: hidden;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table thead {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
    }

    .users-table th {
        font-family: 'Work Sans', sans-serif;
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .users-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .users-table tbody tr:hover {
        background: #f8f9fa;
    }

    .users-table td {
        font-family: 'Work Sans', sans-serif;
        padding: 1.25rem 1.5rem;
        color: #333;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .user-email {
        font-size: 0.85rem;
        color: #666;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .role-badge.admin {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        color: white;
    }

    .role-badge.user {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .action-buttons {
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
        color: #8B7355;
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
    @media (max-width: 968px) {
        .users-table-container {
            overflow-x: auto;
        }

        .users-table {
            min-width: 800px;
        }
    }

    @media (max-width: 768px) {
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

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="header-section">
    <h2 class="header-title">Users Management</h2>
    <a href="{{ route('admin.users.create') }}" class="btn-add">
        <i class="fa-solid fa-user-plus"></i> Add New User
    </a>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon admin">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        </div>
        <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
        <div class="stat-label">Administrators</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon user">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="stat-number">{{ $users->where('role', 'user')->count() }}</div>
        <div class="stat-label">Regular Users</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon admin">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
        <div class="stat-number">{{ $users->count() }}</div>
        <div class="stat-label">Total Users</div>
    </div>
</div>

<!-- Search and Filter -->
<div class="filter-section">
    <form action="{{ route('admin.users.index') }}" method="GET">
        <div class="filter-grid">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search users by name or email..."
                    value="{{ request('search') }}"
                >
            </div>
            <select name="role" class="filter-select" onchange="this.form.submit()">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
    </form>
</div>

<!-- Users Table -->
@if($users->count() > 0)
<div class="users-table-container">
    <table class="users-table">
        <thead>
            <tr>
                <th><i class="fa-solid fa-hashtag"></i> ID</th>
                <th><i class="fa-solid fa-user"></i> User</th>
                <th><i class="fa-solid fa-shield-halved"></i> Role</th>
                <th><i class="fa-solid fa-calendar-plus"></i> Joined</th>
                <th><i class="fa-solid fa-gears"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td><strong>#{{ $user->id }}</strong></td>
                <td>
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-email">
                                <i class="fa-solid fa-envelope"></i>
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="role-badge {{ $user->role }}">
                        @if($user->role == 'admin')
                            <i class="fa-solid fa-user-shield"></i> Administrator
                        @else
                            <i class="fa-solid fa-user"></i> User
                        @endif
                    </span>
                </td>
                <td>
                    <i class="fa-solid fa-calendar"></i>
                    {{ $user->created_at->format('M d, Y') }}
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn-icon btn-view" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon btn-edit" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        @if($user->id != auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="margin-top: 2rem;">
    {{ $users->links() }}
</div>
@else
<div class="empty-state">
    <div class="empty-icon">
        <i class="fa-solid fa-users-slash"></i>
    </div>
    <h3 class="empty-title">No Users Found</h3>
    <p class="empty-text">
        @if(request('search'))
            No users match your search criteria.
        @else
            Start by creating your first user account.
        @endif
    </p>
    <a href="{{ route('admin.users.create') }}" class="btn-add">
        <i class="fa-solid fa-user-plus"></i> Create First User
    </a>
</div>
@endif
@endsection