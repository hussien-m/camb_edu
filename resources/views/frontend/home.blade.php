@extends('frontend.layouts.app')

@section('title', setting('site_title', 'Cambridge College - Best Education in Libya'))

@push('styles')
<style>
    /* ============================================
       HERO SECTION - Modern Gradient Design
    ============================================ */
    .hero {
        position: relative;
        min-height: 70vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
    }
    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
        padding: 60px 20px;
    }
    .hero h1 {
        font-size: 3.8rem;
        font-weight: 900;
        margin-bottom: 20px;
        line-height: 1.2;
        animation: fadeInUp 0.8s ease;
        text-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .hero p.lead {
        font-size: 1.4rem;
        margin-bottom: 40px;
        opacity: 0.95;
        animation: fadeInUp 1s ease;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Search Box - Premium Design */
    .search-box {
        background: white;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 25px 70px rgba(0,0,0,0.25);
        margin-top: 40px;
        animation: fadeInUp 1.2s ease;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }
    .search-box .form-control,
    .search-box .form-select {
        height: 58px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
        padding: 0 18px;
    }
    .search-box .form-control:focus,
    .search-box .form-select:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 0.25rem rgba(30,58,138,0.1);
    }
    .search-box .btn-search {
        height: 58px;
        background: #ffcc00;
        border: none;
        color: #1e3a8a;
        font-weight: 700;
        border-radius: 12px;
        font-size: 1.05rem;
        transition: all 0.3s;
        padding: 0 30px;
    }
    .search-box .btn-search:hover {
        background: #e6b800;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255,204,0,0.35);
    }

    /* ============================================
       SECTION HEADERS - Elegant
    ============================================ */
    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }
    .section-header h2 {
        font-size: 2.8rem;
        font-weight: 800;
        color: #1e3a8a;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 70px;
        height: 5px;
        background: #ffcc00;
        border-radius: 3px;
    }
    .section-header p {
        font-size: 1.15rem;
        color: #6b7280;
        margin-top: 25px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ============================================
       COURSE CARDS - Ultra Modern
    ============================================ */
    .course-card {
        height: 100%;
        border: none;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        background: white;
        position: relative;
    }
    .course-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
    }
    .course-card .card-img-wrapper {
        position: relative;
        overflow: hidden;
        height: 240px;
        background: #f3f4f6;
    }
    .course-card .card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }
    .course-card:hover .card-img-wrapper img {
        transform: scale(1.12) rotate(2deg);
    }
    .course-card .badge-featured {
        position: absolute;
        top: 18px;
        right: 18px;
        background: #ffcc00;
        color: #1e3a8a;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 800;
        font-size: 0.9rem;
        z-index: 2;
        box-shadow: 0 6px 16px rgba(255,204,0,0.4);
    }
    .course-card .card-body {
        padding: 28px;
    }
    .course-card .course-category {
        display: inline-block;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e3a8a;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 14px;
    }
    .course-card .course-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e3a8a;
        margin: 12px 0;
        line-height: 1.4;
        min-height: 68px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .course-card .course-description {
        color: #6b7280;
        font-size: 0.98rem;
        margin-bottom: 22px;
        line-height: 1.7;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .course-card .course-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 18px;
        border-top: 2px solid #f3f4f6;
        margin-bottom: 18px;
    }
    .course-card .course-meta .meta-item {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #6b7280;
        font-size: 0.92rem;
        font-weight: 500;
    }
    .course-card .course-meta .meta-item i {
        color: #1e3a8a;
        font-size: 1rem;
    }
    .course-card .course-price {
        font-size: 1.7rem;
        font-weight: 900;
        color: #1e3a8a;
        margin-bottom: 18px;
    }
    .course-card .btn-course {
        width: 100%;
        background: #1e3a8a;
        border: none;
        color: white;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .course-card .btn-course:hover {
        background: #003366;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(30,58,138,0.3);
    }

    /* ============================================
       STATS SECTION - Impressive
    ============================================ */
    .stats-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }
    .stats-section::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 60px 60px;
        animation: moveBackground 25s linear infinite;
    }
    .stat-card {
        text-align: center;
        padding: 35px;
        position: relative;
        z-index: 2;
    }
    .stat-card .stat-icon {
        font-size: 4rem;
        color: #ffcc00;
        margin-bottom: 25px;
        filter: drop-shadow(0 4px 12px rgba(255,204,0,0.3));
    }
    .stat-card .stat-number {
        font-size: 4rem;
        font-weight: 900;
        color: white;
        margin-bottom: 12px;
        text-shadow: 0 3px 15px rgba(0,0,0,0.3);
    }
    .stat-card .stat-label {
        font-size: 1.15rem;
        color: rgba(255,255,255,0.95);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* ============================================
       SUCCESS STORIES - Premium Cards
    ============================================ */
    .story-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.4s;
        height: 100%;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    .story-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #1e3a8a 0%, #ffcc00 100%);
        transform: scaleX(0);
        transition: transform 0.4s;
    }
    .story-card:hover::before {
        transform: scaleX(1);
    }
    .story-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        border-color: #ffcc00;
    }
    .story-card .story-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #ffcc00;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(255,204,0,0.35);
    }
    .story-card .story-name {
        font-size: 1.45rem;
        font-weight: 800;
        color: #1e3a8a;
        margin-bottom: 10px;
    }
    .story-card .story-meta {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 20px;
        font-weight: 500;
    }
    .story-card .story-quote {
        font-style: italic;
        color: #4b5563;
        line-height: 1.9;
        font-size: 1.05rem;
        position: relative;
        padding: 25px 0;
    }
    .story-card .story-quote::before {
        content: '"';
        font-size: 5rem;
        color: #ffcc00;
        opacity: 0.2;
        position: absolute;
        top: -15px;
        left: -15px;
        font-family: Georgia, serif;
        font-weight: 700;
    }

    /* ============================================
       FEATURE BOXES - Professional
    ============================================ */
    .feature-box {
        text-align: center;
        padding: 45px 30px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.4s;
        height: 100%;
        border: 2px solid transparent;
        position: relative;
    }
    .feature-box:hover {
        transform: translateY(-12px);
        border-color: #1e3a8a;
        box-shadow: 0 20px 50px rgba(30,58,138,0.2);
    }
    .feature-box .feature-icon {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 28px;
        font-size: 2.5rem;
        color: #ffcc00;
        transition: all 0.5s;
        box-shadow: 0 8px 20px rgba(30,58,138,0.2);
    }
    .feature-box:hover .feature-icon {
        transform: rotateY(360deg) scale(1.1);
    }
    .feature-box h5 {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1e3a8a;
        margin-bottom: 18px;
    }
    .feature-box p {
        color: #6b7280;
        line-height: 1.8;
        font-size: 1.02rem;
    }

    /* ============================================
       ANIMATIONS
    ============================================ */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes moveBackground {
        from { transform: translate(0, 0); }
        to { transform: translate(60px, 60px); }
    }

    /* ============================================
       SECTIONS LAYOUT
    ============================================ */
    section {
        padding: 90px 0;
    }
    .section-light {
        background: #f9fafb;
    }
    .section-white {
        background: white;
    }

    /* ============================================
       BUTTONS - Enhanced
    ============================================ */
    .btn-view-all {
        background: #1e3a8a;
        color: white;
        border: none;
        padding: 16px 50px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s;
        box-shadow: 0 8px 20px rgba(30,58,138,0.2);
    }
    .btn-view-all:hover {
        background: #003366;
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(30,58,138,0.3);
        color: white;
    }

    /* ============================================
       RESPONSIVE DESIGN
    ============================================ */
    @media(max-width:768px){
        .hero h1 { font-size: 2.4rem; }
        .hero p.lead { font-size: 1.15rem; }
        .search-box { padding: 25px 20px; }
        .section-header h2 { font-size: 2.2rem; }
        .stat-card .stat-number { font-size: 2.8rem; }
        .stat-card .stat-icon { font-size: 3rem; }
        section { padding: 60px 0; }
        .course-card .card-img-wrapper { height: 200px; }
    }
