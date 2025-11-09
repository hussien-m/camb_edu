@extends('frontend.layouts.app')

@section('title', 'Success Stories - ' . setting('site_name', 'Cambridge College'))

@push('styles')
<style>
    /* Page Header Styles - Same as Courses Page */
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

    /* Main Content Area */
    .content-section {
        padding: 60px 0;
        background: #f9fafb;
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

    /* Story Cards - Same Style as Course Cards */
    .story-card {
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

    .story-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.2);
    }

    .card-img-wrapper {
        position: relative;
        overflow: hidden;
        height: 220px;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-img-wrapper img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.5s ease;
    }

    .story-card:hover .card-img-wrapper img {
        transform: scale(1.1);
    }

    .badge-verified {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .story-card .card-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .student-badge {
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

    .student-name {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 12px;
        line-height: 1.4;
        min-height: 60px;
    }

    .student-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        padding: 15px 0;
        border-top: 2px solid #f3f4f6;
        border-bottom: 2px solid #f3f4f6;
        flex-wrap: wrap;
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

    .story-quote {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-style: italic;
    }

    .story-rating {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        margin-bottom: 15px;
        padding: 15px 0;
        border-top: 2px solid #f3f4f6;
    }

    .story-rating i {
        color: #ffcc00;
        font-size: 1.1rem;
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

    /* Responsive */
    @media (max-width: 991px) {
        .page-header h1 {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 767px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .page-header p {
            font-size: 1rem;
        }

        .result-info {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .student-meta {
            flex-direction: column;
            gap: 10px;
        }

        .card-img-wrapper img {
            width: 120px;
            height: 120px;
        }
    }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="container text-center">
        <h1>Success Stories</h1>
        <p>Inspiring journeys from our successful graduates around the world</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Success Stories</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="content-section">
    <div class="container">
        @if($stories->count() > 0)
            <!-- Result Info -->
            <div class="result-info">
                <h5>
                    <i class="fas fa-star"></i>
                    Showing {{ $stories->count() }} Success Stories
                </h5>
                <span class="badge">{{ $stories->total() }} Total Stories</span>
            </div>

            <!-- Stories Grid -->
            <div class="row g-4">
                @foreach($stories as $story)
                    <div class="col-lg-4 col-md-6">
                        <div class="story-card">
                            <!-- Card Image -->
                            <div class="card-img-wrapper">
                                <span class="badge-verified">
                                    <i class="fas fa-check-circle"></i>
                                    Verified
                                </span>
                                @if($story->image)
                                    <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->student_name }}">
                                @else
                                    <img src="https://picsum.photos/seed/{{ md5($story->id ?? rand()) }}/200/200" alt="{{ $story->student_name }}">
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                @if($story->title)
                                    <span class="student-badge">{{ $story->title }}</span>
                                @endif

                                <h5 class="student-name">{{ $story->student_name ?? 'Student' }}</h5>

                                <div class="student-meta">
                                    @if($story->country)
                                        <div class="meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $story->country }}</span>
                                        </div>
                                    @endif
                                    @if($story->graduation_year)
                                        <div class="meta-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>{{ $story->graduation_year }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="story-quote">
                                    "{{ Str::limit(strip_tags($story->story), 180) }}"
                                </div>

                                <div class="story-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $stories->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h4>No Success Stories Available Yet</h4>
                <p>Check back soon for inspiring stories from our students!</p>
            </div>
        @endif
    </div>
</div>

@endsection
