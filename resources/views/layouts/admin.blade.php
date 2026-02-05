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
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-width-expanded: 260px;
            --sidebar-width-collapsed: 70px;
            --topbar-height: 70px;
            --primary: #8B7355;
            --primary-dark: #6B5644;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background: #f5f5f5;
            color: #333;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width-collapsed);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.expanded {
            width: var(--sidebar-width-expanded);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        /* Sidebar Logo */
        .sidebar-logo {
            padding: 1.75rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: var(--topbar-height);
        }

        .logo-icon {
            font-size: 1.5rem;
            transition: all var(--transition-speed) ease;
        }

        .sidebar.expanded .logo-icon {
            display: none;
        }

        .logo-box {
            display: none;
            padding: 0.65rem 2.25rem;
            border: 2px solid white;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            transition: all var(--transition-speed) ease;
            white-space: nowrap;
        }

        .sidebar.expanded .logo-box {
            display: inline-block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-box:hover {
            background: white;
            color: var(--primary);
            transform: scale(1.05);
        }

        /* Sidebar Menu */
        .sidebar-menu {
            padding: 2rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.95rem 1.25rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all var(--transition-speed) ease;
            position: relative;
            font-weight: 500;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .sidebar-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: white;
            border-radius: 0 4px 4px 0;
            transition: height var(--transition-speed) ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-menu a:hover::before {
            height: 60%;
        }

        .sidebar-menu a.active {
            background: white;
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-menu a.active::before {
            height: 100%;
            background: var(--primary);
        }

        .sidebar-menu a .icon {
            width: 22px;
            text-align: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .menu-text {
            opacity: 0;
            transform: translateX(-10px);
            transition: all var(--transition-speed) ease;
        }

        .sidebar.expanded .menu-text {
            opacity: 1;
            transform: translateX(0);
        }

        .sidebar-menu a .badge {
            margin-left: auto;
            background: #e74c3c;
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(231, 76, 60, 0.3);
            opacity: 0;
            transform: scale(0.8);
            transition: all var(--transition-speed) ease;
        }

        .sidebar.expanded .sidebar-menu a .badge {
            opacity: 1;
            transform: scale(1);
        }

        /* Tooltip for collapsed state */
        .sidebar:not(.expanded) .sidebar-menu a {
            position: relative;
        }

        .sidebar:not(.expanded) .sidebar-menu a::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            margin-left: 0.5rem;
            transition: opacity 0.2s ease;
            z-index: 1001;
        }

        .sidebar:not(.expanded) .sidebar-menu a:hover::after {
            opacity: 1;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .user-info-sidebar {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .user-avatar-sidebar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .user-details-sidebar {
            flex: 1;
            font-size: 0.85rem;
            min-width: 0;
            opacity: 0;
            transform: translateX(-10px);
            transition: all var(--transition-speed) ease;
        }

        .sidebar.expanded .user-details-sidebar {
            opacity: 1;
            transform: translateX(0);
        }

        .user-name-sidebar {
            font-weight: 600;
            margin-bottom: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-sidebar {
            opacity: 0.7;
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .logout-btn-sidebar {
            width: 100%;
            padding: 0.6rem;
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            font-size: 0.85rem;
            font-family: 'Work Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar:not(.expanded) .logout-btn-sidebar {
            padding: 0.6rem 0.5rem;
        }

        .logout-btn-sidebar .logout-icon {
            font-size: 1.1rem;
        }

        .logout-btn-sidebar .logout-text {
            opacity: 0;
            max-width: 0;
            overflow: hidden;
            transition: all var(--transition-speed) ease;
        }

        .sidebar.expanded .logout-btn-sidebar .logout-text {
            opacity: 1;
            max-width: 100px;
        }

        .logout-btn-sidebar:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width-collapsed);
            min-height: 100vh;
            background: #f5f5f5;
            transition: margin-left var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded {
            margin-left: var(--sidebar-width-expanded);
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 1.25rem 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            height: var(--topbar-height);
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(139, 115, 85, 0.2);
        }

        .sidebar-toggle-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
        }

        .sidebar-toggle-btn:active {
            transform: scale(0.95);
        }

        .top-bar-left h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.65rem;
            color: #1a1a1a;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .notification-icon {
            position: relative;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #e74c3c;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .notification-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.4);
        }

        .upcoming-wedding {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 0.85rem 1.35rem;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border: 1px solid #e8e8e8;
            transition: all 0.3s ease;
        }

        .upcoming-wedding:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }

        .upcoming-wedding-title {
            font-size: 0.75rem;
            color: #999;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .upcoming-wedding-couple {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 0.95rem;
            margin-bottom: 0.2rem;
        }

        .upcoming-wedding-date {
            font-size: 0.8rem;
            color: #666;
        }

        /* Content */
        .content {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
        }

        .sidebar-overlay {
            display: none;
        }

        /* Alert Messages */
        .alert {
            padding: 1.15rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideInDown 0.4s ease;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left-color: #dc3545;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar-toggle-btn {
                width: 40px;
                height: 40px;
            }

            .content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width-expanded);
            }

            .sidebar.mobile-active {
                transform: translateX(0);
            }

            .sidebar.expanded {
                width: var(--sidebar-width-expanded);
            }

            .sidebar-overlay {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.active {
                opacity: 1;
                pointer-events: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .sidebar-toggle-btn {
                display: none;
            }

            .top-bar {
                padding: 1rem 1.25rem;
                flex-wrap: wrap;
                gap: 1rem;
                height: auto;
            }

            .top-bar-left h2 {
                font-size: 1.35rem;
            }

            .top-bar-right {
                gap: 0.75rem;
            }

            .upcoming-wedding {
                display: none;
            }

            .content {
                padding: 1.25rem;
            }

            .notification-icon {
                width: 40px;
                height: 40px;
            }

            /* Mobile Toggle Button */
            .mobile-toggle {
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1100;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                color: white;
                border: none;
                padding: 0.65rem 0.85rem;
                border-radius: 10px;
                cursor: pointer;
                font-size: 1.25rem;
                box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
                transition: all 0.3s ease;
            }

            .mobile-toggle:hover {
                transform: scale(1.05);
            }

            .mobile-toggle:active {
                transform: scale(0.95);
            }
        }

        @media (max-width: 480px) {
            .top-bar-left h2 {
                font-size: 1.15rem;
            }

            .content {
                padding: 1rem;
            }

            .alert {
                padding: 1rem;
                font-size: 0.9rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" id="mobile-toggle">☰</button>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">🏠</div>
            <div class="logo-box">LOGO</div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" 
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               data-tooltip="Dashboard">
                <span class="icon">🏠</span>
                <span class="menu-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.packages.index') }}" 
               class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}"
               data-tooltip="Packages">
                <span class="icon">📦</span>
                <span class="menu-text">Packages</span>
            </a>
            <a href="{{ route('admin.services.index') }}" 
               class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
               data-tooltip="Services">
                <span class="icon">⚙️</span>
                <span class="menu-text">Services</span>
            </a>
            <a href="{{ route('admin.galleries.index') }}" 
               class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"
               data-tooltip="Gallery">
                <span class="icon">🖼️</span>
                <span class="menu-text">Gallery</span>
            </a>
            <a href="{{ route('admin.enquiries.index') }}" 
               class="{{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}"
               data-tooltip="Enquiries">
                <span class="icon">✉️</span>
                <span class="menu-text">Enquiries</span>
                @if(isset($stats) && $stats['new_enquiries'] > 0)
                <span class="badge">{{ $stats['new_enquiries'] }}</span>
                @endif
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info-sidebar">
                <div class="user-avatar-sidebar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-details-sidebar">
                    <div class="user-name-sidebar">{{ auth()->user()->name }}</div>
                    <div class="user-role-sidebar">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn-sidebar">
                    <span class="logout-icon">🚪</span>
                    <span class="logout-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <button class="sidebar-toggle-btn" id="sidebar-toggle-btn" title="Toggle Sidebar">
                    <span id="toggle-icon">☰</span>
                </button>
                <h2>@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="top-bar-right">
                <div class="notification-icon">
                    🔔
                    @if(isset($stats) && $stats['new_enquiries'] > 0)
                    <span class="notification-badge">{{ $stats['new_enquiries'] }}</span>
                    @endif
                </div>

                <div class="upcoming-wedding">
                    <div class="upcoming-wedding-title">Upcoming Wedding</div>
                    <div class="upcoming-wedding-couple">Nyoman & Dayu</div>
                    <div class="upcoming-wedding-date">22 December 2022</div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
            <div class="alert alert-success">
                <span style="font-size: 1.25rem;">✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <span style="font-size: 1.25rem;">✗</span>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Get elements
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
        const toggleIcon = document.getElementById('toggle-icon');
        const mobileToggle = document.getElementById('mobile-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        // Check if sidebar should be expanded by default (from localStorage)
        const sidebarState = localStorage.getItem('sidebarExpanded');
        if (sidebarState === 'true') {
            sidebar.classList.add('expanded');
            mainContent.classList.add('expanded');
            if (toggleIcon) toggleIcon.textContent = '✕';
        }

        // Desktop: Toggle sidebar with button
        sidebarToggleBtn?.addEventListener('click', function() {
            const isExpanded = sidebar.classList.toggle('expanded');
            mainContent.classList.toggle('expanded');
            
            // Update icon
            if (toggleIcon) {
                toggleIcon.textContent = isExpanded ? '✕' : '☰';
            }
            
            // Save state
            localStorage.setItem('sidebarExpanded', isExpanded);
        });

        // Desktop: Expand sidebar on hover (only if not manually toggled)
        if (window.innerWidth > 768) {
            sidebar.addEventListener('mouseenter', function() {
                if (!sidebar.classList.contains('expanded')) {
                    sidebar.classList.add('expanded');
                    mainContent.classList.add('expanded');
                }
            });

            sidebar.addEventListener('mouseleave', function() {
                // Only collapse if not manually pinned
                const isPinned = localStorage.getItem('sidebarExpanded') === 'true';
                if (!isPinned) {
                    sidebar.classList.remove('expanded');
                    mainContent.classList.remove('expanded');
                }
            });
        }

        // Mobile: Toggle sidebar
        function toggleMobileSidebar() {
            sidebar.classList.toggle('mobile-active');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('mobile-active') ? 'hidden' : '';
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileToggle?.addEventListener('click', toggleMobileSidebar);
        sidebarOverlay?.addEventListener('click', closeMobileSidebar);

        // Close sidebar when clicking a link on mobile
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', closeMobileSidebar);
            });
        }

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    closeMobileSidebar();
                    // Re-apply saved state for desktop
                    const savedState = localStorage.getItem('sidebarExpanded') === 'true';
                    if (savedState) {
                        sidebar.classList.add('expanded');
                        mainContent.classList.add('expanded');
                        if (toggleIcon) toggleIcon.textContent = '✕';
                    }
                } else {
                    // On mobile, reset to collapsed
                    sidebar.classList.remove('expanded');
                    mainContent.classList.remove('expanded');
                }
            }, 250);
        });
    </script>

    @stack('scripts')
</body>
</html>