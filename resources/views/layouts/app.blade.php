<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Wedding Venue - Intimate Bali Wedding')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
            padding-top: 80px; /* Space for fixed navbar */
        }

        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        /* Main Layout */
        .main-content {
            min-height: calc(100vh - 400px);
        }

        /* Utility Classes */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
        }

        /* Sections */
        .section {
            padding: 5rem 2rem;
        }

        @media (max-width: 768px) {
            .section {
                padding: 3rem 1rem;
            }
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #D4AF37;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            max-width: 700px;
            margin: 0 auto 3rem;
            line-height: 1.8;
        }

        /* Buttons */
        .btn-primary {
            background: #D4AF37;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background: #B8941F;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        @media (max-width: 768px) {
            .btn-primary {
                padding: 0.6rem 1.5rem;
                font-size: 0.85rem;
            }
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Include Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Include Footer -->
    @include('components.footer')

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuClose = document.getElementById('mobile-menu-close');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }

            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }

            // Close menu when clicking on a link
            const mobileLinks = mobileMenu?.querySelectorAll('a');
            mobileLinks?.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            });
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navbarHeight = document.getElementById('navbar').offsetHeight;
                    const targetPosition = target.offsetTop - navbarHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>