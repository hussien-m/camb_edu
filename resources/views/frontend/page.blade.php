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

                    <!-- Accreditations Content -->
                    @if($page->slug === 'accreditations')
                    
                    <!-- Legal Registration Section -->
                    <div class="accreditations-info-section mt-5">
                        <div class="legal-registration-card">
                            <div class="card-header-section">
                                <i class="fas fa-building"></i>
                                <h3>Legal Registration</h3>
                            </div>
                            <div class="card-content">
                                <div class="info-row">
                                    <span class="info-label"><i class="fas fa-hashtag"></i> Company Number:</span>
                                    <span class="info-value">15794456</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label"><i class="fas fa-landmark"></i> Registrar:</span>
                                    <span class="info-value">The Registrar of Companies for England and Wales</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label"><i class="fas fa-university"></i> Legal Name:</span>
                                    <span class="info-value">CAMBRIDGE INTERNATIONAL COLLEGE IN UK LTD</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label"><i class="fas fa-map-marker-alt"></i> Registered Address:</span>
                                    <span class="info-value">86-90 Paul Street, London, England, EC2A 4NE</span>
                                </div>
                            </div>
                        </div>

                        <!-- Accreditations List -->
                        <div class="accreditations-list-card">
                            <div class="card-header-section">
                                <i class="fas fa-certificate"></i>
                                <h3>Our Accreditations</h3>
                            </div>
                            <div class="accreditations-list">
                                <div class="accreditation-item-list">
                                    <div class="accreditation-number">1</div>
                                    <div class="accreditation-details">
                                        <h4>UK Education Quality Management (UKEQM)</h4>
                                        <p>Validity can be checked at <a href="https://www.ukeqm.uk" target="_blank" rel="noopener">www.ukeqm.uk</a></p>
                                        <p class="legal-name">Legal search name: <strong>CAMBRIDGE INTERNATIONAL COLLEGE IN UK</strong></p>
                                    </div>
                                </div>
                                <div class="accreditation-item-list">
                                    <div class="accreditation-number">2</div>
                                    <div class="accreditation-details">
                                        <h4>International Accreditation Council (IAC)</h4>
                                        <p>Validity can be checked at <a href="https://www.iacouncil.org" target="_blank" rel="noopener">www.iacouncil.org</a></p>
                                        <p class="legal-name">Certificate number: <strong>EOM.2025.004496</strong></p>
                                        <p class="legal-name">Legal search name: <strong>CAMBRIDGE INTERNATIONAL COLLEGE IN UK</strong></p>
                                    </div>
                                </div>
                                <div class="accreditation-item-list">
                                    <div class="accreditation-number">3</div>
                                    <div class="accreditation-details">
                                        <h4>UK Register of Learning Providers (UKRLP)</h4>
                                        <p>Validity can be checked at <a href="https://www.ukrlp.co.uk" target="_blank" rel="noopener">www.ukrlp.co.uk</a></p>
                                        <p class="legal-name">UKPRN: <strong>10100136</strong></p>
                                        <p class="legal-name">Legal search name: <strong>CAMBRIDGE INTERNATIONAL COLLEGE IN UK</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Section -->
                        <div class="summary-card">
                            <div class="card-header-section">
                                <i class="fas fa-file-alt"></i>
                                <h3>Summary</h3>
                            </div>
                            <div class="summary-content">
                                <h4>Accreditation & Legal Status</h4>
                                <p>Cambridge International College in UK Ltd is a legally registered and internationally accredited higher education institution, committed to delivering high-quality academic and professional qualifications that meet recognized UK and international standards.</p>
                                <p>The College is officially registered with The Registrar of Companies for England and Wales under Company Number: <strong>15794456</strong>, operating under the legal name: <strong>CAMBRIDGE INTERNATIONAL COLLEGE IN UK LTD</strong></p>
                                <p><strong>Registered Address:</strong> 86–90 Paul Street, London, England, EC2A 4NE</p>
                                <p>This registration confirms the College's lawful establishment and operational compliance within the United Kingdom.</p>
                            </div>
                        </div>

                        <!-- Quality Assurance Section -->
                        <div class="quality-assurance-card">
                            <div class="card-header-section">
                                <i class="fas fa-shield-alt"></i>
                                <h3>Our Accreditation & International Recognition</h3>
                                <p class="subtitle">Quality Assurance</p>
                            </div>
                            <div class="quality-content">
                                <p>Cambridge International College holds multiple recognized accreditations and registrations that demonstrate its commitment to academic excellence, governance, and quality assurance across all levels of higher education, including Diplomas, Bachelor's Degrees, Master's Degrees, and Doctoral (PhD) programs.</p>
                                
                                <div class="accreditation-detail-box">
                                    <h5><i class="fas fa-check-circle"></i> 1. UK Education Quality Management (UKEQM)</h5>
                                    <p>The College is accredited by UK Education Quality Management, an organization dedicated to monitoring institutional quality, academic governance, teaching standards, and learner outcomes. This accreditation reflects the College's adherence to structured quality assurance frameworks and continuous academic improvement.</p>
                                    <p class="verification-link"><i class="fas fa-link"></i> Validity of this certificate can be checked at <a href="https://www.ukeqm.uk" target="_blank" rel="noopener">WWW.UKEQM.UK</a></p>
                                    <p class="legal-name">Legal search name: <strong>CAMBRIDGE INTERNATIONAL COLLEGE IN UK</strong></p>
                                </div>

                                <div class="accreditation-detail-box">
                                    <h5><i class="fas fa-check-circle"></i> 2. International Accreditation Council (IAC)</h5>
                                    <p>Cambridge International College is accredited by the International Accreditation Council, reinforcing its international standing and recognition. This accreditation supports the global credibility of the College's qualifications and affirms alignment with internationally accepted educational benchmarks.</p>
                                    <p class="verification-link"><i class="fas fa-link"></i> Validity of this certificate can be checked at <a href="https://www.iacouncil.org" target="_blank" rel="noopener">WWW.IACOUNCIL.ORG</a></p>
                                    <p class="legal-name">Certificate number: <strong>EOM.2025.004496</strong></p>
                                    <p class="legal-name">Legal search name: <strong>CAMBRIDGE INTERNATIONAL COLLEGE IN UK</strong></p>
                                </div>

                                <div class="accreditation-detail-box">
                                    <h5><i class="fas fa-check-circle"></i> 3. UK Register of Learning Providers (UKRLP)</h5>
                                    <p>The College is officially listed on the UK Register of Learning Providers (UKRLP), confirming its recognized status as a UK learning provider. UKRLP registration enhances transparency, institutional legitimacy, and recognition by employers, training bodies, and international partners.</p>
                                    <p class="verification-link"><i class="fas fa-link"></i> Validity of this certificate can be checked at <a href="https://www.ukrlp.co.uk" target="_blank" rel="noopener">www.ukrlp.co.uk</a></p>
                                    <p class="legal-name">UKPRN: <strong>10100136</strong></p>
                                    <p class="legal-name">Legal search name: <strong>CAMBRIDGE INTERNATIONAL COLLEGE IN UK</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Credibility Section -->
                        <div class="credibility-card">
                            <div class="card-header-section">
                                <i class="fas fa-graduation-cap"></i>
                                <h3>Academic Credibility & Recognition</h3>
                            </div>
                            <div class="credibility-content">
                                <p>Through its legal registration and multi-layered accreditation structure, Cambridge International College demonstrates a strong commitment to:</p>
                                <ul class="commitment-list">
                                    <li><i class="fas fa-check"></i> Delivering accredited Diploma, Bachelor's, Master's, and PhD programs</li>
                                    <li><i class="fas fa-check"></i> Maintaining robust academic and quality assurance systems</li>
                                    <li><i class="fas fa-check"></i> Meeting UK regulatory and international educational standards</li>
                                    <li><i class="fas fa-check"></i> Providing qualifications that are suitable for career progression, professional development, and international recognition</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Commitment Section -->
                        <div class="commitment-card">
                            <div class="card-header-section">
                                <i class="fas fa-star"></i>
                                <h3>Commitment to Excellence</h3>
                            </div>
                            <div class="commitment-content">
                                <p>Cambridge International College in UK Ltd is dedicated to academic integrity, innovation, and global accessibility in higher education. Its accredited status reflects a clear mission: to offer credible, structured, and internationally respected qualifications that empower learners and professionals worldwide.</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $accreditationsImages = [];
                        for ($i = 1; $i <= 12; $i++) {
                            $imagePath = public_path('images/accreditations/' . $i . '.png');
                            if (file_exists($imagePath)) {
                                $accreditationsImages[] = $i;
                            }
                        }
                    @endphp
                    @if(count($accreditationsImages) > 0)
                    <div class="accreditations-gallery-section mt-5 pt-5" style="border-top: 3px solid #ffcc00;">
                        <div class="text-center mb-5">
                            <h2 style="color: #1e3a8a; font-weight: 800; font-size: 2.8rem; margin-bottom: 15px;">
                                <i class="fas fa-images me-3" style="color: #ffcc00;"></i>Accreditation Certificates
                            </h2>
                            <p style="color: #6b7280; font-size: 1.2rem; max-width: 600px; margin: 0 auto;">
                                View our official accreditation certificates
                            </p>
                        </div>
                        
                        <div class="row g-4 justify-content-center">
                            @foreach($accreditationsImages as $imgNumber)
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="accreditation-item">
                                    <div class="accreditation-image-wrapper" onclick="openImageModal('{{ asset('images/accreditations/' . $imgNumber . '.png') }}', 'Accreditation {{ $imgNumber }}')">
                                        <img src="{{ asset('images/accreditations/' . $imgNumber . '.png') }}"
                                             alt="Accreditation Certificate {{ $imgNumber }}"
                                             class="accreditation-image"
                                             loading="lazy">
                                        <div class="accreditation-overlay">
                                            <i class="fas fa-search-plus"></i>
                                            <span>Click to View</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Image Modal -->
                    <div id="imageModal" class="image-modal">
                        <span class="modal-close" onclick="closeImageModal()">&times;</span>
                        <img class="modal-content" id="modalImage" onclick="event.stopPropagation();">
                        <div class="modal-caption" id="modalCaption"></div>
                    </div>

                    <style>
                        /* Accreditations Info Section Styles */
                        .accreditations-info-section {
                            margin-top: 40px;
                        }

                        .legal-registration-card,
                        .accreditations-list-card,
                        .summary-card,
                        .quality-assurance-card,
                        .credibility-card,
                        .commitment-card {
                            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                            border-radius: 20px;
                            padding: 35px;
                            margin-bottom: 30px;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                            border: 2px solid #e5e7eb;
                            transition: all 0.3s ease;
                        }

                        .legal-registration-card:hover,
                        .accreditations-list-card:hover,
                        .summary-card:hover,
                        .quality-assurance-card:hover,
                        .credibility-card:hover,
                        .commitment-card:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 15px 40px rgba(30, 58, 138, 0.15);
                            border-color: #ffcc00;
                        }

                        .card-header-section {
                            display: flex;
                            align-items: center;
                            gap: 15px;
                            margin-bottom: 25px;
                            padding-bottom: 20px;
                            border-bottom: 3px solid #ffcc00;
                        }

                        .card-header-section i {
                            font-size: 2rem;
                            color: #1e3a8a;
                            background: linear-gradient(135deg, #ffcc00 0%, #ffd700 100%);
                            width: 60px;
                            height: 60px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border-radius: 15px;
                            box-shadow: 0 5px 15px rgba(255, 204, 0, 0.3);
                        }

                        .card-header-section h3 {
                            color: #1e3a8a;
                            font-weight: 800;
                            font-size: 1.8rem;
                            margin: 0;
                        }

                        .card-header-section .subtitle {
                            color: #6b7280;
                            font-size: 1rem;
                            font-weight: 500;
                            margin: 0;
                            margin-left: auto;
                        }

                        .card-content {
                            color: #4b5563;
                            line-height: 1.8;
                        }

                        .info-row {
                            display: flex;
                            align-items: flex-start;
                            gap: 15px;
                            padding: 15px 0;
                            border-bottom: 1px solid #e5e7eb;
                        }

                        .info-row:last-child {
                            border-bottom: none;
                        }

                        .info-label {
                            font-weight: 700;
                            color: #1e3a8a;
                            min-width: 200px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        }

                        .info-label i {
                            color: #ffcc00;
                            font-size: 1.1rem;
                        }

                        .info-value {
                            color: #374151;
                            font-size: 1.05rem;
                            flex: 1;
                        }

                        /* Accreditations List */
                        .accreditations-list {
                            display: flex;
                            flex-direction: column;
                            gap: 25px;
                        }

                        .accreditation-item-list {
                            display: flex;
                            gap: 20px;
                            padding: 25px;
                            background: white;
                            border-radius: 15px;
                            border-left: 5px solid #1e3a8a;
                            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                            transition: all 0.3s ease;
                        }

                        .accreditation-item-list:hover {
                            transform: translateX(5px);
                            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.15);
                            border-left-color: #ffcc00;
                        }

                        .accreditation-number {
                            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
                            color: white;
                            width: 50px;
                            height: 50px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border-radius: 12px;
                            font-size: 1.5rem;
                            font-weight: 800;
                            flex-shrink: 0;
                            box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3);
                        }

                        .accreditation-details {
                            flex: 1;
                        }

                        .accreditation-details h4 {
                            color: #1e3a8a;
                            font-weight: 700;
                            font-size: 1.4rem;
                            margin-bottom: 12px;
                        }

                        .accreditation-details p {
                            color: #6b7280;
                            margin-bottom: 8px;
                            line-height: 1.7;
                        }

                        .accreditation-details a {
                            color: #3b82f6;
                            font-weight: 600;
                            text-decoration: none;
                            transition: all 0.3s ease;
                        }

                        .accreditation-details a:hover {
                            color: #1e3a8a;
                            text-decoration: underline;
                        }

                        .legal-name {
                            color: #374151;
                            font-size: 0.95rem;
                            margin-top: 8px;
                        }

                        .legal-name strong {
                            color: #1e3a8a;
                            font-weight: 700;
                        }

                        /* Summary Content */
                        .summary-content h4 {
                            color: #1e3a8a;
                            font-weight: 700;
                            font-size: 1.5rem;
                            margin-bottom: 20px;
                            padding-bottom: 15px;
                            border-bottom: 2px solid #ffcc00;
                        }

                        .summary-content p {
                            color: #4b5563;
                            line-height: 1.9;
                            margin-bottom: 18px;
                            font-size: 1.05rem;
                        }

                        .summary-content strong {
                            color: #1e3a8a;
                            font-weight: 700;
                        }

                        /* Quality Assurance */
                        .quality-content p {
                            color: #4b5563;
                            line-height: 1.9;
                            margin-bottom: 25px;
                            font-size: 1.05rem;
                        }

                        .accreditation-detail-box {
                            background: white;
                            padding: 25px;
                            border-radius: 15px;
                            margin-bottom: 25px;
                            border-left: 5px solid #3b82f6;
                            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                        }

                        .accreditation-detail-box h5 {
                            color: #1e3a8a;
                            font-weight: 700;
                            font-size: 1.3rem;
                            margin-bottom: 15px;
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        }

                        .accreditation-detail-box h5 i {
                            color: #10b981;
                            font-size: 1.2rem;
                        }

                        .accreditation-detail-box p {
                            color: #4b5563;
                            line-height: 1.8;
                            margin-bottom: 12px;
                        }

                        .verification-link {
                            background: #eff6ff;
                            padding: 12px 18px;
                            border-radius: 10px;
                            margin-top: 15px;
                            border-left: 4px solid #3b82f6;
                        }

                        .verification-link i {
                            color: #3b82f6;
                            margin-right: 8px;
                        }

                        .verification-link a {
                            color: #1e3a8a;
                            font-weight: 600;
                            text-decoration: none;
                        }

                        .verification-link a:hover {
                            text-decoration: underline;
                        }

                        /* Credibility */
                        .credibility-content p {
                            color: #4b5563;
                            line-height: 1.9;
                            margin-bottom: 20px;
                            font-size: 1.05rem;
                        }

                        .commitment-list {
                            list-style: none;
                            padding: 0;
                            margin: 0;
                        }

                        .commitment-list li {
                            padding: 15px 20px;
                            margin-bottom: 12px;
                            background: white;
                            border-radius: 12px;
                            border-left: 4px solid #10b981;
                            color: #4b5563;
                            font-size: 1.05rem;
                            line-height: 1.7;
                            display: flex;
                            align-items: flex-start;
                            gap: 12px;
                            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
                            transition: all 0.3s ease;
                        }

                        .commitment-list li:hover {
                            transform: translateX(5px);
                            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.15);
                        }

                        .commitment-list li i {
                            color: #10b981;
                            font-size: 1.2rem;
                            margin-top: 3px;
                            flex-shrink: 0;
                        }

                        /* Commitment */
                        .commitment-content p {
                            color: #4b5563;
                            line-height: 1.9;
                            font-size: 1.1rem;
                            text-align: center;
                            font-style: italic;
                            padding: 20px;
                            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                            border-radius: 15px;
                            border-left: 5px solid #ffcc00;
                        }

                        /* Responsive */
                        @media (max-width: 768px) {
                            .legal-registration-card,
                            .accreditations-list-card,
                            .summary-card,
                            .quality-assurance-card,
                            .credibility-card,
                            .commitment-card {
                                padding: 25px 20px;
                            }

                            .card-header-section {
                                flex-direction: column;
                                align-items: flex-start;
                                gap: 10px;
                            }

                            .card-header-section h3 {
                                font-size: 1.5rem;
                            }

                            .info-row {
                                flex-direction: column;
                                gap: 8px;
                            }

                            .info-label {
                                min-width: auto;
                            }

                            .accreditation-item-list {
                                flex-direction: column;
                                gap: 15px;
                            }

                            .accreditation-number {
                                width: 40px;
                                height: 40px;
                                font-size: 1.2rem;
                            }

                            .accreditation-details h4 {
                                font-size: 1.2rem;
                            }
                        }

                        .accreditations-gallery-section {
                            margin-top: 60px;
                            padding-top: 50px;
                        }

                        .accreditation-item {
                            position: relative;
                            margin-bottom: 20px;
                        }

                        .accreditation-image-wrapper {
                            position: relative;
                            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                            border-radius: 20px;
                            padding: 30px;
                            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                            overflow: hidden;
                            border: 3px solid transparent;
                            height: 100%;
                            min-height: 300px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                        }

                        .accreditation-image-wrapper::before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: linear-gradient(135deg, rgba(30, 58, 138, 0.05) 0%, rgba(255, 204, 0, 0.05) 100%);
                            opacity: 0;
                            transition: opacity 0.3s ease;
                        }

                        .accreditation-image-wrapper:hover::before {
                            opacity: 1;
                        }

                        .accreditation-image-wrapper:hover {
                            transform: translateY(-10px) scale(1.02);
                            box-shadow: 0 20px 40px rgba(30, 58, 138, 0.2);
                            border-color: #ffcc00;
                        }

                        .accreditation-image {
                            max-width: 100%;
                            max-height: 280px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            border-radius: 12px;
                            transition: transform 0.4s ease;
                            cursor: pointer;
                            position: relative;
                            z-index: 2;
                        }

                        .accreditation-image-wrapper:hover .accreditation-image {
                            transform: scale(1.1);
                        }

                        .accreditation-overlay {
                            position: absolute;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: rgba(30, 58, 138, 0.9);
                            color: white;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            opacity: 0;
                            transition: opacity 0.3s ease;
                            border-radius: 20px;
                            z-index: 3;
                        }

                        .accreditation-image-wrapper:hover .accreditation-overlay {
                            opacity: 1;
                        }

                        .accreditation-overlay i {
                            font-size: 2.5rem;
                            margin-bottom: 10px;
                            color: #ffcc00;
                        }

                        .accreditation-overlay span {
                            font-size: 1.1rem;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                        }

                        /* Image Modal */
                        .image-modal {
                            display: none;
                            position: fixed;
                            z-index: 999999;
                            left: 0;
                            top: 0;
                            width: 100%;
                            height: 100%;
                            background-color: rgba(0, 0, 0, 0.95);
                            backdrop-filter: blur(10px);
                            animation: fadeIn 0.3s ease;
                        }

                        .image-modal.show {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        @keyframes fadeIn {
                            from { opacity: 0; }
                            to { opacity: 1; }
                        }

                        .modal-content {
                            max-width: 90%;
                            max-height: 90vh;
                            object-fit: contain;
                            border-radius: 15px;
                            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
                            animation: zoomIn 0.3s ease;
                        }

                        @keyframes zoomIn {
                            from { transform: scale(0.8); opacity: 0; }
                            to { transform: scale(1); opacity: 1; }
                        }

                        .modal-close {
                            position: absolute;
                            top: 30px;
                            right: 40px;
                            color: #fff;
                            font-size: 3rem;
                            font-weight: bold;
                            cursor: pointer;
                            z-index: 1000000;
                            transition: all 0.3s ease;
                            width: 50px;
                            height: 50px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: rgba(255, 255, 255, 0.1);
                            border-radius: 50%;
                            backdrop-filter: blur(10px);
                        }

                        .modal-close:hover {
                            background: rgba(255, 255, 255, 0.2);
                            transform: rotate(90deg);
                        }

                        .modal-caption {
                            position: absolute;
                            bottom: 30px;
                            left: 50%;
                            transform: translateX(-50%);
                            color: white;
                            font-size: 1.3rem;
                            font-weight: 600;
                            text-align: center;
                            background: rgba(0, 0, 0, 0.5);
                            padding: 15px 30px;
                            border-radius: 50px;
                            backdrop-filter: blur(10px);
                        }

                        /* Responsive */
                        @media (max-width: 991px) {
                            .accreditations-gallery-section h2 {
                                font-size: 2.2rem;
                            }
                        }

                        @media (max-width: 768px) {
                            .accreditation-image-wrapper {
                                min-height: 250px;
                                padding: 25px;
                            }

                            .accreditation-image {
                                max-height: 220px;
                            }

                            .accreditations-gallery-section h2 {
                                font-size: 1.8rem;
                            }

                            .accreditations-gallery-section p {
                                font-size: 1rem;
                            }

                            .modal-close {
                                top: 15px;
                                right: 20px;
                                font-size: 2rem;
                                width: 40px;
                                height: 40px;
                            }

                            .modal-caption {
                                bottom: 15px;
                                font-size: 1rem;
                                padding: 10px 20px;
                            }
                        }
                    </style>

                    <script>
                        function openImageModal(src, caption) {
                            console.log('Opening modal with:', src, caption);
                            const modal = document.getElementById('imageModal');
                            const modalImg = document.getElementById('modalImage');
                            const captionText = document.getElementById('modalCaption');
                            
                            if (modal && modalImg && captionText) {
                                modal.classList.add('show');
                                modalImg.src = src;
                                captionText.textContent = caption;
                                document.body.style.overflow = 'hidden';
                            } else {
                                console.error('Modal elements not found');
                            }
                        }

                        function closeImageModal() {
                            const modal = document.getElementById('imageModal');
                            if (modal) {
                                modal.classList.remove('show');
                                document.body.style.overflow = 'auto';
                            }
                        }

                        // Close modal on ESC key
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') {
                                closeImageModal();
                            }
                        });

                        // Close modal when clicking outside the image
                        document.addEventListener('DOMContentLoaded', function() {
                            const modal = document.getElementById('imageModal');
                            if (modal) {
                                modal.addEventListener('click', function(e) {
                                    if (e.target === modal || e.target.classList.contains('modal-close')) {
                                        closeImageModal();
                                    }
                                });
                            }
                        });
                    </script>
                    @endif
                    @endif

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
