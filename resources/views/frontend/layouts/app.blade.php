<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('site_title', 'Cambridge College - Best Education in Libya'))</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', setting('site_description', 'Cambridge College offers top-quality education and courses in Libya'))">
    <meta name="keywords" content="{{ setting('seo_keywords', 'education, courses, libya, cambridge, college') }}">

    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin:0;
            padding:0;
            background:#f9fafb;
        }

        /* ============================================
           MAIN NAVBAR - Compact Professional Design
        ============================================ */
        .navbar {
            background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
            padding: 8px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 10000;
            transition: all 0.3s;
        }
        .navbar.scrolled {
            padding: 6px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.3s;
        }
        .navbar-brand:hover {
            transform: scale(1.02);
        }
        .navbar-brand img {
            height: 40px;
            width: 40px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ffcc00;
            box-shadow: 0 2px 8px rgba(255,204,0,0.2);
            transition: all 0.3s;
        }
        .navbar-brand:hover img {
            transform: rotate(3deg);
            box-shadow: 0 4px 12px rgba(255,204,0,0.4);
        }

        /* Navbar Links - Compact */
        .navbar .nav-link {
            color: rgba(255,255,255,0.95) !important;
            margin: 0 4px;
            padding: 6px 12px !important;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 0.9rem;
            position: relative;
            white-space: nowrap;
        }
        .navbar .nav-link::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 60%;
            height: 2px;
            background: #ffcc00;
            border-radius: 1px;
            transition: transform 0.3s;
        }
        .navbar .nav-link:hover {
            color: #ffcc00 !important;
            background: rgba(255,204,0,0.12);
        }
        .navbar .nav-link:hover::after {
            transform: translateX(-50%) scaleX(1);
        }
        .navbar .nav-link.active {
            color: #ffcc00 !important;
            background: rgba(255,204,0,0.18);
        }
        .navbar .nav-link.active::after {
            transform: translateX(-50%) scaleX(1);
        }
        .navbar .nav-link i {
            font-size: 0.85rem;
        }

        /* Course Links - Different styling for desktop */
        .navbar .courses-link {
            display: none;
        }

        @media (max-width: 991px) {
            .navbar .courses-link {
                display: block;
                background: rgba(59, 130, 246, 0.15);
                border-left: 3px solid #3b82f6;
            }
            .navbar .courses-link:hover {
                background: rgba(59, 130, 246, 0.25);
                color: #3b82f6 !important;
                border-left-color: #60a5fa;
            }
            .navbar .courses-link.active {
                background: rgba(59, 130, 246, 0.3);
                color: #3b82f6 !important;
                border-left-color: #2563eb;
                font-weight: 600;
            }
        }

        /* Dropdown Menu - Modern */
        .navbar .dropdown-menu {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.2);
            padding: 15px 0;
            margin-top: 10px;
            animation: fadeInDown 0.3s ease;
        }
        .navbar .dropdown-item {
            color: #374151;
            padding: 12px 25px;
            transition: all 0.3s;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar .dropdown-item i {
            color: #1e3a8a;
            width: 20px;
            transition: transform 0.3s;
        }
        .navbar .dropdown-item:hover {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #1e3a8a;
            padding-right: 30px;
        }
        .navbar .dropdown-item:hover i {
            transform: scale(1.2);
        }
        .navbar .dropdown-divider {
            border-color: #e5e7eb;
            margin: 10px 15px;
        }

        /* Register Button - Compact Design */
        .btn-register {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 6px 16px !important;
            border-radius: 20px !important;
            border: 2px solid rgba(255,255,255,0.2) !important;
            transition: all 0.3s !important;
            box-shadow: 0 2px 10px rgba(220,38,38,0.3) !important;
            margin-right: 8px !important;
            font-size: 0.9rem !important;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%) !important;
            border-color: rgba(255,255,255,0.4) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 15px rgba(220,38,38,0.5) !important;
            color: #ffffff !important;
        }

        /* Secondary Navbar Styles */
        .courses-navbar {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 8px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .courses-navbar .nav-link {
            color: white !important;
            padding: 6px 15px;
            transition: all 0.3s;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .courses-navbar .nav-link:hover {
            background: rgba(255,255,255,0.15);
        }
        .courses-navbar .nav-link.active {
            background: rgba(255,255,255,0.2);
            font-weight: 600;
        }

        /* Fix mobile navbar collapse background */
        @media (max-width: 991px) {
            .courses-navbar .navbar-collapse {
                background: rgba(37, 99, 235, 0.98);
                padding: 15px;
                border-radius: 8px;
                margin-top: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            }
            .courses-navbar .nav-item {
                margin: 5px 0;
            }
            .courses-navbar .nav-link {
                padding: 10px 15px;
                display: block;
                text-align: center;
            }
        }

        /* Responsive Design - Mobile & Tablet */
        @media (max-width: 991px) {
            .navbar {
                padding: 6px 0;
                z-index: 10000;
            }
            .navbar-brand {
                font-size: 1rem;
            }
            .navbar-brand img {
                height: 35px;
                width: 35px;
            }
            .navbar .nav-link {
                padding: 8px 12px !important;
                margin: 2px 0;
                font-size: 0.9rem;
            }
            .navbar-collapse {
                background: rgba(30, 58, 138, 0.98);
                padding: 10px 15px;
                border-radius: 8px;
                margin-top: 10px;
            }
            /* Ensure secondary navbar is below main navbar */
            .courses-navbar {
                position: relative !important;
                top: auto !important;
                z-index: 1040;
            }
        }

        @media (max-width: 767px) {
            .navbar-brand {
                font-size: 0.95rem;
            }
            .navbar-brand img {
                height: 32px;
                width: 32px;
            }
            .navbar .nav-link {
                font-size: 0.85rem;
                padding: 6px 10px !important;
            }
            .btn-register {
                font-size: 0.85rem !important;
                padding: 5px 14px !important;
            }
            .courses-navbar {
                padding: 6px 0;
            }
            .courses-navbar .nav-link {
                font-size: 0.85rem;
                padding: 6px 10px;
            }
        }

        /* Animation */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================
           FOOTER - Professional Design
        ============================================ */
        /* Newsletter Section */
        .newsletter-section {
            position: relative;
            overflow: hidden;
        }
        .newsletter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            opacity: 0.3;
            pointer-events: none;
            z-index: 0;
        }
        .newsletter-section .container {
            position: relative;
            z-index: 1;
        }
        .newsletter-icon {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .newsletter-form-wrapper {
            animation: slideInRight 0.6s ease-out;
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .newsletter-form-wrapper input:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        footer {
            background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
            color: white;
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
        }
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #ffcc00 0%, #e6b800 100%);
        }
        footer h5 {
            color: #ffcc00;
            margin-bottom: 25px;
            font-weight: 800;
            font-size: 1.3rem;
            position: relative;
            padding-bottom: 12px;
        }
        footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #ffcc00;
            border-radius: 2px;
        }
        footer p {
            color: rgba(255,255,255,0.85);
            line-height: 1.8;
            margin-bottom: 20px;
        }
        footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            margin-bottom: 10px;
        }
        footer a:hover {
            color: #ffcc00;
            transform: translateX(5px);
        }
        footer .social-links {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        footer .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(255,204,0,0.15);
            border: 2px solid rgba(255,204,0,0.3);
            transition: all 0.3s;
            margin: 0;
        }
        footer .social-links a:hover {
            background: #ffcc00;
            border-color: #ffcc00;
            color: #1e3a8a;
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 8px 20px rgba(255,204,0,0.4);
        }
        footer .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 50px;
            padding-top: 25px;
            text-align: center;
            color: rgba(255,255,255,0.7);
        }
        footer .footer-bottom a {
            color: #ffcc00;
            font-weight: 600;
            margin: 0 5px;
        }
        footer .footer-links a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
            font-size: 0.95rem;
        }
        footer .footer-links a i {
            color: #ffcc00;
            width: 18px;
        }
        footer .contact-info {
            margin-top: 20px;
        }
        footer .contact-info p {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }
        footer .contact-info i {
            color: #ffcc00;
            font-size: 1.2rem;
            width: 25px;
        }

        /* Pagination Styles */
        .pagination {
            gap: 5px;
        }
        .pagination .page-link {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            color: #1e3a8a;
            font-weight: 500;
            padding: 10px 16px;
            transition: all 0.3s;
        }
        .pagination .page-link:hover {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            color: white;
        }
        .pagination .page-item.active .page-link {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            color: white;
        }
        .pagination .page-item.disabled .page-link {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }

        @media(max-width:991px){
            .navbar {
                padding: 12px 0;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
            .navbar-brand img {
                height: 45px;
                width: 45px;
            }

            /* Mobile Menu - Full Screen Style */
            .navbar-collapse {
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
                padding: 20px;
                margin: 0;
                overflow-y: auto;
                z-index: 1050;
            }

            .navbar-collapse.collapsing {
                height: auto !important;
                transition: opacity 0.3s ease;
            }

            /* Vertical Stack All Items */
            .navbar-nav {
                flex-direction: column !important;
                width: 100%;
                gap: 0;
            }

            .navbar .nav-item {
                width: 100%;
                margin: 0 0 10px 0;
            }

            .navbar .nav-link {
                width: 100%;
                margin: 0;
                padding: 15px 20px !important;
                border-radius: 10px;
                font-size: 1.05rem;
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(255,255,255,0.1);
                text-align: left;
                display: block;
                transition: all 0.3s ease;
            }

            .navbar .nav-link:hover {
                background: rgba(255,204,0,0.2);
                border-color: #ffcc00;
                transform: translateX(5px);
            }

            /* Dropdown Styling */
            .navbar .dropdown-menu {
                position: static !important;
                transform: none !important;
                width: 100%;
                border: none;
                border-radius: 10px;
                box-shadow: none;
                background: rgba(255,255,255,0.95);
                margin: 8px 0 0 0;
                padding: 10px;
                max-height: 300px;
                overflow-y: auto;
            }

            .navbar .dropdown-item {
                padding: 12px 15px;
                border-radius: 8px;
                font-size: 0.95rem;
                margin-bottom: 5px;
                white-space: normal;
                word-wrap: break-word;
            }

            .navbar .dropdown-item:hover {
                background: #1e3a8a;
                color: #fff;
            }

            /* Register Button */
            .btn-register {
                width: 100%;
                margin: 15px 0 0 0 !important;
                text-align: center;
                justify-content: center;
                padding: 15px 30px !important;
                font-size: 1.1rem;
                font-weight: 600;
            }

            /* Toggler */
            .navbar-toggler {
                border: 2px solid #ffcc00;
                padding: 10px 15px;
                border-radius: 8px;
                background: rgba(255,204,0,0.15);
            }

            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.2rem rgba(255,204,0,0.3);
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23ffcc00' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2.5' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
                width: 28px;
                height: 28px;
            }
        }

        @media(max-width:768px){
            .navbar-brand {
                font-size: 1.1rem;
            }
            .navbar-brand img {
                height: 40px;
                width: 40px;
            }
            .navbar .nav-link {
                font-size: 0.95rem;
            }
            .pagination .page-link {
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Main Navbar - Professional Design -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            @if(setting('site_logo'))
                <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name') }} Logo">
            @else
                <img src="https://via.placeholder.com/55x55/1e3a8a/ffcc00?text=CC" alt="Logo">
            @endif
            {{ setting('site_name', 'Cambridge College') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>

                <!-- Accreditations -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('page.show') && request()->segment(2) == 'accreditations' ? 'active' : '' }}"
                       href="{{ route('page.show', 'accreditations') }}">
                        <i class="fas fa-award me-1"></i> Accreditations
                    </a>
                </li>

                <!-- Verification -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('page.show') && request()->segment(2) == 'verification' ? 'active' : '' }}"
                       href="{{ route('page.show', 'verification') }}">
                        <i class="fas fa-check-circle me-1"></i> Verification
                    </a>
                </li>

                <!-- Attestation -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('page.show') && request()->segment(2) == 'attestation' ? 'active' : '' }}"
                       href="{{ route('page.show', 'attestation') }}">
                        <i class="fas fa-stamp me-1"></i> Attestation
                    </a>
                </li>

                <!-- About Us -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('page.show') && request()->segment(2) == 'about-us' ? 'active' : '' }}"
                       href="{{ route('page.show', 'about-us') }}">
                        <i class="fas fa-info-circle me-1"></i> About Us
                    </a>
                </li>

                <!-- Contact -->
                <li class="nav-item">
                    <a class="nav-link" href="#contact">
                        <i class="fas fa-envelope me-1"></i> Contact
                    </a>
                </li>

                <!-- Divider for Mobile -->
                <li class="nav-item d-lg-none w-100" style="border-top: 2px solid rgba(255,204,0,0.3); margin: 15px 0 10px 0;">
                    <div class="text-center text-white py-2" style="font-size: 0.85rem; font-weight: 600; letter-spacing: 1px;">
                        <i class="fas fa-graduation-cap me-2"></i>OUR PROGRAMS
                    </div>
                </li>

                <!-- Course Levels -->
                @php
                    $levelsMenu = \App\Models\CourseLevel::orderBy('sort_order')->get();
                @endphp
                @foreach($levelsMenu as $level)
                <li class="nav-item">
                    <a class="nav-link courses-link {{ request()->has('level_id') && request()->get('level_id') == $level->id ? 'active' : '' }}"
                       href="{{ route('courses.index', ['level_id' => $level->id]) }}">
                        <i class="fas fa-certificate me-1"></i> {{ $level->name }}
                    </a>
                </li>
                @endforeach

                <!-- Divider for Mobile -->
                <li class="nav-item d-lg-none w-100" style="border-top: 2px solid rgba(255,204,0,0.3); margin: 15px 0 10px 0;">
                    <div class="text-center text-white py-2" style="font-size: 0.85rem; font-weight: 600; letter-spacing: 1px;">
                        <i class="fas fa-user me-2"></i>ACCOUNT
                    </div>
                </li>

                @auth('student')
                    <li class="nav-item">
                        <a class="nav-link btn-register" href="{{ route('student.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-register" href="{{ route('student.register') }}">
                            <i class="fas fa-user-plus me-1"></i> Register Now
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Secondary Navbar for Desktop Only - Course Levels -->
<nav class="navbar navbar-expand-lg navbar-dark courses-navbar d-none d-lg-block" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 10px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="container">
        <div class="navbar-nav mx-auto">
            @php
                $levelsMenu2 = \App\Models\CourseLevel::orderBy('sort_order')->get();
            @endphp
            @foreach($levelsMenu2 as $level)
                <a class="nav-link text-white {{ request()->has('level_id') && request()->get('level_id') == $level->id ? 'fw-bold' : '' }}"
                   href="{{ route('courses.index', ['level_id' => $level->id]) }}"
                   style="padding: 8px 20px; transition: all 0.3s; border-radius: 8px; font-weight: 600;"
                   onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                   onmouseout="this.style.background='transparent'">
                    <i class="fas fa-certificate me-1"></i> {{ $level->name }}
                </a>
            @endforeach
        </div>
    </div>
