@extends('student.layouts.app')

@section('title', 'Course Content')
@section('page-title', 'Course Content')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-2"><i class="fas fa-file-alt me-2"></i>Course Content</h4>
            <p class="text-muted mb-0">Full course details for your enrolled courses.</p>
        </div>
    </div>

    @forelse($courses as $courseData)
        @php
            $course = $courseData['course'];
        @endphp
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">{{ $course->title }}</h5>
                        <div class="text-muted small">
                            @if($course->category)
                                <span class="me-2"><i class="fas fa-folder-open me-1"></i>{{ $course->category->name }}</span>
                            @endif
                            @if($course->level)
                                <span class="me-2"><i class="fas fa-signal me-1"></i>{{ $course->level->name }}</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('courses.show', [$course->category?->slug ?? 'general', $course->level?->slug ?? 'beginner', $course->slug ?? 'course-' . $course->id]) }}"
                       class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener">
                        View Public Page
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="img-fluid rounded mb-3" style="max-height: 260px; object-fit: cover;">
                @endif

                @if($course->short_description)
                    <div class="alert alert-light border">
                        <strong>Summary:</strong> {{ $course->short_description }}
                    </div>
                @endif

                @if($course->description)
                    <div class="course-content-details">
                        {!! $course->description !!}
                    </div>
                @else
                    <p class="text-muted mb-0">No content available for this course.</p>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            You have no enrolled courses yet.
        </div>
    @endforelse
</div>
@endsection
