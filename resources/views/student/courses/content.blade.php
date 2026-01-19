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
            $collapseId = 'courseContent' . $course->id;
        @endphp
        <div class="accordion mb-3" id="courseContentAccordion{{ $course->id }}">
            <div class="accordion-item border-0 shadow-sm">
                <h2 class="accordion-header" id="heading{{ $course->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                        <div class="d-flex flex-column">
                            <strong>{{ $course->title }}</strong>
                            <span class="text-muted small">
                                @if($course->category)
                                    <i class="fas fa-folder-open me-1"></i>{{ $course->category->name }}
                                @endif
                                @if($course->level)
                                    <span class="ms-2"><i class="fas fa-signal me-1"></i>{{ $course->level->name }}</span>
                                @endif
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $course->id }}"
                     data-bs-parent="#courseContentAccordion{{ $course->id }}">
                    <div class="accordion-body">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('courses.show', [$course->category?->slug ?? 'general', $course->level?->slug ?? 'beginner', $course->slug ?? 'course-' . $course->id]) }}"
                               class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener">
                                View Public Page
                            </a>
                        </div>

                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}"
                                 class="img-fluid rounded mb-3" style="max-height: 260px; object-fit: cover;">
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
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            You have no enrolled courses yet.
        </div>
    @endforelse
</div>
@endsection
