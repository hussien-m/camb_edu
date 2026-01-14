@extends('frontend.layouts.app')

@section('title', setting('site_title', 'Cambridge International College in UK'))

@section('description', setting('site_description', 'Cambridge International College in UK offers top-quality education and professional training courses in UK. Accredited programs with international certificates.'))

@section('keywords', setting('seo_keywords', 'education, courses, UAE, cambridge, college, training, certificates'))

@section('canonical', route('home'))

@section('og_type', 'website')
@section('og_title', setting('site_title', 'Cambridge College'))
@section('og_description', setting('site_description', 'Top-quality education and courses in UK'))

@push('styles')
    @vite('resources/css/frontend-home.css')
@endpush

@section('content')

<!-- ============================================
     TOP ADS
============================================ -->
{{-- Top Ads removed - only popup ads are shown --}}

<!-- ============================================
     HERO SECTION - FROM SCRATCH (CLEAN)
============================================ -->
<section class="hero-pro">
    <div class="hero-pro-bg">
        <img class="hero-pro-img" src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="{{ setting('site_name', 'Cambridge International College in UK') }}">
        <span class="hero-pro-overlay"></span>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center gy-4 hero-pro-grid">
            <div class="col-xl-6" data-aos="fade-up">
                <div class="hero-pro-kicker">
                    <i class="bi bi-shield-check"></i>
                    <span>Accredited learning • UK based</span>
                </div>

                <h1 class="hero-pro-title">
                    {{ setting('site_name', 'Cambridge International College in UK') }}
                </h1>

                <p class="hero-pro-subtitle">
                    {{ setting('site_description', 'Select certificates and diplomas built with employers, delivered by expert trainers, and ready for real careers.') }}
                </p>

                <div class="hero-pro-cta">
                    <a class="btn btn-hero-pro-primary" href="{{ route('courses.index') }}">
                        <i class="bi bi-search"></i>
                        Browse Courses
                    </a>
                    <a class="btn btn-hero-pro-secondary" href="#contact-section">
                        <i class="bi bi-chat-dots"></i>
                        Talk to us
                    </a>
                </div>

            </div>

            <div class="col-xl-6" data-aos="fade-up" data-aos-delay="80">
                <div class="hero-pro-card">
                    <div class="hero-pro-card-head">
                        <div>
                            <div class="title">Find the right course</div>
                            <div class="muted">Filter by keyword, category, and level</div>
                        </div>
                        <span class="hero-pro-pill"><i class="bi bi-broadcast-pin"></i> Live / On-demand</span>
                    </div>

                    <form class="hero-pro-form" action="{{ route('courses.index') }}" method="get">
                        <div class="hero-pro-input">
                            <i class="bi bi-search"></i>
                            <input type="text" name="keyword" placeholder="Search courses (Leadership, IT, Education...)" />
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="hero-pro-select">
                                    <i class="bi bi-layers"></i>
                                    <select name="category_id">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="hero-pro-select">
                                    <i class="bi bi-diagram-3"></i>
                                    <select name="level_id">
                                        <option value="">All Levels</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-hero-pro-primary w-100" type="submit">
                            <i class="bi bi-arrow-right-circle"></i>
                            Start Search
                        </button>
                    </form>

                    <div class="hero-pro-quick">
                        <span><i class="bi bi-shield-lock"></i> Accredited</span>
                        <span><i class="bi bi-people"></i> Small cohorts</span>
                        <span><i class="bi bi-laptop"></i> Mobile-ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     MIDDLE ADS
============================================ -->
{{-- Middle Ads removed - only popup ads are shown --}}

<!-- ============================================
     SIDEBAR ADS
============================================ -->
@if(isset($sidebarAds) && $sidebarAds->count() > 0)
<div class="container my-5">
    <div class="row">
        @foreach($sidebarAds as $ad)
            <div class="col-lg-{{ $sidebarAds->count() == 1 ? '12' : '6' }} mb-4">
                @include('frontend.partials.ad-display', ['ad' => $ad])
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- ============================================
     FEATURED COURSES