</style>
@endpush

@section('content')

<!-- ============================================
     HERO SECTION
============================================ -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            @if($banners && $banners->count() > 0)
                <h1>{{ $banners->first()->title }}</h1>
                <p class="lead">{{ $banners->first()->subtitle }}</p>
            @else
                <h1>🎓 Learn Without Limits</h1>
                <p class="lead">Discover courses that match your professional goals and develop your skills with the best trainers in Libya</p>
            @endif

            <!-- Advanced Search Box -->
            <div class="search-box">
                <form action="{{ route('courses.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <select name="category_id" class="form-select">
                                <option value="">📚 All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <select name="level_id" class="form-select">
                                <option value="">📊 All Levels</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-8">
                            <input type="text" name="keyword" class="form-control" placeholder="Search for courses...">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button type="submit" class="btn btn-search w-100">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

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
                            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
                        @else
                            <img src="https://picsum.photos/seed/{{ $course->title }}/400/300" alt="{{ $course->title }}">
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

                        @if($course->price && $course->price > 0)
                            <div class="course-price">{{ number_format($course->price) }} LYD</div>
                        @else
                            <div class="course-price" style="color: #10b981;">Free</div>
                        @endif

                        <a href="{{ route('courses.show', [$course->category->slug, $course->level->slug, $course->slug]) }}" class="btn btn-course">
                            <i class="fas fa-arrow-right me-2"></i> View Details
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
                    <div class="stat-number">{{ \App\Models\Course::where('status', 'active')->count() }}+</div>
                    <div class="stat-label">Active Courses</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-number">{{ \App\Models\SuccessStory::where('is_published', true)->count() * 100 }}+</div>
                    <div class="stat-label">Graduated Students</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-number">{{ \App\Models\CourseCategory::count() * 5 }}+</div>
                    <div class="stat-label">Expert Instructors</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number">100%</div>
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
<section class="section-white">
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
<section class="newsletter-section py-5" style="background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%); margin-top: 80px;">
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
                    <form id="newsletter-form">
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
     CONTACT SECTION
