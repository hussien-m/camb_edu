@extends('frontend.layouts.app')

@section('title', $course->title . ' - ' . setting('site_name', 'Cambridge College'))

@section('description', Str::limit(strip_tags($course->short_description ?? $course->description), 160))

@push('styles')
<style>
    /* Page Header Styles */
    .page-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        animation: moveBackground 30s linear infinite;
    }

    @keyframes moveBackground {
        0% { transform: translate(0, 0); }
        100% { transform: translate(60px, 60px); }
    }

    .page-header h1 {
        color: white;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.2rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }

    .breadcrumb {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 12px 24px;
        border-radius: 50px;
        display: inline-flex;
        margin: 0 0 20px 0;
        position: relative;
        z-index: 1;
    }

    .breadcrumb-item {
        color: white;
        font-weight: 500;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: '›';
        color: rgba(255, 255, 255, 0.7);
        padding: 0 10px;
    }

    .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #ffcc00;
    }

    .breadcrumb-item.active {
        color: #ffcc00;
        font-weight: 600;
    }

    /* Course Content */
    .course-content-section {
        background: #f9fafb;
        padding: 80px 0;
    }

    .course-main-card {
        background: white;
        border-radius: 24px;
        padding: 50px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 2px solid #f0f0f0;
        margin-bottom: 30px;
    }

    .course-image-wrapper {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(30, 58, 138, 0.15);
        margin-bottom: 40px;
    }

    .course-image {
        width: 100%;
        height: 450px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .course-image-wrapper:hover .course-image {
        transform: scale(1.05);
    }

    .course-category-badge {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e3a8a;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .course-title {
        color: #1e3a8a;
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .course-short-desc {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 5px solid #1e3a8a;
        padding: 25px 30px;
        margin: 30px 0;
        border-radius: 12px;
        font-size: 1.15rem;
        color: #1e3a8a;
        line-height: 1.7;
    }

    .course-description {
        color: #4b5563;
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .course-description h1,
    .course-description h2 {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 2rem;
        margin-top: 40px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #ffcc00;
        display: inline-block;
    }

    .course-description h3 {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 1.5rem;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .course-description h4 {
        color: #3b82f6;
        font-weight: 600;
        font-size: 1.25rem;
        margin-top: 25px;
        margin-bottom: 12px;
    }

    .course-description p {
        margin-bottom: 20px;
    }

    .course-description ul,
    .course-description ol {
        padding-left: 30px;
        margin-bottom: 20px;
    }

    .course-description ul li,
    .course-description ol li {
        margin-bottom: 10px;
        position: relative;
    }

    .course-description ul li::marker {
        color: #1e3a8a;
        font-size: 1.2rem;
    }

    .course-description ol li::marker {
        color: #1e3a8a;
        font-weight: 700;
    }

    .course-description img {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        margin: 30px 0;
    }

    /* Sidebar Styles */
    .course-info-sidebar {
        background: white;
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 2px solid #f0f0f0;
    }

    .sidebar-title {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-title i {
        color: #ffcc00;
        font-size: 1.8rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 12px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.1);
    }

    .info-item-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .info-item-content strong {
        color: #1e3a8a;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
        font-size: 0.95rem;
    }

    .info-item-content span {
        color: #ffcc00;
        font-size: 1rem;
    }

    .course-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1e3a8a;
        text-align: center;
        padding: 25px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 16px;
        margin: 25px 0;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.15);
    }

    .btn-enroll {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        margin-bottom: 15px;
        cursor: pointer;
    }

    .btn-enroll:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .btn-enroll form {
        width: 100%;
        margin: 0;
    }
    }

    .btn-back {
        width: 100%;
        padding: 14px;
        background: white;
        color: #1e3a8a;
        border: 2px solid #1e3a8a;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #1e3a8a;
        color: white;
        transform: translateY(-2px);
    }

    /* Related Courses */
    .related-courses-box {
        background: white;
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 2px solid #f0f0f0;
        margin-top: 30px;
    }

    .related-course-item {
        padding: 20px;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 12px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .related-course-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.15);
    }

    .related-course-item h6 {
        color: #1e3a8a;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.05rem;
    }

    .related-course-item h6 a {
        color: #1e3a8a;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .related-course-item h6 a:hover {
        color: #3b82f6;
    }

    .badge-level {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .related-course-price {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Course Inquiry Form */
    .inquiry-form-box {
        background: white;
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 2px solid #f0f0f0;
        margin-top: 30px;
    }

    .inquiry-form-box .form-group {
        margin-bottom: 20px;
    }

    .inquiry-form-box label {
        color: #1e3a8a;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }

    .inquiry-form-box .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .inquiry-form-box .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .inquiry-form-box textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .btn-submit-inquiry {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit-inquiry:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }

    .alert {
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .page-header h1 {
            font-size: 2.2rem;
        }

        .course-main-card {
            padding: 35px;
        }

        .course-title {
            font-size: 2rem;
        }

        .course-info-sidebar {
            position: static;
            margin-top: 30px;
        }
    }

    @media (max-width: 767px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .page-header {
            padding: 70px 0 50px;
        }

        .course-main-card {
            padding: 25px;
            border-radius: 16px;
        }

        .course-title {
            font-size: 1.6rem;
        }

        .course-image {
            height: 250px;
        }

        .course-price {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center">
                <h1>{{ $course->title }}</h1>
                @if($course->short_description)
                <p>{{ $course->short_description }}</p>
                @endif
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item active">{{ $course->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Course Content -->
<section class="course-content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="course-main-card">
                    <!-- Category Badge -->
                    @if($course->category)
                    <div class="course-category-badge">
                        @if($course->category->icon)
                            <i class="{{ $course->category->icon }}"></i>
                        @else
                            <i class="fas fa-graduation-cap"></i>
                        @endif
                        <span>{{ $course->category->name }}</span>
                    </div>
                    @endif

                    <!-- Course Image -->
                    @if($course->image)
                    <div class="course-image-wrapper">
                        <img src="{{ asset('storage/' . $course->image) }}" class="course-image" alt="{{ $course->title }}">
                    </div>
                    @else
                    <div class="course-image-wrapper">
                        <img src="https://picsum.photos/seed/{{ $course->id }}/800/450" class="course-image" alt="{{ $course->title }}">
                    </div>
                    @endif

                    <!-- Course Title -->
                    <h1 class="course-title">{{ $course->title }}</h1>

                    <!-- Course Code Badge -->
                    @if($course->course_code)
                    <div class="mb-3">
                        <span class="badge bg-primary" style="font-size: 1rem; padding: 8px 16px;">
                            <i class="fas fa-tag me-2"></i>Course Code: {{ $course->course_code }}
                        </span>
                    </div>
                    @endif

                    <!-- Short Description -->
                    @if($course->short_description)
                    <div class="course-short-desc">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ $course->short_description }}
                    </div>
                    @endif

                    <!-- Course Description -->
                    @if($course->description)
                    <div class="course-description">
                        {!! $course->description !!}
                    </div>
                    @endif

                    <!-- Course Inquiry Form -->
                    <div class="inquiry-form-box mt-5">
                        <h4 class="sidebar-title">
                            <i class="fas fa-envelope"></i>
                            <span>Inquire About This Course</span>
                        </h4>

                        <div id="inquiry-alert" style="display: none;"></div>

                        <form id="inquiryForm" action="{{ route('course.inquiry.store', $course->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="name" name="name"
                                               placeholder="Enter your full name" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control"
                                               id="email" name="email"
                                               placeholder="your.email@example.com" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" class="form-control"
                                               id="phone" name="phone"
                                               placeholder="+1 (555) 123-4567">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="message">Your Message <span class="text-danger">*</span></label>
                                        <textarea class="form-control"
                                                  id="message" name="message" rows="5"
                                                  placeholder="Tell us about your interest in this course..." required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn-submit-inquiry" id="submitBtn">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Send Inquiry</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Course Info Sidebar -->
                <div class="course-info-sidebar">
                    <h4 class="sidebar-title">
                        <i class="fas fa-info-circle"></i>
                        <span>Course Information</span>
                    </h4>

                    @if($course->duration)
                    <div class="info-item">
                        <div class="info-item-icon">
                            <i class="far fa-clock"></i>
                        </div>
                        <div class="info-item-content">
                            <strong>Duration</strong>
                            <span>{{ $course->duration }}</span>
                        </div>
                    </div>
                    @endif

                    @if($course->level)
                    <div class="info-item">
                        <div class="info-item-icon">
                            <i class="fas fa-signal"></i>
                        </div>
                        <div class="info-item-content">
                            <strong>Level</strong>
                            <span class="badge-level">{{ $course->level->name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($course->mode)
                    <div class="info-item">
                        <div class="info-item-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="info-item-content">
                            <strong>Mode</strong>
                            <span>{{ ucfirst($course->mode) }}</span>
                        </div>
                    </div>
                    @endif

                    @if($course->fee && $course->fee > 0)
                    <div class="course-price">
                        {{ number_format($course->fee) }} LYD
                    </div>
                    @else
                    <div class="course-price" style="color: #10b981;">
                        Free
                    </div>
                    @endif

                    @auth('student')
                        @php
                            $isEnrolled = \App\Models\Enrollment::where('student_id', auth('student')->id())
                                ->where('course_id', $course->id)
                                ->exists();
                        @endphp

                        @if($isEnrolled)
                            <a href="{{ route('student.courses.index') }}" class="btn-enroll" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="fas fa-check-circle"></i>
                                <span>Already Enrolled - Go to Dashboard</span>
                            </a>
                        @else
                            <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-enroll">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Enroll Now</span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('student.login') }}" class="btn-enroll">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login to Enroll</span>
                        </a>
                    @endauth

                    <a href="{{ route('courses.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Courses</span>
                    </a>
                </div>

                <!-- Related Courses -->
                @if($relatedCourses->count() > 0)
                <div class="related-courses-box">
                    <h4 class="sidebar-title">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Related Courses</span>
                    </h4>

                    @foreach($relatedCourses as $related)
                    <div class="related-course-item">
                        <h6>
                            <a href="{{ route('courses.show', [$related->category->slug, $related->level->slug, $related->slug]) }}">
                                {{ $related->title }}
                            </a>
                        </h6>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            @if($related->level)
                            <span class="badge-level">{{ $related->level->name }}</span>
                            @endif
                            @if($related->fee && $related->fee > 0)
                            <span class="related-course-price">{{ number_format($related->fee) }} LYD</span>
                            @else
                            <span class="related-course-price" style="color: #10b981;">Free</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    $('#inquiryForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#inquiry-alert').hide();

        // Disable submit button
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Sending...</span>');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Show success message
                $('#inquiry-alert')
                    .removeClass('alert-danger')
                    .addClass('alert alert-success')
                    .html('<i class="fas fa-check-circle me-2"></i> Thank you for your inquiry! We will contact you soon.')
                    .slideDown();

                // Reset form
                $('#inquiryForm')[0].reset();

                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);

                // Hide success message after 5 seconds
                setTimeout(function() {
                    $('#inquiry-alert').slideUp();
                }, 5000);

                // Scroll to alert
                $('html, body').animate({
                    scrollTop: $('#inquiry-alert').offset().top - 100
                }, 500);
            },
            error: function(xhr) {
                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {
                        const input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(messages[0]);
                    });

                    // Show error alert
                    $('#inquiry-alert')
                        .removeClass('alert-success')
                        .addClass('alert alert-danger')
                        .html('<i class="fas fa-exclamation-circle me-2"></i> Please fix the errors below.')
                        .slideDown();
                } else {
                    // General error
                    $('#inquiry-alert')
                        .removeClass('alert-success')
                        .addClass('alert alert-danger')
                        .html('<i class="fas fa-exclamation-circle me-2"></i> Something went wrong. Please try again.')
                        .slideDown();
                }

                // Scroll to alert
                $('html, body').animate({
                    scrollTop: $('#inquiry-alert').offset().top - 100
                }, 500);
            }
        });
    });
});
</script>
@endpush

@endsection
