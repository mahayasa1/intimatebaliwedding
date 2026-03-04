<style>
    /* Navbar Styles */
    nav#navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        padding-top: 10px;
        background: rgba(0, 0, 0, 0.3);
        min-height: 80px;
        display: flex;
        align-items: center;
    }

    nav#navbar.scrolled {
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }

    .navbar-container {
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 200px 1fr 200px;
        align-items: center;
        height: 100%;
        gap: 2rem;
    }

    /* Logo - Left */
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

    nav#navbar.scrolled .navbar-logo {
        color: #333;
    }

    /* Desktop Menu - Center */
    .navbar-menu {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2.5rem;
    }

    .navbar-menu a {
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: color 0.3s;
        position: relative;
    }

    nav#navbar.scrolled .navbar-menu a {
        color: #333;
    }

    .navbar-menu a:hover {
        color: #D4AF37;
    }

    .navbar-menu a::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 0;
        height: 2px;
        background: #D4AF37;
        transition: width 0.3s;
    }

    .navbar-menu a:hover::after {
        width: 100%;
    }

    /* CTA Button Container - Right */
    .navbar-cta-container {
        justify-self: end;
    }

    /* CTA Button in Navbar */
    .navbar-cta {
        background: transparent;
        color: white !important;
        padding: 0.7rem 1.8rem;
        border: 2.5px solid white;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-block;
        white-space: nowrap;
    }

    .navbar-cta:hover {
        background: white;
        color: #333 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
    }

    .navbar-cta::after {
        display: none;
    }

    /* Scrolled state CTA */
    nav#navbar.scrolled .navbar-cta {
        border-color: #333;
        color: #333 !important;
    }

    nav#navbar.scrolled .navbar-cta:hover {
        background: #333;
        color: white !important;
        border-color: #333;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        z-index: 1001;
        justify-self: end;
    }

    .mobile-menu-btn span {
        width: 25px;
        height: 3px;
        background: white;
        transition: all 0.3s;
        border-radius: 2px;
    }

    nav#navbar.scrolled .mobile-menu-btn span {
        background: #333;
    }

    /* Mobile Menu */
    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 80%;
        max-width: 400px;
        height: 100vh;
        background: white;
        box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
    }

    .mobile-menu.active {
        right: 0;
    }

    .mobile-menu-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .mobile-menu-logo {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
    }

    .mobile-menu-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #333;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .mobile-menu-links {
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .mobile-menu-links a {
        color: #333;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem;
        border-radius: 4px;
        transition: all 0.3s;
    }

    .mobile-menu-links a:hover {
        background: #f8f8f8;
        color: #D4AF37;
    }

    .mobile-menu-cta {
        margin: 1rem 1.5rem;
        background: transparent;
        color: #333;
        padding: 1rem;
        border: 2.5px solid #333;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: all 0.3s;
    }

    .mobile-menu-cta:hover {
        background: #333;
        color: white;
    }

    /* Mobile Menu Overlay */
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        z-index: 999;
    }

    .mobile-menu.active ~ .mobile-menu-overlay {
        opacity: 1;
        visibility: visible;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .navbar-container {
            gap: 1rem;
            grid-template-columns: 180px 1fr 180px;
        }

        .navbar-menu {
            gap: 1.8rem;
        }

        .navbar-menu a {
            font-size: 0.85rem;
        }

        .navbar-cta {
            padding: 0.6rem 1.4rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 1024px) {
        .navbar-container {
            grid-template-columns: 160px 1fr 160px;
        }

        .navbar-menu {
            gap: 1.5rem;
        }

        .navbar-menu a {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 900px) {
        .navbar-container {
            grid-template-columns: 150px 1fr 150px;
        }

        .navbar-menu {
            gap: 1.2rem;
        }

        .navbar-menu a {
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .navbar-cta {
            padding: 0.5rem 1.2rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 768px) {
        nav#navbar {
            min-height: 70px;
        }

        .navbar-container {
            padding: 0 1rem;
            grid-template-columns: 1fr auto;
        }

        .navbar-menu {
            display: none;
        }

        .navbar-cta-container {
            display: none;
        }

        .mobile-menu-btn {
            display: flex;
        }
    }

    @media (min-width: 769px) {
        .mobile-menu {
            display: none;
        }
    }
</style>

<!-- Navigation -->
<nav id="navbar">
    <div class="navbar-container">
        <!-- Logo - Left -->
        <a href="{{ url('/') }}" class="navbar-logo">
            <img src="{{ asset('assets/logo_IBW_1.png') }}" alt="Intimate Bali Wedding Logo" style=" height:65px;">
        </a>

        <!-- Desktop Menu - Center -->
        <div class="navbar-menu">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('packages.public') }}">Packages</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('gallery.public') }}">Gallery</a>
            <a href="{{ route('blogs.public') }}">Blog</a>
        </div>

        <!-- CTA Button - Right -->
        <div class="navbar-cta-container">
            <a href="{{ route('contact') }}" class="navbar-cta">Inquire Now</a>
        </div>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu">
    <div class="mobile-menu-header">
        <div class="mobile-menu-logo">INTIMATE BALI</div>
        <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Close menu">
            ×
        </button>
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