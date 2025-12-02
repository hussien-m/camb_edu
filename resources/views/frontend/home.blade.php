@extends('frontend.layouts.app')

@section('title', setting('site_title', 'Cambridge College - Best Education in Libya'))

@push('styles')
    @vite('resources/css/frontend-home.css')
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
    @vite('resources/js/frontend-home.js')
@endpush

