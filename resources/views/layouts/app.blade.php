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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ---- Reset ---- */
        *, *::before, *::after { box-sizing: border-box; }
        img { max-width: 100%; height: auto; display: block; }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* ---- Main Layout ---- */
        .main-content { min-height: calc(100vh - 400px); }

        /* ============================================================
           NAVBAR
           ============================================================ */
        nav#navbar {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            z-index: 1000;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            background: rgba(0,0,0,0.3);
            min-height: 80px;
            display: flex;
            align-items: center;
        }

        nav#navbar.scrolled {
            background: rgba(255,255,255,0.98);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .navbar-container {
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 180px 1fr 180px;
            align-items: center;
            gap: 2rem;
        }

        /* Logo */
        .navbar-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            transition: color 0.3s;
            z-index: 1001;
            justify-self: start;
        }

        nav#navbar.scrolled .navbar-logo { color: #333; }

        .navbar-logo img {
            height: 80px;
            width: auto;
            object-fit: contain;
            transition: filter 0.3s ease;
        }

        /* Default (navbar transparan) → putih pekat */
        nav#navbar:not(.scrolled) .navbar-logo img {
            filter:
                brightness(0)
                invert(1)
                contrast(200%)
                saturate(0%);
        }

        /* Saat scroll → hitam */
        nav#navbar.scrolled .navbar-logo img {
            filter:
                brightness(0)
                contrast(120%);
        }

        /* Desktop menu */
        .navbar-menu {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.3s;
            position: relative;
            white-space: nowrap;
        }

        nav#navbar.scrolled .navbar-menu a { color: #333; }
        .navbar-menu a:hover { color: #D4AF37; }

        .navbar-menu a::after {
            content: '';
            position: absolute;
            bottom: -5px; left: 0;
            width: 0; height: 2px;
            background: #D4AF37;
            transition: width 0.3s;
        }

        .navbar-menu a:hover::after { width: 100%; }

        /* CTA */
        .navbar-cta-container { justify-self: end; }

        .navbar-cta {
            background: transparent;
            color: white !important;
            padding: 0.65rem 1.6rem;
            border: 2px solid white;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 0.82rem;
            display: inline-block;
            white-space: nowrap;
        }

        .navbar-cta:hover {
            background: white;
            color: #333 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.3);
        }

        .navbar-cta::after { display: none; }

        nav#navbar.scrolled .navbar-cta { border-color: #333; color: #333 !important; }
        nav#navbar.scrolled .navbar-cta:hover { background: #333; color: white !important; }

        /* Mobile button */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 1101;
            justify-self: end;
        }

        .mobile-menu-btn span {
            width: 25px; height: 3px;
            background: white;
            transition: all 0.3s;
            border-radius: 2px;
            display: block;
        }

        nav#navbar.scrolled .mobile-menu-btn span { background: #333; }

        /* Mobile sidebar */
        .mobile-menu {
            position: fixed;
            top: 0; right: -100%;
            width: 80%;
            max-width: 380px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 20px rgba(0,0,0,0.15);
            transition: right 0.3s ease;
            z-index: 1099;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .mobile-menu.active { right: 0; }

        .mobile-menu-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .mobile-menu-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
        }

        .mobile-menu-close {
            background: none; border: none;
            font-size: 1.8rem; color: #333;
            cursor: pointer;
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            line-height: 1;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .mobile-menu-close:hover { background: #f0f0f0; }

        .mobile-menu-links {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .mobile-menu-links a {
            color: #333;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.875rem 1rem;
            border-radius: 6px;
            transition: all 0.2s;
            border-bottom: 1px solid #f5f5f5;
        }

        .mobile-menu-links a:last-child { border-bottom: none; }
        .mobile-menu-links a:hover { background: #f8f8f8; color: #D4AF37; }

        .mobile-menu-cta {
            margin: 0.5rem 1.5rem 1.5rem;
            background: #D4AF37;
            color: white;
            padding: 1rem;
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }

        .mobile-menu-cta:hover { background: #B8941F; color: white; }

        /* ============================================================
           HERO SECTIONS
           ============================================================ */
        /* All hero sections flush against navbar */
        .hero-section,
        .about-hero,
        .blog-hero,
        .contact-hero,
        .packages-hero,
        .gallery-hero,
        .package-detail-hero,
        .sub-hero,
        .page-hero {
            margin-top: 0 !important;
            padding-top: 80px !important;
        }

        .hero-section {
            position: relative;
            height: 100vh;
            min-height: 500px;
            overflow: hidden;
        }

        .about-hero,
        .blog-hero,
        .contact-hero,
        .gallery-hero { height: calc(50vh + 80px); min-height: 320px; }

        .packages-hero { height: calc(50vh + 80px); min-height: 320px; }

        .package-detail-hero,
        .page-hero { height: calc(40vh + 80px); min-height: 280px; }

        /* ============================================================
           UTILITY
           ============================================================ */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .section { padding: 5rem 2rem; }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #D4AF37;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            max-width: 700px;
            margin: 0 auto 3rem;
            line-height: 1.8;
        }

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
            box-shadow: 0 4px 12px rgba(212,175,55,0.3);
            color: white;
        }

        html { scroll-behavior: smooth; }

        /* ============================================================
           RESPONSIVE BREAKPOINTS
           ============================================================ */
        @media (max-width: 1100px) {
            .navbar-container {
                grid-template-columns: 160px 1fr 160px;
                gap: 1rem;
            }
            .navbar-menu { gap: 1.5rem; }
            .navbar-menu a { font-size: 0.82rem; }
            .navbar-cta { padding: 0.6rem 1.2rem; font-size: 0.78rem; }
        }

        @media (max-width: 900px) {
            .navbar-container { grid-template-columns: 140px 1fr 140px; }
            .navbar-menu { gap: 1.2rem; }
            .navbar-menu a { font-size: 0.78rem; letter-spacing: 0.5px; }
            .navbar-cta { padding: 0.5rem 1rem; font-size: 0.75rem; }
        }

        @media (max-width: 768px) {
            nav#navbar { min-height: 68px; }

            .navbar-container {
                padding: 0 1rem;
                grid-template-columns: 1fr auto;
            }

            .navbar-menu { display: none; }
            .navbar-cta-container { display: none; }
            .mobile-menu-btn { display: flex; }

            .hero-section { height: 70vh; min-height: 400px; }

            .about-hero,
            .blog-hero,
            .contact-hero,
            .packages-hero,
            .gallery-hero { height: calc(42vh + 68px); min-height: 280px; }

            .package-detail-hero,
            .page-hero { height: calc(34vh + 68px); min-height: 240px; }

            .section { padding: 3rem 1rem; }
            .section-title { font-size: 2rem; }
            .container { padding: 0 1rem; }
        }

        @media (max-width: 480px) {
            .navbar-logo img { height: 48px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav id="navbar">
        <div class="navbar-container">
            <a href="{{ url('/') }}" class="navbar-logo">
                <img src="{{ asset('assets/Logo_IBW_2B.png') }}" alt="Intimate Bali Wedding">
            </a>

            <div class="navbar-menu">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('packages.public') }}">Packages</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('gallery.public') }}">Gallery</a>
                <a href="{{ route('blogs.public') }}">Blog</a>
            </div>

            <div class="navbar-cta-container">
                <a href="{{ route('contact') }}" class="navbar-cta">Inquire Now</a>
            </div>

            <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
        <div class="mobile-menu-header">
            <div class="mobile-menu-logo">INTIMATE BALI</div>
            <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Close menu">×</button>
        </div>
        <div class="mobile-menu-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('packages.public') }}">Packages</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('gallery.public') }}">Gallery</a>
            <a href="{{ route('blogs.public') }}">Blog</a>
        </div>
        <a href="{{ route('contact') }}" class="mobile-menu-cta">Inquire Now</a>
    </div>
    <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <script src="https://snapwidget.com/js/snapwidget.js"></script>

    <script>
        // ---- Mobile Menu ----
        const mobileMenuBtn  = document.getElementById('mobile-menu-btn');
        const mobileMenu     = document.getElementById('mobile-menu');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileOverlay  = document.getElementById('mobile-menu-overlay');

        function openMobileMenu() {
            mobileMenu.classList.add('active');
            mobileMenu.setAttribute('aria-hidden', 'false');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            mobileMenu.setAttribute('aria-hidden', 'true');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileMenuBtn?.addEventListener('click', openMobileMenu);
        mobileMenuClose?.addEventListener('click', closeMobileMenu);
        mobileOverlay?.addEventListener('click', closeMobileMenu);

        // Close on link click
        document.querySelectorAll('.mobile-menu-links a, .mobile-menu-cta').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // ESC key
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobileMenu(); });

        // ---- Navbar scroll ----
        const navbar = document.getElementById('navbar');
        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const current = window.scrollY;
            if (current > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            lastScroll = current;
        }, { passive: true });

        // ---- Smooth scroll anchors ----
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const navbarHeight = navbar.offsetHeight;
                    window.scrollTo({ top: target.offsetTop - navbarHeight, behavior: 'smooth' });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>