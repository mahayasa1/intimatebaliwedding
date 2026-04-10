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
            --sb-expanded: 260px;
            --sb-collapsed: 68px;
            --topbar-h: 68px;
            --primary: #8B7355;
            --primary-dark: #6B5644;
            --speed: 0.28s;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background: #f4f4f4;
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
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: width var(--speed) cubic-bezier(0.4, 0, 0.2, 1);
            -webkit-overflow-scrolling: touch;
        }

        .sidebar.expanded { width: var(--sb-expanded); }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        /* Logo area */
        .sidebar-logo {
            padding: 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            min-height: var(--topbar-h);
            display: flex; align-items: center; justify-content: center;
        }

        .logo-icon img { height: 44px; width: auto; }
        .logo-box { display: none; }
        .logo-box img { height: 64px; width: auto; }

        .sidebar.expanded .logo-icon { display: none; }
        .sidebar.expanded .logo-box { display: inline-block; animation: fadeIn 0.25s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        /* Nav items */
        .sidebar-menu { padding: 1.25rem 0; }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.85rem 1.1rem;
            color: rgba(255,255,255,0.72);
            text-decoration: none;
            transition: all var(--speed) ease;
            position: relative;
            font-weight: 500;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .sidebar-menu a::before {
            content: '';
            position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 0;
            background: white;
            border-radius: 0 4px 4px 0;
            transition: height var(--speed) ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-menu a:hover::before { height: 60%; }

        .sidebar-menu a.active {
            background: white;
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-menu a.active::before { height: 100%; background: var(--primary); }

        .sidebar-menu a .icon {
            width: 20px;
            text-align: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .menu-text {
            opacity: 0;
            transform: translateX(-8px);
            transition: all var(--speed) ease;
            overflow: hidden;
        }

        .sidebar.expanded .menu-text { opacity: 1; transform: translateX(0); }

        /* Badge */
        .sidebar-menu a .badge {
            margin-left: auto;
            background: #e74c3c;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-size: 0.68rem;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
            opacity: 0;
            transform: scale(0.8);
            transition: all var(--speed) ease;
            flex-shrink: 0;
        }

        .sidebar.expanded .sidebar-menu a .badge { opacity: 1; transform: scale(1); }

        /* Tooltip */
        .sidebar:not(.expanded) .sidebar-menu a[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 8px);
            top: 50%; transform: translateY(-50%);
            background: rgba(0,0,0,0.88);
            color: white;
            padding: 0.4rem 0.65rem;
            border-radius: 6px;
            font-size: 0.82rem;
            white-space: nowrap;
            pointer-events: none;
            z-index: 1001;
        }

        /* Footer */
        .sidebar-footer {
            position: sticky;
            bottom: 0;
            padding: 1rem 1.1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.12);
        }

        .user-info-sidebar {
            display: flex; align-items: center; gap: 0.75rem;
            margin-bottom: 0.8rem; overflow: hidden;
        }

        .user-avatar-sidebar {
            width: 36px; height: 36px;
            border-radius: 50%; background: white;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-weight: 700; font-size: 0.9rem;
            flex-shrink: 0;
        }

        .user-details-sidebar {
            flex: 1; font-size: 0.8rem; min-width: 0;
            opacity: 0; transform: translateX(-8px);
            transition: all var(--speed) ease;
        }

        .sidebar.expanded .user-details-sidebar { opacity: 1; transform: translateX(0); }

        .user-name-sidebar {
            font-weight: 600; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }

        .logout-btn-sidebar {
            width: 100%; padding: 0.5rem;
            background: rgba(255,255,255,0.12);
            color: white;
            border: 1px solid rgba(255,255,255,0.22);
            border-radius: 7px;
            cursor: pointer; font-weight: 600;
            transition: all var(--speed) ease;
            font-size: 0.82rem; font-family: 'Work Sans', sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }

        .logout-btn-sidebar .logout-text {
            opacity: 0; max-width: 0;
            overflow: hidden; transition: all var(--speed) ease;
            white-space: nowrap;
        }

        .sidebar.expanded .logout-btn-sidebar .logout-text { opacity: 1; max-width: 120px; }

        .logout-btn-sidebar:hover {
            background: rgba(255,255,255,0.22);
            transform: translateY(-2px);
        }

        /* ============================================================
           MAIN CONTENT
           ============================================================ */
        .main-content {
            margin-left: var(--sb-collapsed);
            min-height: 100vh;
            background: #f4f4f4;
            transition: margin-left var(--speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded { margin-left: var(--sb-expanded); }

        /* ============================================================
           TOP BAR
           ============================================================ */
        .top-bar {
            background: white;
            padding: 1rem 1.75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 100;
            min-height: var(--topbar-h);
        }

        .top-bar-left {
            display: flex; align-items: center; gap: 1rem;
            flex: 1; min-width: 0;
        }

        .sidebar-toggle-btn {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white; border: none; cursor: pointer;
            font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(139,115,85,0.2);
            flex-shrink: 0;
        }

        .sidebar-toggle-btn:hover { transform: scale(1.06); box-shadow: 0 4px 12px rgba(139,115,85,0.3); }
        .sidebar-toggle-btn:active { transform: scale(0.96); }

        .top-bar-left h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem; color: #1a1a1a;
            font-weight: 700; letter-spacing: -0.5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .top-bar-right { display: flex; align-items: center; gap: 1rem; flex-shrink: 0; }

        .notification-icon {
            position: relative; width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #e74c3c; font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .notification-icon:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(231,76,60,0.2); }

        .notification-badge {
            position: absolute; top: -4px; right: -4px;
            background: #e74c3c; color: white;
            min-width: 18px; height: 18px;
            border-radius: 50%; font-size: 0.65rem;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; border: 2px solid white;
            padding: 0 3px;
        }

        /* ============================================================
           CONTENT AREA
           ============================================================ */
        .content { padding: 1.75rem; }

        /* ============================================================
           ALERTS
           ============================================================ */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            border-left: 4px solid;
            font-weight: 500;
            display: flex; align-items: center; gap: 0.65rem;
            animation: slideDown 0.35s ease;
            word-break: break-word;
        }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }

        .alert-success { background: #d4edda; color: #155724; border-left-color: #28a745; }
        .alert-error   { background: #f8d7da; color: #721c24; border-left-color: #dc3545; }

        /* ============================================================
           MOBILE
           ============================================================ */
        .mobile-toggle { display: none; }
        .sidebar-overlay { display: none; }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sb-expanded);
                transition: transform var(--speed) ease;
            }

            .sidebar.mobile-active { transform: translateX(0); }
            .sidebar.expanded { width: var(--sb-expanded); }

            /* Expanded text always visible on mobile */
            .sidebar.mobile-active .menu-text,
            .sidebar.mobile-active .user-details-sidebar,
            .sidebar.mobile-active .logout-btn-sidebar .logout-text { opacity: 1; transform: none; max-width: 120px; }

            .sidebar.mobile-active .logo-icon { display: none; }
            .sidebar.mobile-active .logo-box { display: inline-block; }
            .sidebar.mobile-active .sidebar-menu a .badge { opacity: 1; transform: scale(1); }

            .sidebar-overlay {
                display: block;
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                opacity: 0; pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.active { opacity: 1; pointer-events: auto; }

            .main-content,
            .main-content.expanded { margin-left: 0; }

            .mobile-toggle {
                display: block;
                position: fixed; top: 0.85rem; left: 0.85rem;
                z-index: 1100;
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: white; border: none;
                padding: 0.55rem 0.75rem;
                border-radius: 8px; cursor: pointer;
                font-size: 1.1rem;
                box-shadow: 0 4px 12px rgba(139,115,85,0.3);
                transition: all 0.3s;
            }

            .mobile-toggle:hover { transform: scale(1.05); }

            .sidebar-toggle-btn { display: none; }

            .top-bar { padding: 0.875rem 1rem; min-height: 60px; }
            .top-bar-left h2 { font-size: 1.2rem; }
            .content { padding: 1.25rem; }
        }

        @media (max-width: 480px) {
            :root { --topbar-h: 58px; }
            .mobile-toggle { top: 0.7rem; left: 0.7rem; padding: 0.5rem 0.65rem; }
            .top-bar-left h2 { font-size: 1.05rem; }
            .content { padding: 1rem; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile toggle button -->
    <button class="mobile-toggle" id="mobile-toggle" aria-label="Open menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><img src="{{ asset('assets/Logo_IBW_1.png') }}" alt="IBW"></div>
            <div class="logo-box"><img src="{{ asset('assets/Logo_IBW_1.png') }}" alt="IBW"></div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               data-tooltip="Dashboard">
                <span class="icon"><i class="fas fa-home"></i></span>
                <span class="menu-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.packages.index') }}"
               class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}"
               data-tooltip="Packages">
                <span class="icon"><i class="fas fa-box"></i></span>
                <span class="menu-text">Packages</span>
            </a>
            <a href="{{ route('admin.galleries.index') }}"
               class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"
               data-tooltip="Gallery">
                <span class="icon"><i class="fas fa-images"></i></span>
                <span class="menu-text">Gallery</span>
            </a>
            <a href="{{ route('admin.blogs.index') }}"
               class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
               data-tooltip="Blog">
                <span class="icon"><i class="fas fa-blog"></i></span>
                <span class="menu-text">Blog</span>
            </a>
            <a href="{{ route('admin.enquiries.index') }}"
               class="{{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}"
               data-tooltip="Enquiries">
                <span class="icon"><i class="fas fa-envelope"></i></span>
                <span class="menu-text">Enquiries</span>
                @if(isset($stats) && ($stats['new_enquiries'] ?? 0) > 0)
                <span class="badge">{{ $stats['new_enquiries'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               data-tooltip="Users">
                <span class="icon"><i class="fas fa-user"></i></span>
                <span class="menu-text">Users</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info-sidebar">
                <div class="user-avatar-sidebar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-details-sidebar">
                    <div class="user-name-sidebar">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.72rem; opacity:0.7; margin-top:1px;">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn-sidebar">
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
                <button class="sidebar-toggle-btn" id="sidebar-toggle-btn" title="Toggle Sidebar" aria-label="Toggle sidebar">
                    <i class="fas fa-bars" id="toggle-icon"></i>
                </button>
                <h2>@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="top-bar-right">
                <div class="notification-icon" title="Notifications">
                    <i class="fas fa-bell"></i>
                    @if(isset($stats) && ($stats['new_enquiries'] ?? 0) > 0)
                    <span class="notification-badge">{{ $stats['new_enquiries'] }}</span>
                    @endif
                </div>
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

    <script>
        const sidebar      = document.getElementById('sidebar');
        const mainContent  = document.getElementById('main-content');
        const toggleBtn    = document.getElementById('sidebar-toggle-btn');
        const toggleIcon   = document.getElementById('toggle-icon');
        const mobileToggle = document.getElementById('mobile-toggle');
        const overlay      = document.getElementById('sidebar-overlay');
        const isMobile     = () => window.innerWidth <= 768;

        // Desktop: restore saved state
        if (!isMobile()) {
            const saved = localStorage.getItem('sbExpanded') === 'true';
            if (saved) {
                sidebar.classList.add('expanded');
                mainContent.classList.add('expanded');
                if (toggleIcon) toggleIcon.className = 'fas fa-times';
            }
        }

        // Desktop toggle
        toggleBtn?.addEventListener('click', function() {
            if (isMobile()) return;
            const expanded = sidebar.classList.toggle('expanded');
            mainContent.classList.toggle('expanded', expanded);
            if (toggleIcon) toggleIcon.className = expanded ? 'fas fa-times' : 'fas fa-bars';
            localStorage.setItem('sbExpanded', expanded);
        });

        // Desktop hover (only if NOT pinned)
        if (!isMobile()) {
            sidebar.addEventListener('mouseenter', () => {
                if (!sidebar.classList.contains('expanded')) {
                    sidebar.classList.add('expanded');
                    mainContent.classList.add('expanded');
                }
            });

            sidebar.addEventListener('mouseleave', () => {
                const pinned = localStorage.getItem('sbExpanded') === 'true';
                if (!pinned) {
                    sidebar.classList.remove('expanded');
                    mainContent.classList.remove('expanded');
                }
            });
        }

        // Mobile open/close
        function openMobile() {
            sidebar.classList.add('mobile-active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobile() {
            sidebar.classList.remove('mobile-active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileToggle?.addEventListener('click', openMobile);
        overlay?.addEventListener('click', closeMobile);
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && isMobile()) closeMobile(); });

        // Close on link click on mobile
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => { if (isMobile()) closeMobile(); });
        });

        // Resize handler
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (!isMobile()) {
                    closeMobile();
                    const saved = localStorage.getItem('sbExpanded') === 'true';
                    sidebar.classList.toggle('expanded', saved);
                    mainContent.classList.toggle('expanded', saved);
                    if (toggleIcon) toggleIcon.className = saved ? 'fas fa-times' : 'fas fa-bars';
                } else {
                    sidebar.classList.remove('expanded');
                    mainContent.classList.remove('expanded');
                }
            }, 250);
        });

        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-12px)';
                    setTimeout(() => alert.remove(), 420);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>