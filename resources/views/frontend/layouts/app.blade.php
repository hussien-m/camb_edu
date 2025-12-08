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

    @vite('resources/css/frontend-layout.css')

    @stack('styles')
</head>
<body>

<!-- Main Navbar - Professional Design -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <!-- Register Button - Mobile Only (Left Side) -->
        @guest('student')
            <a class="mobile-register-btn d-lg-none" href="{{ route('student.register') }}">
                <i class="fas fa-user-plus"></i>
                <span>Register</span>
            </a>
        @else
            <a class="mobile-register-btn d-lg-none" href="{{ route('student.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        @endguest

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <!-- Left Side - Navigation Links -->
            <ul class="navbar-nav me-auto align-items-center">
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

                <!-- Auth Links - Mobile Only -->
                @auth('student')
                    <li class="nav-item d-lg-none">
                        <a class="nav-link btn-register" href="{{ route('student.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                @else
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="{{ route('student.login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link btn-register" href="{{ route('student.register') }}">
                            <i class="fas fa-user-plus me-1"></i> Register Now
                        </a>
                    </li>
                @endauth
            </ul>>

            <!-- Right Side - Auth Buttons (Desktop Only) -->
            <ul class="navbar-nav ms-auto align-items-center d-none d-lg-flex">
                @auth('student')
                    <li class="nav-item">
                        <a class="nav-link btn-register" href="{{ route('student.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.login') }}" style="margin-right: 10px;">
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
                <div class="footer-logo mb-3">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name') }} Logo" class="footer-logo-img">
                    @else
                        <img src="https://via.placeholder.com/80x80/1e3a8a/ffcc00?text=CC" alt="Logo" class="footer-logo-img">
                    @endif
                </div>
                <h5>{{ setting('site_name', 'Cambridge College') }}</h5>
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
                    @if(setting('contact_whatsapp'))
                    <p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('contact_whatsapp')) }}"
                           target="_blank"
                           style="color: #25d366; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fab fa-whatsapp" style="font-size: 1.2rem;"></i>
                            <span>{{ setting('contact_whatsapp') }}</span>
                            <span style="background: #25d366; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Chat Now</span>
                        </a>
                    </p>
                    @endif
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
                    <a href="#" target="_blank">{{ setting('site_title') ?? 'Hussien Mohamed' }} Team</a>
                </small>
            </p>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Setup CSRF Token for AJAX & Newsletter Script -->
@vite('resources/js/csrf-setup.js')
@vite('resources/js/frontend-newsletter.js')

<!-- Google Analytics -->
@if(setting('google_analytics'))
    {!! setting('google_analytics') !!}
@endif

@stack('scripts')

</body>
</html>
