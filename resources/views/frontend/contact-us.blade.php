@extends('frontend.layouts.app')

@section('title', $page->title)

@push('styles')
    <style>
        .contact-page-hero {
            padding: 70px 0 40px;
            background: linear-gradient(135deg, rgba(14,107,80,0.08), rgba(11,27,58,0.08));
        }

        .contact-page-hero h1 {
            font-size: 2.6rem;
            font-weight: 800;
            color: #0b1b3a;
        }

        .contact-page-hero p {
            color: rgba(11,27,58,0.7);
            font-size: 1.05rem;
        }

        .contact-page-section {
            padding: 50px 0 80px;
        }

        .contact-info-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border: 1px solid rgba(11,27,58,0.08);
        }

        .contact-info-card h3 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0b1b3a;
            margin-bottom: 16px;
        }

        .contact-form-card {
            background: #ffffff;
            border-radius: 22px;
            border: none;
            box-shadow: 0 14px 40px rgba(0,0,0,0.08);
        }

        .contact-form-card .card-body {
            padding: 32px;
        }

        @media (max-width: 767px) {
            .contact-page-hero h1 {
                font-size: 2rem;
            }

            .contact-form-card .card-body {
                padding: 22px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="contact-page-hero">
        <div class="container text-center">
            <h1>{{ $page->title }}</h1>
            <p>{{ $page->excerpt ?? 'We are here to support you. Reach out anytime.' }}</p>
        </div>
    </section>

    <section class="contact-page-section">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <div class="contact-info-card">
                        <h3>Branches & Contacts</h3>

                        @php
                            $ukPhone = setting('contact_phone');
                            $ukWhatsapp = setting('contact_whatsapp');
                            $canadaPhone = setting('contact_phone_ca') ?: setting('contact_phone_2');
                            $canadaWhatsapp = setting('contact_whatsapp_ca');
                        @endphp

                        @if(setting('contact_address_uk') || $ukPhone || $ukWhatsapp)
                            <div class="contact-branch-section mb-3">
                                <div class="contact-branch-header">UK Branch</div>
                                <div class="contact-branch-items">
                                    @if(setting('contact_address_uk'))
                                        <div class="contact-branch-row">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div class="contact-branch-text">
                                                <span class="contact-branch-label">Address</span>
                                                <span class="contact-branch-value">{{ setting('contact_address_uk') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($ukPhone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $ukPhone) }}" class="contact-branch-row contact-branch-link">
                                            <i class="fas fa-phone"></i>
                                            <div class="contact-branch-text">
                                                <span class="contact-branch-label">Phone</span>
                                                <span class="contact-branch-value">{{ $ukPhone }}</span>
                                            </div>
                                            <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                        </a>
                                    @endif
                                    @if($ukWhatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ukWhatsapp) }}" target="_blank" class="contact-branch-row contact-branch-link">
                                            <i class="fab fa-whatsapp"></i>
                                            <div class="contact-branch-text">
                                                <span class="contact-branch-label">WhatsApp</span>
                                                <span class="contact-branch-value">{{ $ukWhatsapp }}</span>
                                            </div>
                                            <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if(setting('contact_address') || $canadaPhone || $canadaWhatsapp)
                            <div class="contact-branch-section mb-3">
                                <div class="contact-branch-header">Canada Branch</div>
                                <div class="contact-branch-items">
                                    @if(setting('contact_address'))
                                        <div class="contact-branch-row">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div class="contact-branch-text">
                                                <span class="contact-branch-label">Address</span>
                                                <span class="contact-branch-value">{{ setting('contact_address') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($canadaPhone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $canadaPhone) }}" class="contact-branch-row contact-branch-link">
                                            <i class="fas fa-phone"></i>
                                            <div class="contact-branch-text">
                                                <span class="contact-branch-label">Phone</span>
                                                <span class="contact-branch-value">{{ $canadaPhone }}</span>
                                            </div>
                                            <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                        </a>
                                    @endif
                                    @if($canadaWhatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $canadaWhatsapp) }}" target="_blank" class="contact-branch-row contact-branch-link">
                                            <i class="fab fa-whatsapp"></i>
                                            <div class="contact-branch-text">
                                                <span class="contact-branch-label">WhatsApp</span>
                                                <span class="contact-branch-value">{{ $canadaWhatsapp }}</span>
                                            </div>
                                            <i class="fas fa-external-link-alt contact-branch-arrow"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="contact-info-item">
                            <i class="fas fa-clock"></i>
                            <span>Saturday - Thursday (9AM - 5PM)</span>
                        </div>

                        <a href="mailto:{{ setting('contact_email', 'info@cambridgecollege.ly') }}" class="contact-btn contact-btn-email mt-3">
                            <div class="contact-btn-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-btn-content">
                                <span class="contact-btn-label">Email (All Branches)</span>
                                <span class="contact-btn-value">{{ setting('contact_email', 'info@cambridgecollege.ly') }}</span>
                            </div>
                            <i class="fas fa-external-link-alt contact-btn-arrow"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card contact-form-card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success" style="border-radius: 12px; border-left: 5px solid #10b981;">
                                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                </div>
                            @endif

                            <div id="contactAlert" style="display: none;" class="alert alert-dismissible fade show" role="alert">
                                <span id="contactAlertMessage"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>

                            <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
                                @csrf

                                <input type="text" name="website_url" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
                                <input type="text" name="phone_number_confirm" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}" required
                                               style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}" required
                                               style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Phone Number</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}"
                                               style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                               value="{{ old('subject') }}" required
                                               style="height: 55px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                        @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                                        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                                                  required style="border-radius: 12px; border: 2px solid #e5e7eb;">{{ old('message') }}</textarea>
                                        @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-12 text-center mt-4">
                                        <button type="submit" class="btn btn-view-all px-5" id="submitBtn">
                                            <i class="fas fa-paper-plane me-2"></i> Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @vite('resources/js/frontend-home.js')
@endpush
