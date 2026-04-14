<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Intimate Bali Wedding</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background Image with Overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?w=1920&q=80');
            background-size: cover;
            background-position: center;
            filter: brightness(0.6);
            z-index: -1;
        }

        /* Decorative Wave Line */
        .wave-decoration {
            position: absolute;
            top: 50px;
            left: 100px;
            width: 200px;
            height: 100px;
            opacity: 0.3;
        }

        .wave-decoration svg {
            width: 100%;
            height: 100%;
            stroke: white;
            fill: none;
            stroke-width: 2;
        }

        /* Login Container */
        .login-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 0;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 420px;
            width: 90%;
            padding: 3rem 2.5rem;
            position: relative;
            z-index: 1;
        }

        /* Logo Area */
        .login-logo {
            text-align: center;
            margin-bottom: 3rem;
        }

        /* Form Styles */
        .login-form {
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: none;
            background: #E8E8E8;
            border-radius: 0;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s;
            color: #333;
        }

        .form-group input::placeholder {
            color: #999;
        }

        .form-group input:focus {
            outline: none;
            background: #DDD;
        }

        .form-group input.error {
            border: 2px solid #e74c3c;
        }

        /* Error Messages */
        .alert {
            padding: 1rem;
            border-radius: 0;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: #8B7355;
            color: white;
            border: none;
            border-radius: 0;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2rem;
        }

        .btn-login:hover {
            background: #6F5B44;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 115, 85, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Back Link */
        .back-to-site {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-to-site a {
            color: #8B7355;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .back-to-site a:hover {
            color: #6F5B44;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .wave-decoration {
                display: none;
            }

            .login-container {
                padding: 2rem 1.5rem;
                width: 95%;
            }

            .logo-box {
                font-size: 1.2rem;
                padding: 0.8rem 2rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }

            .logo-box {
                font-size: 1rem;
                padding: 0.7rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Decorative Wave Line -->
    <div class="wave-decoration">
        <svg viewBox="0 0 200 100" preserveAspectRatio="none">
            <path d="M0,50 Q50,20 100,50 T200,50" stroke-linecap="round"/>
        </svg>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <!-- Logo -->
        <div class="login-logo">
            <img src="{{ asset('assets/Logo_IBW_1B.png') }}" alt="Intimate Bali Wedding Logo" style=" height:100px;">
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login.post') }}" class="login-form">
            @csrf

            <div class="form-group">
                <input 
                    type="text" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    placeholder="Email"
                    class="@error('email') error @enderror"
                >
                @error('email')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="Password"
                    class="@error('password') error @enderror"
                >
                @error('password')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; margin-bottom: 1rem;">
                <input type="checkbox" id="remember" name="remember" style="width: auto; margin-right: 0.5rem;">
                <label for="remember" style="margin: 0; cursor: pointer; font-size: 0.9rem;">Remember me</label>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>
        </form>

        <!-- Back to Site Link -->
        <div class="back-to-site">
            <a href="{{ route('home') }}">← Back to Main Website</a>
        </div>
    </div>
</body>
</html>