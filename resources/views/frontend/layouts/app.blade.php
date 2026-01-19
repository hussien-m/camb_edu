<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title', setting('site_title', 'Cambridge International College in UK - Bst Education in UK'))</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', setting('site_description', 'Cambridge International College in UK'))">
    <meta name="keywords" content="@yield('keywords', setting('seo_keywords', 'education, courses, UAE, cambridge, college'))">
    <meta name="author" content="{{ setting('site_name', 'Cambridge International College in UK') }}">
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', setting('site_title', 'Cambridge International College in UK'))">
    <meta property="og:description" content="@yield('og_description', setting('site_description', 'Cambridge International College in UK offers top-quality education'))">
    <meta property="og:image" content="@yield('og_image', asset('storage/' . setting('site_logo', 'images/og-default.jpg')))">
    <meta property="og:site_name" content="{{ setting('site_name', 'Cambridge International College in UK') }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter_title', setting('site_title', 'Cambridge International College in UK'))">
    <meta name="twitter:description" content="@yield('twitter_description', setting('site_description', 'Cambridge International College in UK offers top-quality education'))">
    <meta name="twitter:image" content="@yield('twitter_image', asset('storage/' . setting('site_logo', 'images/og-default.jpg')))">
    @if(setting('social_twitter'))
    <meta name="twitter:site" content="{{ '@' . basename(setting('social_twitter')) }}">
    @endif

    <!-- Favicon -->
    @if(setting('site_favicon'))
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @endif

    <!-- Google Site Verification -->
    <meta name="google-site-verification" content="kAM8Q1Df2Hat02O5vk047QGP0-Pze5RZQ7pblkAkHMg" />
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://www.google.com">
    <link rel="preconnect" href="https://www.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//{{ parse_url(config('app.url'), PHP_URL_HOST) }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite('resources/css/frontend-layout.css')

    @stack('styles')
</head>

