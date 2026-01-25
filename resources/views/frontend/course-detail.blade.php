@extends('frontend.layouts.app')

@section('title', $course->title . ' - ' . setting('site_name', 'Cambridge College'))

@section('description', App\Helpers\SeoHelper::cleanDescription($course->short_description ?? $course->description))

@section('keywords', $course->category->name . ', ' . $course->level->name . ', ' . setting('seo_keywords', 'education, courses'))

@section('canonical', route('courses.show', [$course->category->slug, $course->level->slug, $course->slug]))

@section('og_type', 'article')
@section('og_title', $course->title)
@section('og_description', App\Helpers\SeoHelper::cleanDescription($course->short_description ?? $course->description))
@section('og_image', $course->image ? asset('storage/' . $course->image) : asset('images/default-course.jpg'))

@section('twitter_card', 'summary_large_image')
@section('twitter_title', $course->title)
@section('twitter_description', App\Helpers\SeoHelper::cleanDescription($course->short_description ?? $course->description))
@section('twitter_image', $course->image ? asset('storage/' . $course->image) : asset('images/default-course.jpg'))

@push('styles')
@vite('resources/css/frontend-course-detail.css')
@endpush

@push('schema')
<!-- Course Schema.org JSON-LD -->
<script type="application/ld+json">
{!! App\Helpers\SeoHelper::generateCourseSchema($course) !!}
</script>