============================================ -->
<section class="section-light">
    <div class="container">
        <div class="section-header">
            <h2>🎯 Featured Courses</h2>
            <p>Choose from the best training courses specially designed to develop your skills</p>
        </div>

        <div class="row g-4">
            @forelse($featuredCourses as $course)
            <div class="col-lg-3 col-md-6">
                <div class="card course-card">
                    <div class="card-img-wrapper">
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}"
                                 alt="{{ $course->title }}"
                                 width="400"
                                 height="300"
                                 loading="lazy"
                                 decoding="async">
                        @else
                            <img src="https://picsum.photos/seed/{{ $course->title }}/400/300"
                                 alt="{{ $course->title }}"
                                 width="400"
                                 height="300"
                                 loading="lazy"
                                 decoding="async">
                        @endif
                        <span class="badge-featured">⭐ Featured</span>
                    </div>
                    <div class="card-body">
                        <span class="course-category">{{ $course->category->name }}</span>
                        <h5 class="course-title">{{ $course->title }}</h5>
                        <p class="course-description">{{ $course->description }}</p>

                        <div class="course-meta">
                            <span class="meta-item">
                                <i class="fas fa-signal"></i>
                                {{ $course->level->name }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-clock"></i>
                                {{ $course->duration }} hours
                            </span>
                        </div>

                        @php
                            $categorySlug = $course->category?->slug ?? 'general';
                            $levelSlug = $course->level?->slug ?? 'beginner';
                            $courseSlug = $course->slug ?? 'course-' . $course->id;
                        @endphp
                        <a href="{{ route('courses.show', [$categorySlug, $levelSlug, $courseSlug]) }}" class="btn btn-course">
                            <i class="fas fa-arrow-right"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5" style="background: white; border: 2px dashed #1e3a8a; border-radius: 20px;">
                    <i class="fas fa-info-circle fa-3x text-primary mb-3"></i>
                    <h5>No featured courses available at the moment</h5>
                </div>
            </div>
            @endforelse
        </div>

        @if($featuredCourses->count() > 0)
        <div class="text-center mt-5">
            <a href="{{ route('courses.index') }}" class="btn btn-view-all">
                <i class="fas fa-th me-2"></i> View All Courses
            </a>
        </div>
        @endif
    </div>
</section>

<!-- ============================================
     STATS SECTION
============================================ -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-number" data-stat="{{ \App\Models\Course::where('status', 'active')->count() }}+">{{ \App\Models\Course::where('status', 'active')->count() }}+</div>
                    <div class="stat-label">Active Courses</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-number" data-stat="{{ \App\Models\SuccessStory::where('is_published', true)->count() * 100 }}+">{{ \App\Models\SuccessStory::where('is_published', true)->count() * 100 }}+</div>
                    <div class="stat-label">Graduated Students</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-number" data-stat="{{ \App\Models\CourseCategory::count() * 5 }}+">{{ \App\Models\CourseCategory::count() * 5 }}+</div>
                    <div class="stat-label">Expert Instructors</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number" data-stat="100%">100%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     SUCCESS STORIES
============================================ -->
@if($successStories->count() > 0)
<section class="section-white" id="success-stories-section">
    <div class="container">
        <div class="section-header">
            <h2>🌟 Success Stories</h2>
            <p>Discover how our courses have changed the lives of our outstanding students</p>
        </div>

        <div class="row g-4">
            @foreach($successStories as $story)
            <div class="col-lg-4 col-md-6">
                <div class="story-card">
                    @if($story->image_path)
                        <img src="{{ Storage::url($story->image_path) }}" alt="{{ $story->name }}" class="story-avatar">
                    @else
                        <img src="https://picsum.photos/seed/story{{ $story->id }}/100/100" alt="{{ $story->name }}" class="story-avatar">
                    @endif
                    <h4 class="story-name">{{ $story->name }}</h4>
                    <p class="story-meta">
                        <i class="fas fa-graduation-cap me-1"></i>
                        {{ $story->course_name ?: 'Graduate' }}
                    </p>
                    <p class="story-quote">{{ Str::limit(strip_tags($story->story), 150) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('success.stories') }}" class="btn btn-view-all">
                <i class="fas fa-star me-2"></i> More Success Stories
            </a>
        </div>
    </div>
</section>
@endif

<!-- ============================================
     WHY CHOOSE US (FEATURES)
============================================ -->
@if($features->count() > 0)
<section class="section-light">
    <div class="container">
        <div class="section-header">
            <h2>✨ Why Choose Us?</h2>
            <p>We provide you with the best learning experience with exceptional features</p>
        </div>

        <div class="row g-4">
            @foreach($features as $feature)
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="{{ $feature->icon }}"></i>
                    </div>
                    <h5>{{ $feature->title }}</h5>
                    <p>{{ $feature->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ============================================
     NEWSLETTER SECTION
============================================ -->
<section class="newsletter-section newsletter-green py-5" style="margin-top: 80px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 text-white">
                <div class="d-flex align-items-center mb-3">
                    <div class="newsletter-icon me-3">
                        <i class="fas fa-envelope-open-text fa-3x"></i>
                    </div>
                    <div>
                        <h2 class="mb-2 fw-bold">Subscribe to Our Newsletter</h2>
                        <p class="mb-0 fs-5">Get the latest updates, courses, and exclusive offers delivered to your inbox!</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2 fa-lg"></i>
                        <span>Weekly Updates</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2 fa-lg"></i>
                        <span>New Courses</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2 fa-lg"></i>
                        <span>Special Offers</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="newsletter-form-wrapper p-4 bg-white rounded-3 shadow-lg">
                    <form id="newsletter-form" data-action="{{ route('newsletter.subscribe') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="newsletter-email" class="form-label fw-bold text-dark">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-at text-muted"></i>
                                </span>
                                <input type="email"
                                       class="form-control border-start-0 ps-0"
                                       id="newsletter-email"
                                       name="email"
                                       placeholder="Enter your email address"
                                       required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary w-100 fw-bold shadow">
                            <i class="fas fa-paper-plane me-2"></i>
                            Subscribe Now
                        </button>
                        <div id="newsletter-message" class="mt-3 text-center fw-bold"></div>
                    </form>
                    <p class="text-muted text-center mt-3 mb-0 small">
                        <i class="fas fa-lock me-1"></i>
                        Your privacy is important to us. We'll never share your email.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     BOTTOM ADS
============================================ -->
{{-- Bottom Ads removed - only popup ads are shown --}}

<!-- ============================================
     CONTACT SECTION
============================================ -->
<section class="section-white" id="contact-section">
    <div class="container">
        <div class="section-header">
            <h2>📬 Contact Us</h2>
            <p>Have a question? We're here to answer all your inquiries</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card" style="border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: none;">
                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success" style="border-radius: 12px; border-left: 5px solid #10b981;">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        <!-- Alert Messages (Ajax) -->
                        <div id="contactAlert" style="display: none;" class="alert alert-dismissible fade show" role="alert">
                            <span id="contactAlertMessage"></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <!-- Honeypot Fields (hidden from real users, visible to bots) -->
                            <input type="text" name="website_url" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
                            <input type="text" name="phone_number_confirm" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required
                                           style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required
                                           style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                           value="{{ old('subject') }}" required
                                           style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                                              required style="border-radius: 12px; border: 2px solid #e5e7eb;">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-view-all px-5" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    @vite('resources/js/frontend-home.js')

    {{-- Popup Ads --}}
    @php
        $hasPopupAds = isset($popupAds) && $popupAds->count() > 0;
        $popupAdsData = [];
        if ($hasPopupAds) {
            foreach($popupAds as $ad) {
                $popupAdsData[] = [
                    'id' => $ad->id,
                    'title' => $ad->title ?? '',
                    'description' => $ad->description ?? '',
                    'image' => $ad->image ?? '',
                    'html_content' => $ad->html_content ?? '',
                    'link' => $ad->link ?? '',
                    'open_in_new_tab' => $ad->open_in_new_tab ?? true,
                    'end_date' => $ad->end_date ? $ad->end_date->format('Y-m-d H:i:s') : null,
                ];
            }
        }
    @endphp

    @if($hasPopupAds)
    <script>
        console.log('=== POPUP ADS DEBUG START ===');
        console.log('Popup Ads Count:', @json(count($popupAdsData)));

        const popupAds = @json($popupAdsData);
        console.log('Popup Ads Array:', popupAds);

        function showPopupAd(ad) {
            console.log('=== SHOWING POPUP AD ===', ad);

            // Clear sessionStorage for testing
            // sessionStorage.removeItem('ad_popup_' + ad.id);

            const overlay = document.createElement('div');
            overlay.id = 'popup-overlay-' + ad.id;
            overlay.style.cssText = 'position:fixed !important;top:0 !important;left:0 !important;right:0 !important;bottom:0 !important;background:rgba(0,0,0,0.75) !important;z-index:999999 !important;display:flex !important;align-items:center !important;justify-content:center !important;';

            let contentHtml = '';

            if (ad.image) {
                const imageUrl = '{{ asset("storage/") }}/' + ad.image;
                contentHtml += '<img src="' + imageUrl + '" alt="' + (ad.title || 'Ad') + '" style="width:100%;height:auto;display:block;">';
            }

            if (ad.html_content) {
                contentHtml += '<div style="padding:30px;">' + ad.html_content + '</div>';
            }

            if (ad.title || ad.description) {
                contentHtml += '<div style="padding:40px 30px;text-align:center;background:white;">';
                if (ad.title) {
                    contentHtml += '<h3 style="margin:0 0 15px 0;font-size:1.8rem;color:#1e3a8a;font-weight:700;">' + ad.title + '</h3>';
                }
                if (ad.description) {
                    contentHtml += '<p style="margin:0 0 25px 0;font-size:1.1rem;color:#6b7280;line-height:1.7;">' + ad.description + '</p>';
                }
                if (ad.link) {
                    const target = ad.open_in_new_tab ? '_blank' : '_self';
                    const clickUrl = '{{ url("/ad") }}/' + ad.id + '/click';
                    contentHtml += '<a href="' + clickUrl + '" target="' + target + '" style="display:inline-block;padding:15px 35px;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);color:white;text-decoration:none;border-radius:50px;font-weight:600;font-size:1.1rem;box-shadow:0 5px 15px rgba(102, 126, 234, 0.4);">Learn More</a>';
                }
                contentHtml += '</div>';
            }

            if (!contentHtml) {
                contentHtml = '<div style="padding:40px 30px;text-align:center;background:white;"><h3>Ad #' + ad.id + '</h3><p>No content available</p></div>';
            }

            overlay.innerHTML = '<div style="position:relative;max-width:600px;width:90%;max-height:90vh;background:white;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.5);overflow:hidden;"><button onclick="document.getElementById(\'popup-overlay-' + ad.id + '\').remove()" style="position:absolute;top:15px;right:15px;width:40px;height:40px;background:rgba(0,0,0,0.5);border:none;border-radius:50%;color:white;font-size:1.2rem;cursor:pointer;z-index:10;">×</button><div style="width:100%;">' + contentHtml + '</div></div>';

            document.body.appendChild(overlay);
            console.log('Popup overlay added to body. Body children:', document.body.children.length);

            setTimeout(function() {
                const el = document.getElementById('popup-overlay-' + ad.id);
                if (el && el.parentNode) {
                    el.remove();
                    console.log('Popup auto-closed');
                }
            }, 10000);
        }

        function initPopups() {
            console.log('=== INITIALIZING POPUPS ===');
            console.log('Total popups:', popupAds.length);

            if (popupAds.length === 0) {
                console.log('No popup ads to show!');
                return;
            }

            popupAds.forEach(function(ad, index) {
                // Check if ad has expired
                if (ad.end_date) {
                    const endDate = new Date(ad.end_date);
                    const now = new Date();
                    if (now > endDate) {
                        console.log('Ad #' + ad.id + ' has expired, skipping');
                        return;
                    }
                }

                // Show popup on every page load/refresh until end date
                // Don't use localStorage to block it - show it every time
                const delay = (index + 1) * 2000;
                console.log('Scheduling popup #' + ad.id + ' in ' + delay + 'ms');

                setTimeout(function() {
                    console.log('Showing popup #' + ad.id + ' now!');
                    showPopupAd(ad);
                }, delay);
            });
        }

        // Force show after page load
        if (document.readyState === 'loading') {
            console.log('Document still loading, waiting for DOMContentLoaded');
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOMContentLoaded fired');
                setTimeout(initPopups, 1000);
            });
        } else {
            console.log('Document already loaded, initializing immediately');
            setTimeout(initPopups, 1000);
        }

        // Also try on window load
        window.addEventListener('load', function() {
            console.log('Window load event fired');
            setTimeout(initPopups, 1500);
        });

        console.log('=== POPUP ADS DEBUG END ===');
    </script>
    @else
    <script>
        console.log('=== NO POPUP ADS FOUND ===');
        console.log('popupAds isset:', @json(isset($popupAds)));
        console.log('popupAds count:', @json(isset($popupAds) ? $popupAds->count() : 0));
    </script>
    @endif
@endpush

