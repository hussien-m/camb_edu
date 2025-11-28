@extends('frontend.layouts.app')

@section('title', 'Our Courses - ' . setting('site_name', 'Cambridge College'))

@push('styles')
@vite('resources/css/frontend-courses.css')
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
                    <form id="filterForm" action="{{ route('courses.index') }}" method="GET" data-url="{{ route('courses.index') }}">
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
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
                                @else
                                    <img src="https://picsum.photos/seed/{{ urlencode($course->title) }}/400/300" alt="{{ $course->title }}">
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
@vite('resources/js/frontend-courses.js')
@endpush