<!-- Breadcrumb Schema.org JSON-LD -->
<script type="application/ld+json">
{!! App\Helpers\SeoHelper::generateBreadcrumbSchema([
    ['name' => 'Home', 'url' => route('home')],
    ['name' => 'Courses', 'url' => route('courses.index')],
    ['name' => $course->category->name, 'url' => route('courses.index', ['category_id' => $course->category->id])],
    ['name' => $course->title, 'url' => route('courses.show', [$course->category->slug, $course->level->slug, $course->slug])]
]) !!}
</script>
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
                        <img src="{{ asset('storage/' . $course->image) }}"
                             class="course-image"
                             alt="{{ $course->title }}"
                             width="800"
                             height="450"
                             loading="eager">
                    </div>
                    @else
                    <div class="course-image-wrapper">
                        <img src="https://picsum.photos/seed/{{ $course->id }}/800/450"
                             class="course-image"
                             alt="{{ $course->title }}"
                             width="800"
                             height="450"
                             loading="eager">
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

                    @if($lockOfferContent)
                        <div class="alert alert-warning mt-4">
                            <h5 class="mb-2"><i class="fas fa-lock me-2"></i>Content Locked</h5>
                            <p class="mb-3">This course is part of our offers. Please log in or register to view the full details.</p>
                            <a href="{{ route('student.login') }}" class="btn btn-primary me-2">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <a href="{{ route('student.register') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </div>
                    @elseif(isset($isEnrolled) && $isEnrolled && isset($contentDisabled) && $contentDisabled == true)
                        <!-- Enrolled but content disabled -->
                        <div class="alert alert-warning border-warning mt-4" style="border-left: 4px solid #f59e0b;">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-lock fa-2x me-3 mt-1" style="color: #f59e0b;"></i>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading mb-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Course Content Disabled
                                    </h5>
                                    <p class="mb-3">
                                        The content for this course is currently disabled. You cannot view the course materials or take exams until you contact the administration.
                                    </p>
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <a href="mailto:{{ setting('contact_email', 'info@example.com') }}?subject=Course Content Access Request - {{ $course->title }}&body=Hello,%0D%0A%0D%0AI am enrolled in the course '{{ $course->title }}' and would like to request access to the course content.%0D%0A%0D%0AThank you." 
                                           class="btn btn-primary">
                                            <i class="fas fa-envelope me-2"></i>Contact Administration
                                        </a>
                                        <span class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Email: <strong>{{ setting('contact_email', 'info@example.com') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(!Auth::guard('student')->check())
                        <!-- Not logged in -->
                        <div class="alert alert-info mt-4" style="border-left: 4px solid #3b82f6;">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle fa-2x me-3 mt-1" style="color: #3b82f6;"></i>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading mb-2">
                                        <i class="fas fa-lock me-2"></i>Login Required
                                    </h5>
                                    <p class="mb-3">
                                        Please log in or register to view the course content and enroll in this course.
                                    </p>
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <a href="{{ route('student.login') }}" class="btn btn-primary">
                                            <i class="fas fa-sign-in-alt me-2"></i>Login
                                        </a>
                                        <a href="{{ route('student.register') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-user-plus me-2"></i>Register
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::guard('student')->check() && (!isset($isEnrolled) || !$isEnrolled))
                        <!-- Logged in but not enrolled -->
                        <div class="alert alert-warning mt-4" style="border-left: 4px solid #f59e0b;">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-user-lock fa-2x me-3 mt-1" style="color: #f59e0b;"></i>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading mb-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Enrollment Required
                                    </h5>
                                    <p class="mb-3">
                                        You need to enroll in this course to view the content. Please enroll first to access the course materials.
                                    </p>
                                    <form action="{{ route('student.courses.enroll', $course) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-2"></i>Enroll Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($showContent) && $showContent && isset($isEnrolled) && $isEnrolled)
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

                        <!-- Contact Us Buttons -->
                        <div class="contact-buttons-section mt-4">
                            <h4 class="contact-section-title">
                                <i class="fas fa-headset"></i>
                                <span>Need Help? Contact Us</span>
                            </h4>
                            <div class="contact-buttons-wrapper">
                                @if(setting('contact_whatsapp'))
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('contact_whatsapp')) }}?text={{ urlencode('Hello! I am interested in the course: ' . $course->title) }}"
                                   class="contact-btn whatsapp-btn" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                    <div class="btn-content">
                                        <span class="btn-label">Chat on WhatsApp</span>
                                        <span class="btn-info">Quick Response</span>
                                    </div>
                                </a>
                                @endif

                                @if(setting('contact_email'))
                                @php
                                    $emailSubject = 'Inquiry about: ' . $course->title;
                                    $emailBody = "Hello,\n\nI am interested in learning more about the course: " . $course->title . "\n\nPlease provide me with more information.\n\nThank you.";
                                    $gmailLink = 'https://mail.google.com/mail/?view=cm&to=' . setting('contact_email') . '&su=' . rawurlencode($emailSubject) . '&body=' . rawurlencode($emailBody);
                                @endphp
                                <a href="{{ $gmailLink }}"
                                   class="contact-btn email-btn" target="_blank" rel="noopener noreferrer">
                                    <i class="fas fa-envelope"></i>
                                    <div class="btn-content">
                                        <span class="btn-label">Send Email</span>
                                        <span class="btn-info">{{ setting('contact_email') }}</span>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Course Inquiry Form -->
                        <div class="inquiry-form-box mt-5">
                            <h4 class="sidebar-title">
                                <i class="fas fa-envelope"></i>
                                <span>Inquire About This Course</span>
                            </h4>

                            <div id="inquiry-alert" style="display: none;"></div>

                            <form id="inquiryForm" action="{{ route('course.inquiry.store', $course->id) }}" method="POST">
                                @csrf

                                <!-- Honeypot Fields (hidden from real users, visible to bots) -->
                                <input type="text" name="website_url" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
                                <input type="text" name="phone_number_confirm" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">

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
                    @endif
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



                    @auth('student')
                        @php
                            $isEnrolled = \App\Models\Enrollment::where('student_id', auth('student')->id())
                                ->where('course_id', $course->id)
                                ->exists();
                        @endphp

                        @if($isEnrolled)
                            @if(isset($contentDisabled) && $contentDisabled)
                                <div class="alert alert-warning mb-3" style="border-left: 4px solid #f59e0b;">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-lock me-2 mt-1"></i>
                                        <div>
                                            <strong>Content Disabled</strong>
                                            <p class="mb-0 small">Course content is currently disabled. Please contact administration.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
