@extends('frontend.layouts.app')

@section('title', 'Our Courses - ' . setting('site_name', 'Cambridge International College in UK'))

@section('description', 'Browse our comprehensive catalog of professional courses and training programs. Find the perfect course to advance your career.')

@section('keywords', 'courses, training, education, professional development, ' . setting('seo_keywords', 'Cambridge International College in UK'))

@section('canonical', route('courses.index'))

@section('og_title', 'Our Courses - ' . setting('site_name', 'Cambridge College'))
@section('og_description', 'Browse our comprehensive catalog of professional courses and training programs')

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
        <!-- Mobile Filter Toggle Button -->
        <div class="mobile-filter-toggle d-lg-none">
            <button class="btn-mobile-filter" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilterCollapse">
                <i class="fas fa-sliders-h"></i>
                <span>Filter Courses</span>
                @php
                    $activeFilters = collect([request('category_id'), request('level_id'), request('keyword')])->filter()->count();
                @endphp
                @if($activeFilters > 0)
                    <span class="filter-badge">{{ $activeFilters }}</span>
                @endif
                <i class="fas fa-chevron-down toggle-icon"></i>
            </button>
        </div>

        <!-- Mobile Filter Collapse -->
        <div class="collapse d-lg-none {{ request()->hasAny(['category_id', 'level_id', 'keyword']) ? 'show' : '' }}" id="mobileFilterCollapse">
            <div class="mobile-filter-content">
                <form id="mobileFilterForm" action="{{ route('courses.index') }}" method="GET" data-url="{{ route('courses.index') }}">
                    <div class="mobile-filter-grid">
                        <div class="filter-item">
                            <label><i class="fas fa-folder"></i> Category</label>
                            <select name="category_id" id="mobileCategorySelect" class="form-select filter-input">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}"
                                        {{ request('category_id') == $cat->id || (isset($category) && $category->id == $cat->id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label><i class="fas fa-signal"></i> Level</label>
                            <select name="level_id" id="mobileLevelSelect" class="form-select filter-input">
                                <option value="">All Levels</option>
                                @foreach($levels as $lvl)
                                    <option value="{{ $lvl->id }}" data-slug="{{ $lvl->slug }}"
                                        {{ request('level_id') == $lvl->id || (isset($level) && $level->id == $lvl->id) ? 'selected' : '' }}>
                                        {{ $lvl->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="filter-item mt-3">
                        <label><i class="fas fa-search"></i> Search</label>
                        <input type="text" name="keyword" class="form-control filter-input"
                               placeholder="Search for a course..."
                               value="{{ request('keyword') }}">
                    </div>
                    <div class="mobile-filter-actions">
                        <button type="submit" class="btn btn-filter-apply">
                            <i class="fas fa-search"></i> Apply
                        </button>
                        @if(request()->hasAny(['category_id', 'level_id', 'keyword']))
                            <a href="{{ route('courses.index') }}" class="btn btn-filter-reset">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Filter Sidebar (Desktop) -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="filter-sidebar">
                    <h5>
                        <i class="fas fa-filter"></i>
                        Filter Results
                    </h5>
                    <form id="filterForm" action="{{ route('courses.index') }}" method="GET" data-url="{{ route('courses.index') }}">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="categorySelect" class="form-select filter-input">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}"
                                        {{ request('category_id') == $cat->id || (isset($category) && $category->id == $cat->id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="level_id" id="levelSelect" class="form-select filter-input">
                                <option value="">All Levels</option>
                                @foreach($levels as $lvl)
                                    <option value="{{ $lvl->id }}" data-slug="{{ $lvl->slug }}"
                                        {{ request('level_id') == $lvl->id || (isset($level) && $level->id == $lvl->id) ? 'selected' : '' }}>
                                        {{ $lvl->name }}
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
                        @include('frontend.partials.course-grid', ['courses' => $courses])
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