<body>

    <!-- Main Navbar (Template-style) -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand brand" href="{{ route('home') }}" aria-label="{{ setting('site_name', 'Cambridge International College in UK') }}">
                @if(setting('header-footer-logo'))
                <img src="{{ asset('storage/' . setting('header-footer-logo')) }}" alt="Logo">
                @elseif(setting('site_logo'))
                <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="Logo">
                @else
                <i class="fas fa-graduation-cap logo-icon"></i>
                @endif
                <div class="title">
                    <strong>Cambridge</strong>
                    <span>International College in UK</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('courses.offers') ? 'active' : '' }}" href="{{ route('courses.offers') }}">New Offers</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->routeIs('page.show') && request()->segment(2) == 'accreditations') ? 'active' : '' }}"
                            href="{{ route('page.show', 'accreditations') }}">
                            Accreditations
                        </a>
                    </li>

                    @if(isset($pages) && $pages->count() > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                        <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
                            @foreach($pages->take(6) as $page)
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('page.show') && request()->segment(2) == $page->slug ? 'active' : '' }}"
                                    href="{{ route('page.show', $page->slug) }}">
                                    {{ $page->title }}
                                </a>
                            </li>
                            @endforeach
                            <li><a class="dropdown-item" href="{{ route('page.show', 'verification') }}">Verification</a></li>
                        </ul>
                    </li>
                    @endif

                    @auth('student')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">Dashboard</a>
                    </li>
                    @else
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('student.login') ? 'active' : '' }}" href="{{ route('student.login') }}">Login</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-primary" href="{{ route('student.register') }}"><i class="bi bi-person-plus"></i> Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer - Complete Redesign -->
    <footer id="contact" class="footer-new">
        <div class="container">
            <div class="row g-4">
                <!-- Brand & Description -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand-new">
                        <a class="footer-logo-brand" href="{{ route('home') }}">
                            @if(setting('header-footer-logo'))
                            <img src="{{ asset('storage/' . setting('header-footer-logo')) }}" alt="{{ setting('site_name', 'Cambridge College') }} Logo" class="footer-logo-img">
                            @elseif(setting('site_logo'))
                            <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name', 'Cambridge College') }} Logo" class="footer-logo-img">
                            @else
                            <i class="fas fa-graduation-cap footer-logo-icon"></i>
                            @endif
                            <div class="footer-brand-text">
                                <strong class="footer-brand-name">{{ setting('site_name', 'Cambridge International College in UK') }}</strong>
                                <span class="footer-brand-desc" style="font-style: italic;">{{ setting('footer_site_descriptions', 'A leading UK-based provider of internationally recognized academic and professional programs, committed to excellence in education.') }}</span>
                            </div>
                        </a>
                        <div class="footer-social-new mt-4">
                            @if(setting('social_facebook'))
                            <a href="{{ setting('social_facebook') }}" target="_blank" title="Facebook" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if(setting('social_instagram'))
                            <a href="{{ setting('social_instagram') }}" target="_blank" title="Instagram" class="social-btn"><i class="fab fa-instagram"></i></a>
                            @endif
                            @if(setting('social_twitter'))
                            <a href="{{ setting('social_twitter') }}" target="_blank" title="Twitter" class="social-btn"><i class="fab fa-twitter"></i></a>
                            @endif
                            @if(setting('social_linkedin'))
                            <a href="{{ setting('social_linkedin') }}" target="_blank" title="LinkedIn" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                            @endif
                            @if(setting('social_youtube'))
                            <a href="{{ setting('social_youtube') }}" target="_blank" title="YouTube" class="social-btn"><i class="fab fa-youtube"></i></a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Quick Links</h5>
                    <div class="footer-links-new">
                        <a href="{{ route('home') }}" class="footer-link-item">
                            <i class="fas fa-angle-right"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('courses.index') }}" class="footer-link-item">
                            <i class="fas fa-angle-right"></i>
                            <span>Courses</span>
                        </a>
                        <a href="{{ route('success.stories') }}" class="footer-link-item">
                            <i class="fas fa-angle-right"></i>
                            <span>Success Stories</span>
                        </a>
                        <a href="#contact" class="footer-link-item">
                            <i class="fas fa-angle-right"></i>
                            <span>Contact</span>
                        </a>
                    </div>
                </div>

                <!-- Pages Links -->
                @if(isset($pages) && $pages->count() > 0)
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Pages</h5>
                    <div class="footer-links-new">
                        @foreach($pages->take(8) as $page)
                        <a href="{{ route('page.show', $page->slug) }}" class="footer-link-item">
                            <i class="fas fa-angle-right"></i>
                            <span>{{ $page->title }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Contact Us</h5>
                    <div class="footer-contact-new">
                        @php
                            $ukPhone = setting('contact_phone');
                            $ukWhatsapp = setting('contact_whatsapp');
                            $canadaPhone = setting('contact_phone_ca') ?: setting('contact_phone_2');
                            $canadaWhatsapp = setting('contact_whatsapp_ca');
                        @endphp

                        @if(setting('contact_address_uk') || $ukPhone || $ukWhatsapp)
                        <div class="contact-branch-section">
                            <div class="contact-branch-header">UK Branch</div>
                            <div class="contact-branch-items">
                                @if(setting('contact_address_uk'))
                                <div class="contact-branch-row">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div class="contact-branch-text">
                                        <span class="contact-branch-label">Address</span>
                                        <span class="contact-branch-value">{{ setting('contact_address_uk') }}</span>
                                    </div>
                                </div>
                                @endif
                                @if($ukPhone)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $ukPhone) }}" class="contact-branch-row contact-branch-link">
                                    <i class="fas fa-phone"></i>
                                    <div class="contact-branch-text">
                                        <span class="contact-branch-label">Phone</span>
                                        <span class="contact-branch-value">{{ $ukPhone }}</span>
                                    </div>
                                    <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                </a>
                                @endif
                                @if($ukWhatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ukWhatsapp) }}" target="_blank" class="contact-branch-row contact-branch-link">
                                    <i class="fab fa-whatsapp"></i>
                                    <div class="contact-branch-text">
                                        <span class="contact-branch-label">WhatsApp</span>
                                        <span class="contact-branch-value">{{ $ukWhatsapp }}</span>
                                    </div>
                                    <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(setting('contact_address') || $canadaPhone || $canadaWhatsapp)
                        <div class="contact-branch-section">
                            <div class="contact-branch-header">Canada Branch</div>
                            <div class="contact-branch-items">
                                @if(setting('contact_address'))
                                <div class="contact-branch-row">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div class="contact-branch-text">
                                        <span class="contact-branch-label">Address</span>
                                        <span class="contact-branch-value">{{ setting('contact_address') }}</span>
                                    </div>
                                </div>
                                @endif
                                @if($canadaPhone)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $canadaPhone) }}" class="contact-branch-row contact-branch-link">
                                    <i class="fas fa-phone"></i>
                                    <div class="contact-branch-text">
                                        <span class="contact-branch-label">Phone</span>
                                        <span class="contact-branch-value">{{ $canadaPhone }}</span>
                                    </div>
                                    <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                </a>
                                @endif
                                @if($canadaWhatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $canadaWhatsapp) }}" target="_blank" class="contact-branch-row contact-branch-link">
                                    <i class="fab fa-whatsapp"></i>
                                    <div class="contact-branch-text">
                                        <span class="contact-branch-label">WhatsApp</span>
                                        <span class="contact-branch-value">{{ $canadaWhatsapp }}</span>
                                    </div>
                                    <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="contact-info-item">
                            <i class="fas fa-clock"></i>
                            <span>Saturday - Thursday (9AM - 5PM)</span>
                        </div>

                        <a href="mailto:{{ setting('contact_email', 'info@cambridgecollege.ly') }}" class="contact-btn contact-btn-email">
                            <div class="contact-btn-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-btn-content">
                                <span class="contact-btn-label">Email (All Branches)</span>
                                <span class="contact-btn-value">{{ setting('contact_email', 'info@cambridgecollege.ly') }}</span>
                            </div>
                            <i class="fas fa-external-link-alt contact-btn-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Bottom - Full Width -->
        <div class="footer-bottom-new">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="footer-copyright">
                            <i class="fas fa-copyright"></i>
                            <span class="copyright-year">{{ date('Y') }}</span>
                            <span class="copyright-name">{{ setting('site_name', 'Cambridge International College in UK') }}</span>
                            <span class="copyright-separator">|</span>
                            <span class="copyright-rights">All Rights Reserved</span>
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                        <p class="footer-developer">
                            <i class="fas fa-code"></i>
                            <span class="dev-text">Developed with</span>
                            <i class="fas fa-heart dev-heart"></i>
                            <span class="dev-text">by</span>
                            <a href="https://wa.me/970597092668" target="_blank" class="dev-link">
                                <i class="fab fa-whatsapp"></i>
                                <span>Hussien Mohamed</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </footer>

    <style>
        @keyframes heartbeat {

            0%,
            100% {
                transform: scale(1);
            }

            25% {
                transform: scale(1.2);
            }

            50% {
                transform: scale(1);
            }
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Organization Schema.org JSON-LD -->
    <script type="application/ld+json">
        {!! App\Helpers\SeoHelper::generateOrganizationSchema() !!}
    </script>

    @stack('schema')

    <!-- Setup CSRF Token for AJAX & Newsletter Script -->
    @vite('resources/js/csrf-setup.js')
    @vite('resources/js/frontend-newsletter.js')

    <!-- Cookie Consent Banner -->
    @include('frontend.partials.cookie-consent')

    <!-- Scroll to Top Button -->
    @include('frontend.partials.scroll-to-top')

    <!-- Google reCAPTCHA v3 -->
    @if(config('services.recaptcha.enabled') && config('services.recaptcha.site_key'))
    <div id="recaptcha-container" style="position: fixed; bottom: 0; left: 0; width: 0; height: 0; overflow: hidden; visibility: hidden; pointer-events: none; z-index: -1;"></div>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
        // Global reCAPTCHA function
        function executeRecaptcha(action) {
            return new Promise((resolve, reject) => {
                grecaptcha.ready(function() {
                    grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", {
                        action: action
                    })
                        .then(function(token) {
                            resolve(token);
                        })
                        .catch(function(error) {
                            console.error('reCAPTCHA error:', error);
                            reject(error);
                        });
                });
            });
        }
    </script>
    @endif

    <!-- Google Analytics -->
    @if(setting('google_analytics'))
    {!! setting('google_analytics') !!}
    @endif

    @stack('scripts')

</body>

</html>
