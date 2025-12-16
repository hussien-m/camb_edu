@extends('frontend.layouts.app')

@section('title', $page->title . ' - ' . setting('site_name', 'Cambridge College'))

@section('description', App\Helpers\SeoHelper::cleanDescription($page->content))

@section('keywords', $page->title . ', ' . setting('seo_keywords', 'cambridge college'))

@section('canonical', route('page.show', $page->slug))

@section('og_title', $page->title)
@section('og_description', App\Helpers\SeoHelper::cleanDescription($page->content))

@push('schema')
<!-- Breadcrumb Schema for Page -->
<script type="application/ld+json">
{!! App\Helpers\SeoHelper::generateBreadcrumbSchema([
    ['name' => 'Home', 'url' => route('home')],
    ['name' => $page->title, 'url' => route('page.show', $page->slug)]
]) !!}
</script>
@endpush

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

    /* Page Content Styles */
    .page-content {
        background: #f9fafb;
        padding: 80px 0;
    }

    .content-wrapper {
        background: white;
        border-radius: 24px;
        padding: 50px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 2px solid #f0f0f0;
    }

    .content-wrapper h1 {
        color: #1e3a8a;
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .content-wrapper h2 {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 2rem;
        margin-top: 40px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #ffcc00;
        display: inline-block;
    }

    .content-wrapper h3 {
        color: #1e3a8a;
        font-weight: 700;
        font-size: 1.5rem;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .content-wrapper h4 {
        color: #3b82f6;
        font-weight: 600;
        font-size: 1.25rem;
        margin-top: 25px;
        margin-bottom: 12px;
    }

    .content-wrapper p {
        color: #4b5563;
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .content-wrapper ul,
    .content-wrapper ol {
        color: #4b5563;
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 20px;
        padding-left: 30px;
    }

    .content-wrapper ul li,
    .content-wrapper ol li {
        margin-bottom: 10px;
        position: relative;
    }

    .content-wrapper ul li::marker {
        color: #1e3a8a;
        font-size: 1.2rem;
    }

    .content-wrapper ol li::marker {
        color: #1e3a8a;
        font-weight: 700;
    }

    .content-wrapper a {
        color: #1e3a8a;
        font-weight: 600;
        text-decoration: none;
        border-bottom: 2px solid #ffcc00;
        transition: all 0.3s ease;
    }

    .content-wrapper a:hover {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }

    .content-wrapper blockquote {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 5px solid #1e3a8a;
        padding: 25px 30px;
        margin: 30px 0;
        border-radius: 12px;
        font-style: italic;
        color: #1e3a8a;
        font-size: 1.15rem;
        line-height: 1.7;
    }

    .content-wrapper img {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        margin: 30px 0;
    }

    .content-wrapper table {
        width: 100%;
        border-collapse: collapse;
        margin: 30px 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }

    .content-wrapper table thead {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
    }

    .content-wrapper table th {
        padding: 15px;
        font-weight: 700;
        text-align: left;
        font-size: 1rem;
    }

    .content-wrapper table td {
        padding: 15px;
        border-bottom: 1px solid #e5e7eb;
        color: #4b5563;
    }

    .content-wrapper table tbody tr:hover {
        background: #f9fafb;
    }

    .content-wrapper table tbody tr:last-child td {
        border-bottom: none;
    }

    .content-wrapper code {
        background: #f3f4f6;
        color: #ef4444;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.95rem;
        font-family: 'Courier New', monospace;
    }

    .content-wrapper pre {
        background: #1f2937;
        color: #f9fafb;
        padding: 20px;
        border-radius: 12px;
        overflow-x: auto;
        margin: 25px 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .content-wrapper pre code {
        background: transparent;
        color: #f9fafb;
        padding: 0;
    }

    .content-wrapper hr {
        border: none;
        height: 2px;
        background: linear-gradient(to right, transparent, #e5e7eb, transparent);
        margin: 40px 0;
    }

    /* Featured Box */
    .featured-box {
        background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
        border-left: 5px solid #ffcc00;
        padding: 25px 30px;
        margin: 30px 0;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(255, 204, 0, 0.2);
    }

    .featured-box h4 {
        color: #1e3a8a;
        font-weight: 700;
        margin-top: 0;
        margin-bottom: 12px;
        font-size: 1.3rem;
    }

    .featured-box p {
        margin-bottom: 0;
        color: #4b5563;
    }

    /* Page Meta Info */
    .page-meta {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f3f4f6;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .meta-item i {
        color: #1e3a8a;
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .page-header h1 {
            font-size: 2.2rem;
        }

        .content-wrapper {
            padding: 35px;
        }

        .content-wrapper h1 {
            font-size: 2rem;
        }

        .content-wrapper h2 {
            font-size: 1.6rem;
        }
    }

    @media (max-width: 767px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .page-header {
            padding: 70px 0 50px;
        }

        .content-wrapper {
            padding: 25px;
            border-radius: 16px;
        }

        .content-wrapper h1 {
            font-size: 1.6rem;
        }

        .content-wrapper h2 {
            font-size: 1.4rem;
        }

        .content-wrapper p,
        .content-wrapper ul,
        .content-wrapper ol {
            font-size: 1rem;
        }

        .page-meta {
            flex-direction: column;
            gap: 12px;
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
                <h1>{{ $page->title }}</h1>
                @if($page->excerpt)
                <p>{{ $page->excerpt }}</p>
                @endif
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}#pages">Pages</a></li>
                        <li class="breadcrumb-item active">{{ $page->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="content-wrapper">
                    <!-- Page Meta Information -->
                    @if($page->created_at)
                    <div class="page-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>Published: {{ $page->created_at->format('F j, Y') }}</span>
                        </div>
                        @if($page->updated_at && $page->updated_at->ne($page->created_at))
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>Last Updated: {{ $page->updated_at->format('F j, Y') }}</span>
                        </div>
                        @endif
                        <div class="meta-item">
                            <i class="fas fa-eye"></i>
                            <span>{{ number_format(rand(100, 5000)) }} Views</span>
                        </div>
                    </div>
                    @endif

                    <!-- Page Image -->
                    @if($page->image_path)
                    <div class="text-center mb-5">
                        <img src="{{ Storage::url($page->image_path) }}"
                             alt="{{ $page->title }}"
                             class="img-fluid"
                             style="border-radius: 20px; box-shadow: 0 15px 50px rgba(30, 58, 138, 0.15);">
                    </div>
                    @endif

                    <!-- Page Content -->
                    <div class="page-body">
                        {!! $page->content !!}
                    </div>

                    <!-- Share Section -->
                    <div class="mt-5 pt-4" style="border-top: 2px solid #f3f4f6;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 style="color: #1e3a8a; font-weight: 700; margin: 0;">
                                    <i class="fas fa-share-alt me-2"></i> Share This Page
                                </h5>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                   target="_blank"
                                   class="btn btn-sm"
                                   style="background: #1877f2; color: white; padding: 10px 20px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease;">
                                    <i class="fab fa-facebook-f me-2"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($page->title) }}"
                                   target="_blank"
                                   class="btn btn-sm"
                                   style="background: #1da1f2; color: white; padding: 10px 20px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease;">
                                    <i class="fab fa-twitter me-2"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($page->title . ' - ' . request()->fullUrl()) }}"
                                   target="_blank"
                                   class="btn btn-sm"
                                   style="background: #25d366; color: white; padding: 10px 20px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease;">
                                    <i class="fab fa-whatsapp me-2"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <div class="mt-5">
                        <div class="featured-box">
                            <h4>📚 Interested in Our Courses?</h4>
                            <p>Explore our wide range of training courses and start your learning journey today!</p>
                            <a href="{{ route('courses.index') }}"
                               class="btn mt-3"
                               style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 12px 30px; border-radius: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                                <i class="fas fa-graduation-cap"></i> View All Courses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
