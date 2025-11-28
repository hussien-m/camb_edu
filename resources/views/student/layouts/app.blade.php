<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e3a8a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title') - Student Portal</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #003366;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --sidebar-width: 280px;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--primary);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.4);
            cursor: pointer;
            transition: all 0.3s;
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            pointer-events: none;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0, 0, 0, 0.15);
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .site-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 12px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .site-logo img {
            width: 90%;
            height: 90%;
            object-fit: contain;
        }

        .site-logo-text {
            font-size: 32px;
            font-weight: 900;
            color: var(--primary);
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        .sidebar-header p {
            margin: 5px 0 0;
            font-size: 0.8rem;
            opacity: 0.8;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-section-title {
            padding: 20px 25px 10px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
            font-weight: 700;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            position: relative;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #0ea5e9;
            padding-left: 30px;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: #0ea5e9;
            font-weight: 700;
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            right: 20px;
            width: 6px;
            height: 6px;
            background: #0ea5e9;
            border-radius: 50%;
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 20px 25px;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .top-header h2 {
            margin: 0;
            font-weight: 800;
            color: var(--primary);
            font-size: 1.6rem;
            letter-spacing: -0.5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--dark);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Cards */
        .dashboard-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .dashboard-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.3px;
        }

        /* Status Badge */
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            :root {
                --sidebar-width: 260px;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding: 75px 15px 15px;
            }

            .top-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 20px 15px;
            }

            .top-header h2 {
                font-size: 1.3rem;
            }

            .user-details {
                align-items: center;
            }

            .dashboard-card {
                padding: 20px;
            }

            .card-title {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 70px 10px 10px;
            }

            .dashboard-card {
                padding: 15px;
                border-radius: 12px;
            }

            .top-header {
                padding: 15px;
                border-radius: 12px;
            }

            .menu-item {
                padding: 12px 20px;
                font-size: 0.9rem;
            }

            .sidebar-header {
                padding: 20px 15px;
            }

            .site-logo {
                width: 60px;
                height: 60px;
            }
        }

        /* iOS Safe Area Support */
        .sidebar {
            padding-left: env(safe-area-inset-left, 0);
            padding-right: env(safe-area-inset-right, 0);
        }

        .main-content {
            padding-left: env(safe-area-inset-left, 20px);
            padding-right: env(safe-area-inset-right, 20px);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="site-logo">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name', 'Cambridge College') }}">
                @else
                    <span class="site-logo-text">{{ substr(setting('site_name', 'CC'), 0, 1) }}</span>
                @endif
            </div>
            <h4>{{ setting('site_name', 'Cambridge College') }}</h4>
            <p>Student Portal</p>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section-title">Main Menu</div>
            <a href="{{ route('student.dashboard') }}" class="menu-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('student.courses.index') }}" class="menu-item {{ request()->routeIs('student.courses.*') || request()->routeIs('student.exams.*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i>
                <span>My Courses</span>
            </a>
            <a href="{{ route('student.certificates.index') }}" class="menu-item {{ request()->routeIs('student.certificates.*') ? 'active' : '' }}">
                <i class="fas fa-award"></i>
                <span>Certificates</span>
            </a>

            <div class="menu-section-title">Account</div>
            <a href="{{ route('student.profile') }}" class="menu-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i>
                <span>My Profile</span>
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank">
                <i class="fas fa-globe"></i>
                <span>Main Website</span>
            </a>
            <form method="POST" action="{{ route('student.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="menu-item w-100 text-start border-0 bg-transparent" style="cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <h2>@yield('page-title')</h2>
            <div class="user-info">
                <div class="user-avatar">
                    @if(auth()->guard('student')->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->guard('student')->user()->profile_photo) }}" alt="Profile">
                    @else
                        {{ strtoupper(substr(auth()->guard('student')->user()->first_name, 0, 1)) }}
                    @endif
                </div>
                <div class="user-details">
                    <span class="user-name">{{ auth()->guard('student')->user()->full_name }}</span>
                    <span class="user-role">Student</span>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Toggle sidebar on button click
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        // Close sidebar when clicking overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        // Close sidebar when clicking menu item on mobile
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar .menu-item').forEach(item => {
                item.addEventListener('click', () => {
                    if (sidebar.classList.contains('show')) {
                        setTimeout(closeSidebar, 100);
                    }
                });
            });
        }

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth > 768 && sidebar.classList.contains('show')) {
                    closeSidebar();
                }
            }, 250);
        });

        // Prevent sidebar from closing when clicking inside it
        if (sidebar) {
            sidebar.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add touch feedback for mobile menu items only
        if ('ontouchstart' in window) {
            document.querySelectorAll('.sidebar .menu-item').forEach(element => {
                element.addEventListener('touchstart', function() {
                    this.style.opacity = '0.7';
                }, {passive: true});

                element.addEventListener('touchend', function() {
                    this.style.opacity = '';
                }, {passive: true});
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
