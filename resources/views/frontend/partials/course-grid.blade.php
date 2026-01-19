{{-- Course Grid Partial - Used for AJAX Loading --}}

@if($courses->total() > 0)
<div class="result-info">
    <h5>
        <i class="fas fa-graduation-cap"></i>
        Showing {{ ($courses->currentPage() - 1) * $courses->perPage() + 1 }}-{{ min($courses->currentPage() * $courses->perPage(), $courses->total()) }} of {{ $courses->total() }} courses
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
                         decoding="async"
                         width="400"
                         height="300"
                         style="object-fit: cover;">
                @else
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect fill='%23e5e7eb' width='400' height='300'/%3E%3Ctext fill='%239ca3af' font-family='sans-serif' font-size='24' x='50%25' y='50%25' text-anchor='middle' dominant-baseline='middle'%3ENo Image%3C/text%3E%3C/svg%3E"
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
                <p class="course-description">{{ Str::limit(strip_tags(html_entity_decode($course->description)), 100) }}</p>

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

                @php
                    $categorySlug = $course->category?->slug ?? 'general';
                    $levelSlug = $course->level?->slug ?? 'beginner';
                    $courseSlug = $course->slug ?? 'course-' . $course->id;
                @endphp
                <a href="{{ route('courses.show', [$categorySlug, $levelSlug, $courseSlug]) }}" class="btn btn-course">
                    <i class="fas fa-arrow-right"></i> View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    @php
        $resetRoute = $resetRoute ?? route('courses.index');
    @endphp
    <div class="col-12">
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h4>No Results Found</h4>
            <p>Try adjusting your search criteria or <a href="{{ $resetRoute }}" style="color: #1e3a8a; font-weight: 700;">reset the filter</a></p>
        </div>
    </div>
    @endforelse
</div>
