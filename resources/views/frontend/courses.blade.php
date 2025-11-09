@extends('frontend.layouts.app')

@section('title', 'Our Courses - ' . setting('site_name', 'Cambridge College'))

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
        margin: 0;
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

    /* Filter Sidebar */
    .filter-sidebar {
        background: white;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 100px;
        border: 2px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .filter-sidebar:hover {
        box-shadow: 0 15px 40px rgba(30, 58, 138, 0.15);
        border-color: #1e3a8a;
    }

    .filter-sidebar h5 {
        color: #1e3a8a;
        font-weight: 700;
        margin-bottom: 25px;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-sidebar h5 i {
        color: #ffcc00;
        font-size: 1.5rem;
    }

    .filter-sidebar .form-label {
        color: #1e3a8a;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-sidebar .form-select,
    .filter-sidebar .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .filter-sidebar .form-select:focus,
    .filter-sidebar .form-control:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        background: white;
    }

    .btn-filter {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3);
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
    }

    .btn-reset {
        width: 100%;
        padding: 12px;
        background: white;
        color: #ef4444;
        border: 2px solid #ef4444;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        margin-top: 10px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-reset:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Result Info */
    .result-info {
        background: white;
        padding: 20px 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border-left: 5px solid #1e3a8a;
    }

    .result-info h5 {
        color: #1e3a8a;
        font-weight: 700;
        margin: 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .result-info .badge {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Course Cards */
    .course-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        height: 100%;
        display: flex;
        flex-direction: column;
        background: white;
    }

    .course-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.2);
    }

    .card-img-wrapper {
        position: relative;
        overflow: hidden;
        height: 220px;
    }

    .card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .course-card:hover .card-img-wrapper img {
        transform: scale(1.1) rotate(2deg);
    }

    .badge-featured {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #ffcc00 0%, #ff9800 100%);
        color: #1e3a8a;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(255, 204, 0, 0.4);
        z-index: 2;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .course-card .card-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .course-category {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e3a8a;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .course-title {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 12px;
        line-height: 1.4;
        min-height: 60px;
    }

    .course-description {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        padding: 15px 0;
        border-top: 2px solid #f3f4f6;
        border-bottom: 2px solid #f3f4f6;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .meta-item i {
        color: #1e3a8a;
        font-size: 1rem;
    }

    .course-price {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e3a8a;
        margin-bottom: 15px;
    }

    .btn-course {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: auto;
    }

    .btn-course:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 5rem;
        color: #e5e7eb;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        color: #1e3a8a;
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Pagination */
    .pagination {
        margin-top: 40px;
        justify-content: center;
        gap: 8px;
    }

    .pagination .page-link {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        color: #1e3a8a;
        padding: 10px 18px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 0 4px;
    }

    .pagination .page-link:hover {
        background: #1e3a8a;
        color: white;
        border-color: #1e3a8a;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border-color: #1e3a8a;
        color: white;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        background: #f3f4f6;
        border-color: #e5e7eb;
        color: #9ca3af;
    }

    /* Loading Overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(249, 250, 251, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        border-radius: 24px;
    }

    .loading-spinner {
        text-align: center;
    }

    .loading-spinner .spinner {
        width: 60px;
        height: 60px;
        border: 4px solid #e5e7eb;
        border-top-color: #1e3a8a;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-spinner p {
        margin-top: 15px;
        color: #1e3a8a;
        font-weight: 600;
        font-size: 1.1rem;
    }

    /* Fade In Animation */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 991px) {
        .page-header h1 {
            font-size: 2.2rem;
        }

        .filter-sidebar {
            position: static;
            margin-bottom: 30px;
        }
    }

    @media (max-width: 767px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .page-header {
            padding: 70px 0 50px;
        }

        .result-info {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .course-meta {
            flex-direction: column;
            gap: 10px;
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
                <h1>🎓 All Training Courses</h1>
                <p>Choose the right course for you and start your learning journey with us</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Courses Content -->
<section class="py-5" style="background: #f9fafb;">
    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h5>
                        <i class="fas fa-filter"></i>
                        Filter Results
                    </h5>
                    <form id="filterForm" action="{{ route('courses.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select filter-input">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="level_id" class="form-select filter-input">
                                <option value="">All Levels</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}"
                                        {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Search by Keywords</label>
                            <input type="text" name="keyword" class="form-control filter-input"
                                   placeholder="Search for a course..."
                                   value="{{ request('keyword') }}">
                        </div>

                        <button type="submit" class="btn btn-filter">
                            <i class="fas fa-search"></i>
                            Apply Filter
                        </button>

                        @if(request()->hasAny(['category_id', 'level_id', 'keyword']))
                            <a href="{{ route('courses.index') }}" class="btn btn-reset" id="resetBtn">
                                <i class="fas fa-redo"></i>
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="col-lg-9" style="position: relative;">
                <div id="coursesWrapper">
                    <div id="coursesContainer">
                        @if($courses->total() > 0)
                        <div class="result-info">
                            <h5>
                                <i class="fas fa-graduation-cap"></i>
                                Showing {{ $courses->count() }} of {{ $courses->total() }} courses
                            </h5>
                            <span class="badge">{{ $courses->total() }} courses</span>
                        </div>
                        @endif

                        <div class="row g-4">
                    @forelse($courses as $course)
                    <div class="col-lg-4 col-md-6">
                        <div class="card course-card">
                            <div class="card-img-wrapper">
                                @if($course->image_path)
                                    <img src="{{ Storage::url($course->image_path) }}" alt="{{ $course->title }}">
                                @else
                                    <img src="https://picsum.photos/seed/{{ $course->id }}/400/300" alt="{{ $course->title }}">
                                @endif
                                @if($course->is_featured)
                                    <span class="badge-featured">⭐ Featured</span>
                                @endif
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
                                    <i class="fas fa-arrow-right"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h4>No Results Found</h4>
                            <p>Try adjusting your search criteria or <a href="{{ route('courses.index') }}" style="color: #1e3a8a; font-weight: 700;">reset the filter</a></p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($courses->hasPages())
                <div class="d-flex justify-content-center mt-5" id="paginationContainer">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
                @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const coursesWrapper = document.getElementById('coursesWrapper');
    const coursesContainer = document.getElementById('coursesContainer');
    const resetBtn = document.getElementById('resetBtn');

    // Handle filter form submission
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loadCourses(new FormData(this));
        });

        // Auto-submit on filter change
        const filterInputs = document.querySelectorAll('.filter-input');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.dispatchEvent(new Event('submit'));
            });
        });

        // Handle keyword search with delay
        const keywordInput = document.querySelector('[name="keyword"]');
        let searchTimeout;
        if (keywordInput) {
            keywordInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterForm.dispatchEvent(new Event('submit'));
                }, 500);
            });
        }
    }

    // Handle reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterForm.reset();
            loadCourses(new FormData());
        });
    }

    // Load courses function
    function loadCourses(formData) {
        // Show loading first
        showLoading();

        // Then scroll to courses area
        setTimeout(() => {
            coursesWrapper.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);

        // Build query string
        const params = new URLSearchParams(formData);
        const url = `{{ route('courses.index') }}?${params.toString()}`;

        // Fetch courses
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#coursesContainer');

            if (newContent) {
                // Update content with fade animation
                coursesContainer.style.opacity = '0';
                setTimeout(() => {
                    coursesContainer.innerHTML = newContent.innerHTML;
                    coursesContainer.style.opacity = '1';
                    coursesContainer.classList.add('fade-in');

                    // Re-attach pagination listeners
                    attachPaginationListeners();

                    // Smooth scroll to top of results
                    coursesWrapper.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Update URL without reload
                    window.history.pushState({}, '', url);

                    // Hide loading
                    hideLoading();
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error loading courses:', error);
            hideLoading();
        });
    }

    // Handle pagination clicks
    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('#paginationContainer .page-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (url && !this.parentElement.classList.contains('disabled')) {
                    loadCoursesFromUrl(url);
                }
            });
        });
    }

    // Load courses from URL
    function loadCoursesFromUrl(url) {
        // Show loading first
        showLoading();

        // Then scroll to top
        setTimeout(() => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 100);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#coursesContainer');

            if (newContent) {
                coursesContainer.style.opacity = '0';
                setTimeout(() => {
                    coursesContainer.innerHTML = newContent.innerHTML;
                    coursesContainer.style.opacity = '1';
                    coursesContainer.classList.add('fade-in');
                    attachPaginationListeners();

                    // Scroll to top smoothly
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    window.history.pushState({}, '', url);

                    // Hide loading
                    hideLoading();
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
        });
    }

    // Show loading overlay
    function showLoading() {
        // Remove existing overlay if any
        hideLoading();

        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.id = 'loadingOverlay';
        overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Loading Courses...</p>
            </div>
        `;
        coursesWrapper.appendChild(overlay);
    }

    // Hide loading overlay
    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.remove();
        }
    }

    // Initial pagination listeners
    attachPaginationListeners();
});
</script>
@endpush