============================================ -->
<section class="section-white">
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
<script>
// Newsletter Subscription Script
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletter-form');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const email = document.getElementById('newsletter-email').value;
            const messageEl = document.getElementById('newsletter-message');
            const submitBtn = form.querySelector('button[type="submit"]');

            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Subscribing...';

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
                    messageEl.className = 'mt-3 text-center fw-bold text-success';
                    messageEl.textContent = data.message;
                    form.reset();
                } else {
                    messageEl.className = 'mt-3 text-center fw-bold text-danger';
                    messageEl.textContent = data.message;
                }
            })
            .catch(error => {
                messageEl.className = 'mt-3 text-center fw-bold text-danger';
                messageEl.textContent = 'An error occurred. Please try again.';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Subscribe Now';

                // Clear message after 5 seconds
                setTimeout(() => {
                    messageEl.textContent = '';
                }, 5000);
            });
        });
    }
});

// Contact Form Script
// Contact Form Script
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('contactAlert');
    const alertMessage = document.getElementById('contactAlertMessage');

    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        alertBox.style.display = 'none';

        // Get form data
        const formData = new FormData(form);

        // Send Ajax request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw { status: response.status, data: err };
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Success message
                alertBox.className = 'alert alert-success alert-dismissible fade show';
                alertMessage.textContent = data.message || 'Your message has been sent successfully!';
                alertBox.style.display = 'block';

                // Reset form
                form.reset();

                // Scroll to alert
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // Error message
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertMessage.textContent = data.message || 'Something went wrong. Please try again.';
                alertBox.style.display = 'block';
            }
        })
        .catch(error => {
            if (error.status === 422 && error.data.errors) {
                // Validation errors
                const errors = error.data.errors;
                for (const field in errors) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.parentElement.querySelector('.invalid-feedback:last-child');
                        if (feedback) {
                            feedback.textContent = errors[field][0];
                            feedback.style.display = 'block';
                        }
                    }
                }

                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertMessage.textContent = 'Please fix the errors below.';
                alertBox.style.display = 'block';
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // General error
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertMessage.textContent = 'An error occurred. Please try again later.';
                alertBox.style.display = 'block';
            }
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Send Message';
        });
    });
});
</script>
@endpush
