@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

    .section-icon {
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

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label .required {
        color: #e74c3c;
        margin-left: 0.25rem;
    }

    .form-control {
        font-family: 'Work Sans', sans-serif;
        width: 100%;
        padding: 0.875rem 1.25rem 0.875rem 3rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #8B7355;
        box-shadow: 0 0 0 4px rgba(139, 115, 85, 0.1);
        transform: translateY(-2px);
    }

    .form-control.error {
        border-color: #e74c3c;
        background: #fff5f5;
    }

    .error-message {
        font-family: 'Work Sans', sans-serif;
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-help {
        font-family: 'Work Sans', sans-serif;
        color: #999;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Password Toggle */
    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        font-size: 1rem;
        padding: 0.5rem;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #8B7355;
    }

    /* Role Selection */
    .role-selection {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .role-option {
        position: relative;
    }

    .role-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .role-label {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .role-option input[type="radio"]:checked + .role-label {
        background: linear-gradient(135deg, rgba(139, 115, 85, 0.1) 0%, rgba(107, 86, 68, 0.1) 100%);
        border-color: #8B7355;
    }

    .role-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .role-icon.admin {
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
    }

    .role-icon.user {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .role-info h4 {
        font-family: 'Work Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .role-info p {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.8rem;
        color: #666;
        margin: 0;
    }

    /* Info Box */
    .info-box {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #2196F3;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-box-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #1565C0;
        font-size: 0.9rem;
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

        .form-grid {
            grid-template-columns: 1fr;
        }

        .role-selection {
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
<div class="form-container">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- User Information -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">
                    <i class="fa-solid fa-user"></i>
                </span>
                User Information
            </h3>
            
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fa-solid fa-user"></i>
                    Full Name <span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control @error('name') error @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                        placeholder="Enter full name"
                    >
                </div>
                @error('name')
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fa-solid fa-envelope"></i>
                    Email Address <span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control @error('email') error @enderror"
                        value="{{ old('email', $user->email) }}"
                        required
                        placeholder="user@example.com"
                    >
                </div>
                @error('email')
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <!-- Change Password (Optional) -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">
                    <i class="fa-solid fa-key"></i>
                </span>
                Change Password (Optional)
            </h3>

            <div class="info-box">
                <div class="info-box-content">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Leave password fields empty if you don't want to change the password.</span>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fa-solid fa-lock"></i>
                        New Password
                    </label>
                    <div class="password-wrapper">
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control @error('password') error @enderror"
                                placeholder="Enter new password"
                            >
                            <button type="button" class="password-toggle" id="toggle-password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                    <div class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="form-help">
                        <i class="fa-solid fa-circle-info"></i>
                        Minimum 8 characters
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="fa-solid fa-lock"></i>
                        Confirm New Password
                    </label>
                    <div class="password-wrapper">
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-control"
                                placeholder="Confirm new password"
                            >
                            <button type="button" class="password-toggle" id="toggle-password-confirm">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Role -->
        <div class="form-card">
            <h3 class="section-title">
                <span class="section-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
                User Role
            </h3>

            <div class="form-group">
                <label class="form-label">
                    <i class="fa-solid fa-user-tag"></i>
                    Select Role <span class="required">*</span>
                </label>
                <div class="role-selection">
                    <div class="role-option">
                        <input 
                            type="radio" 
                            id="role_admin" 
                            name="role" 
                            value="admin"
                            {{ old('role', $user->role) == 'admin' ? 'checked' : '' }}
                        >
                        <label for="role_admin" class="role-label">
                            <div class="role-icon admin">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div class="role-info">
                                <h4>Administrator</h4>
                                <p>Full system access</p>
                            </div>
                        </label>
                    </div>

                    <div class="role-option">
                        <input 
                            type="radio" 
                            id="role_user" 
                            name="role" 
                            value="user"
                            {{ old('role', $user->role) == 'user' ? 'checked' : '' }}
                        >
                        <label for="role_user" class="role-label">
                            <div class="role-icon user">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="role-info">
                                <h4>Regular User</h4>
                                <p>Limited access</p>
                            </div>
                        </label>
                    </div>
                </div>
                @error('role')
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Update User
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Password Toggle
    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    document.getElementById('toggle-password-confirm').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush