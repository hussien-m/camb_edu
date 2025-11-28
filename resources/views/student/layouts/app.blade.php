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

    <!-- Student Layout Styles -->
    @vite('resources/css/student-layout.css')

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

    <!-- Student Layout Scripts -->
    @vite('resources/js/student-layout.js')

    @stack('scripts')
</body>
</html>
