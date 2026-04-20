<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        img { max-width: 100%; height: auto; }

        :root {
            --sb-expanded: 240px;
            --sb-collapsed: 72px;
            --topbar-h: 64px;
            --primary: #8B7355;
            --primary-dark: #6B5644;
            --primary-light: rgba(139,115,85,0.12);
            --speed: 0.28s;
            --radius: 12px;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background: #f0ede8;
            color: #333;
            margin: 0;
            overflow-x: hidden;
        }

        /* ============================================================
           SIDEBAR
           ============================================================ */
        .sidebar {
            position: fixed;
            left: 0; top: 0;
            width: var(--sb-collapsed);
            height: 100vh;
            background: linear-gradient(160deg, #7a6348 0%, #5a4333 100%);
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: width var(--speed) cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }

        .sidebar.expanded { width: var(--sb-expanded); }

        /* Logo area */
        .sidebar-logo {
            flex-shrink: 0;
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 0 14px;
            overflow: hidden;
        }

        .logo-icon img { height: 38px; width: auto; display: block; }
        .logo-box { display: none; }
        .logo-box img { height: 52px; width: auto; display: block; }

        .sidebar.expanded .logo-icon { display: none; }
        .sidebar.expanded .logo-box { display: block; animation: fadeIn 0.2s ease; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Nav scroll area */
        .sidebar-nav-wrap {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 0;
        }

        .sidebar-nav-wrap::-webkit-scrollbar { width: 3px; }
        .sidebar-nav-wrap::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        /* Section label */
        .nav-section-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.38);
            padding: 12px 0 4px 22px;
            white-space: nowrap;
            overflow: hidden;
            opacity: 0;
            height: 0;
            transition: opacity var(--speed) ease, height var(--speed) ease;
        }

        .sidebar.expanded .nav-section-label {
            opacity: 1;
            height: 28px;
        }

        /* Nav items */
        .sidebar-menu {
            padding: 2px 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 0;
            height: 48px;
            color: rgba(255,255,255,0.68);
            text-decoration: none;
            transition: all var(--speed) ease;
            position: relative;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 10px;
            margin-bottom: 2px;
            overflow: hidden;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-menu a.active {
            background: white;
            color: var(--primary);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Icon wrapper — fixed width so it's always centered */
        .sidebar-menu a .nav-icon {
            width: 52px;          /* = var(--sb-collapsed) - 2×10px padding */
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            transition: width var(--speed) ease;
        }

        .sidebar.expanded .sidebar-menu a .nav-icon {
            width: 44px;
        }

        .menu-text {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            opacity: 0;
            max-width: 0;
            transition: opacity var(--speed) ease, max-width var(--speed) ease;
        }

        .sidebar.expanded .menu-text {
            opacity: 1;
            max-width: 160px;
        }

        /* Badge */
        .nav-badge {
            margin-right: 10px;
            background: #e74c3c;
            color: white;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
            opacity: 0;
            transform: scale(0.7);
            transition: all var(--speed) ease;
        }

        .sidebar.expanded .nav-badge { opacity: 1; transform: scale(1); }

        /* Tooltip for collapsed state */
        .sidebar:not(.expanded) .sidebar-menu a[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%; transform: translateY(-50%);
            background: rgba(30,20,10,0.92);
            color: white;
            padding: 5px 10px;
            border-radius: 7px;
            font-size: 0.8rem;
            white-space: nowrap;
            pointer-events: none;
            z-index: 1100;
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        }

        /* Active indicator line */
        .sidebar-menu a.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        /* Footer */
        .sidebar-footer {
            flex-shrink: 0;
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.12);
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 8px;
            height: 44px;
            overflow: hidden;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar-inner {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            color: var(--primary);
            font-weight: 700;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-details {
            flex: 1;
            font-size: 0.78rem;
            min-width: 0;
            opacity: 0;
            max-width: 0;
            overflow: hidden;
            transition: all var(--speed) ease;
            white-space: nowrap;
        }

        .sidebar.expanded .user-details {
            opacity: 1;
            max-width: 200px;
        }

        .user-name { font-weight: 600; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 0.68rem; opacity: 0.6; margin-top: 1px; }

        /* Logout button */
        .logout-btn {
            width: 100%;
            height: 38px;
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 9px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            font-family: 'Work Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all var(--speed) ease;
            overflow: hidden;
        }

        .logout-text {
            white-space: nowrap;
            opacity: 0;
            max-width: 0;
            overflow: hidden;
            transition: all var(--speed) ease;
        }

        .sidebar.expanded .logout-text { opacity: 1; max-width: 100px; }

        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* ============================================================
           MAIN CONTENT
           ============================================================ */
        .main-content {
            margin-left: var(--sb-collapsed);
            min-height: 100vh;
            transition: margin-left var(--speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded { margin-left: var(--sb-expanded); }

        /* ============================================================
           TOP BAR
           ============================================================ */
        .top-bar {
            background: white;
            height: var(--topbar-h);
            padding: 0 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .sidebar-toggle-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary);
            border: none;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary);
            color: white;
        }

        .top-bar-left h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            color: #1a1a1a;
            font-weight: 700;
            letter-spacing: -0.3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0;
        }

        .top-bar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

        .top-icon-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #f5f1ec;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1rem;
            position: relative;
            transition: all 0.2s ease;
        }

        .top-icon-btn:hover { background: var(--primary); color: white; }

        .notif-badge {
            position: absolute;
            top: -3px; right: -3px;
            background: #e74c3c;
            color: white;
            min-width: 16px; height: 16px;
            border-radius: 50%;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid white;
            padding: 0 3px;
        }

        /* ============================================================
           CONTENT AREA
           ============================================================ */
        .content { padding: 1.5rem; }

        /* ============================================================
           ALERTS
           ============================================================ */
        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }

        .alert-success { background: #d4edda; color: #155724; border-left-color: #28a745; }
        .alert-error   { background: #f8d7da; color: #721c24; border-left-color: #dc3545; }

        /* ============================================================
           MOBILE
           ============================================================ */
        .mobile-toggle { display: none; }
        .sidebar-overlay { display: none; }

        @media (max-width: 768px) {
            .sidebar {
                width: var(--sb-expanded) !important;
                transform: translateX(-100%);
                transition: transform var(--speed) ease;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            /* Always show expanded styles on mobile */
            .sidebar .menu-text        { opacity: 1; max-width: 160px; }
            .sidebar .nav-section-label { opacity: 1; height: 28px; }
            .sidebar .user-details     { opacity: 1; max-width: 200px; }
            .sidebar .logout-text      { opacity: 1; max-width: 100px; }
            .sidebar .nav-badge        { opacity: 1; transform: scale(1); }
            .sidebar .logo-icon        { display: none; }
            .sidebar .logo-box         { display: block; }
            .sidebar .nav-icon         { width: 44px; }

            .sidebar-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.45);
                z-index: 999;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                backdrop-filter: blur(2px);
            }

            .sidebar-overlay.active { opacity: 1; pointer-events: auto; }

            .main-content,
            .main-content.expanded { margin-left: 0 !important; }

            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                position: fixed;
                top: 13px; left: 13px;
                z-index: 1100;
                width: 38px; height: 38px;
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: white;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                font-size: 1rem;
                box-shadow: 0 4px 14px rgba(139,115,85,0.35);
                transition: transform 0.2s;
            }

            .mobile-toggle:hover { transform: scale(1.05); }

            .sidebar-toggle-btn { display: none; }

            .top-bar {
                padding: 0 1rem 0 60px; /* room for mobile toggle */
            }

            .top-bar-left h2 { font-size: 1.1rem; }
            .content { padding: 1rem; }
        }

        @media (max-width: 480px) {
            .top-bar-left h2 { font-size: 1rem; }
            .content { padding: 0.875rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Mobile overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Mobile toggle -->
    <button class="mobile-toggle" id="mobile-toggle" aria-label="Buka menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="logo-icon"><img src="{{ asset('assets/Logo_IBW_2B.png') }}" alt="IBW"></div>
            <div class="logo-box"><img src="{{ asset('assets/Logo_IBW_2B.png') }}" alt="IBW"></div>
        </div>

        <!-- Scrollable nav area -->
        <div class="sidebar-nav-wrap">
            <div class="nav-section-label">Main Menu</div>
            <nav class="sidebar-menu">

                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <span class="nav-icon"><i class="fas fa-home"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>

                <a href="{{ route('admin.packages.index') }}"
                   class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}"
                   data-tooltip="Packages">
                    <span class="nav-icon"><i class="fas fa-box-open"></i></span>
                    <span class="menu-text">Packages</span>
                </a>

                <a href="{{ route('admin.galleries.index') }}"
                   class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"
                   data-tooltip="Gallery">
                    <span class="nav-icon"><i class="fas fa-images"></i></span>
                    <span class="menu-text">Gallery</span>
                </a>

                <a href="{{ route('admin.blogs.index') }}"
                   class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                   data-tooltip="Blog">
                    <span class="nav-icon"><i class="fas fa-pencil-alt"></i></span>
                    <span class="menu-text">Blog</span>
                </a>

                <a href="{{ route('admin.enquiries.index') }}"
                   class="{{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}"
                   data-tooltip="Enquiries">
                    <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                    <span class="menu-text">Enquiries</span>
                    @if(isset($stats) && ($stats['new_enquiries'] ?? 0) > 0)
                    <span class="nav-badge">{{ $stats['new_enquiries'] }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                   data-tooltip="Users">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text">Users</span>
                </a>

            </nav>
        </div>

        <!-- Footer -->
        <div class="sidebar-footer">
            <div class="user-row">
                <div class="user-avatar">
                    <div class="user-avatar-inner">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="logout-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="main-content" id="main-content">

        <!-- Top bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <button class="sidebar-toggle-btn" id="sidebar-toggle-btn" aria-label="Toggle sidebar">
                    <i class="fas fa-bars" id="toggle-icon"></i>
                </button>
                <h2>@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="top-bar-right">
                <button class="top-icon-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    @if(isset($stats) && ($stats['new_enquiries'] ?? 0) > 0)
                    <span class="notif-badge">{{ $stats['new_enquiries'] }}</span>
                    @endif
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-times-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </div>
    </main>
    

    <script src="{{ asset('js/image-compressor.js') }}"></script>

    <script>
        const sidebar     = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleBtn   = document.getElementById('sidebar-toggle-btn');
        const toggleIcon  = document.getElementById('toggle-icon');
        const mobileBtn   = document.getElementById('mobile-toggle');
        const overlay     = document.getElementById('sidebar-overlay');

        const isMobile = () => window.innerWidth <= 768;

        /* ── Desktop: restore saved state ── */
        if (!isMobile()) {
            const pinned = localStorage.getItem('sbExpanded') === 'true';
            if (pinned) {
                sidebar.classList.add('expanded');
                mainContent.classList.add('expanded');
                if (toggleIcon) toggleIcon.className = 'fas fa-times';
            }
        }

        /* ── Desktop toggle (pin/unpin) ── */
        toggleBtn?.addEventListener('click', () => {
            if (isMobile()) return;
            const expanded = sidebar.classList.toggle('expanded');
            mainContent.classList.toggle('expanded', expanded);
            if (toggleIcon) toggleIcon.className = expanded ? 'fas fa-times' : 'fas fa-bars';
            localStorage.setItem('sbExpanded', expanded);
        });

        /* ── Desktop hover expand (only when not pinned) ── */
        if (!isMobile()) {
            sidebar.addEventListener('mouseenter', () => {
                if (!sidebar.classList.contains('expanded')) {
                    sidebar.classList.add('expanded');
                    mainContent.classList.add('expanded');
                }
            });
            sidebar.addEventListener('mouseleave', () => {
                if (localStorage.getItem('sbExpanded') !== 'true') {
                    sidebar.classList.remove('expanded');
                    mainContent.classList.remove('expanded');
                }
            });
        }

        /* ── Mobile open / close ── */
        function openMobile() {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeMobile() {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileBtn?.addEventListener('click', openMobile);
        overlay?.addEventListener('click', closeMobile);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobile(); });

        /* Close on nav link click (mobile) */
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => { if (isMobile()) closeMobile(); });
        });

        /* ── Resize handler ── */
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (!isMobile()) {
                    closeMobile();
                    const pinned = localStorage.getItem('sbExpanded') === 'true';
                    sidebar.classList.toggle('expanded', pinned);
                    mainContent.classList.toggle('expanded', pinned);
                    if (toggleIcon) toggleIcon.className = pinned ? 'fas fa-times' : 'fas fa-bars';
                }
            }, 200);
        });

        /* ── Auto-dismiss alerts ── */
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.alert').forEach(el => {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-10px)';
                    setTimeout(() => el.remove(), 420);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>