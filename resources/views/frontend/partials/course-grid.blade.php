{{-- Course Grid Partial - Used for AJAX Loading --}}

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
                    <img src="{{ asset('storage/' . $course->image) }}"
                         alt="{{ $course->title }}"
                         loading="lazy"
                         width="400"
                         height="300">
                @else
                    <img src="{{ asset('images/course-placeholder.jpg') }}"
                         alt="{{ $course->title }}"
                         loading="lazy"
                         width="400"
                         height="300">
                @endif
                @if($course->is_featured)
                    <span class="badge-featured">⭐ Featured</span>
                @endif
            </div>
            <div class="card-body">
                <span class="course-category">{{ $course->category->name ?? 'General' }}</span>
                <h5 class="course-title">{{ Str::limit($course->title, 60) }}</h5>
                <p class="course-description">{{ Str::limit($course->description, 100) }}</p>

                <div class="course-meta">
                    <span class="meta-item">
                        <i class="fas fa-signal"></i>
                        {{ $course->level->name ?? 'N/A' }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-clock"></i>
                        {{ $course->duration }} hours
                    </span>
                </div>

                <a href="{{ route('courses.show', [$course->category->slug ?? 'general', $course->level->slug ?? 'course', $course->slug]) }}"
                   class="btn btn-course">
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