</nav>

<!-- Main Content -->
<main>
    @yield('content')
</main>

<!-- Footer - Professional Design -->
<footer id="contact">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5>
                    <i class="fas fa-graduation-cap me-2"></i>
                    {{ setting('site_name', 'Cambridge College') }}
                </h5>
                <p>{{ setting('site_description', 'We provide the best training courses and educational programs in Libya. Join us and develop your skills with the best instructors.') }}</p>

                <div class="social-links">
                    @if(setting('social_facebook'))
                        <a href="{{ setting('social_facebook') }}" target="_blank" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" target="_blank" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if(setting('social_twitter'))
                        <a href="{{ setting('social_twitter') }}" target="_blank" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if(setting('social_linkedin'))
                        <a href="{{ setting('social_linkedin') }}" target="_blank" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if(setting('social_youtube'))
                        <a href="{{ setting('social_youtube') }}" target="_blank" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5>
                    <i class="fas fa-link me-2"></i>
                    Quick Links
                </h5>
                <div class="footer-links">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-angle-right"></i>
                        Home
                    </a>
                    <a href="{{ route('courses.index') }}">
                        <i class="fas fa-angle-right"></i>
                        Courses
                    </a>
                    <a href="{{ route('success.stories') }}">
                        <i class="fas fa-angle-right"></i>
                        Success Stories
                    </a>
                    <a href="#contact">
                        <i class="fas fa-angle-right"></i>
                        Contact
                    </a>
                </div>
            </div>

            <!-- Pages Links -->
            @if(isset($pages) && $pages->count() > 0)
            <div class="col-lg-2 col-md-6 mb-4">
                <h5>
                    <i class="fas fa-file-alt me-2"></i>
                    Pages
                </h5>
                <div class="footer-links">
                    @foreach($pages->take(5) as $page)
                        <a href="{{ route('page.show', $page->slug) }}">
                            <i class="fas fa-angle-right"></i>
                            {{ $page->title }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5>
                    <i class="fas fa-phone-alt me-2"></i>
                    Contact Information
                </h5>
                <div class="contact-info">
                    <p>
                        <i class="fas fa-envelope"></i>
                        {{ setting('contact_email', 'info@cambridgecollege.ly') }}
                    </p>
                    <p>
                        <i class="fas fa-phone"></i>
                        {{ setting('contact_phone', '+218 91 234 5678') }}
                    </p>
                    @if(setting('contact_address'))
                        <p>
                            <i class="fas fa-map-marker-alt"></i>
                            {{ setting('contact_address') }}
                        </p>
                    @endif
                    <p>
                        <i class="fas fa-clock"></i>
                        Working Hours: Saturday - Thursday (9AM - 5PM)
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p class="mb-0">
                {{ setting('footer_text', '© ' . date('Y') . ' Cambridge College. All rights reserved.') }}
                <br>
                <small>Developed by
                    <a href="#" target="_blank">Cambridge College Team</a>
                </small>
            </p>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Setup CSRF Token for AJAX -->
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});
</script>

<!-- Newsletter Subscription Script -->
<script>
document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const email = document.getElementById('newsletter-email').value;
    const messageEl = document.getElementById('newsletter-message');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch('{{ route("newsletter.subscribe") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageEl.className = 'text-success';
            messageEl.textContent = data.message;
            form.reset();
        } else {
            messageEl.className = 'text-danger';
            messageEl.textContent = data.message;
        }
    })
    .catch(error => {
        messageEl.className = 'text-danger';
        messageEl.textContent = 'An error occurred. Please try again.';
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';

        // Clear message after 5 seconds
        setTimeout(() => {
            messageEl.textContent = '';
        }, 5000);
    });
});
</script>

<!-- Google Analytics -->
@if(setting('google_analytics'))
    {!! setting('google_analytics') !!}
@endif

@stack('scripts')

</body>
</html>
