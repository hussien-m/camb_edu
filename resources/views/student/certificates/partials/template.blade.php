@php
    $centerName = setting('site_name', config('app.name'));
    $centerTagline = setting('certificate_tagline', setting('site_title', 'Authorized Training Center'));
    $centerLogo = setting('header-footer-logo') ?: setting('site_logo');
    $forPdf = isset($forPdf) && $forPdf;
    $logoUrl = asset('certificates/logocert.png');
    $ukAddress = setting('contact_address_uk', '86-90 Paul Street, London, England, EC2A 4NE');
    $ukPhone = setting('contact_phone', '+44 7848 195975');
    $ukEmail = setting('contact_email', 'info@cambridgecollege.com');
@endphp

<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Libre+Baskerville:wght@400;700&family=Great+Vibes&display=swap" rel="stylesheet">

<div class="cert-outer {{ $forPdf ? 'cert-pdf' : '' }}">
    <div class="certificate" id="certificate-to-capture">
        <img src="{{ $logoUrl }}" class="watermark">

        <div class="content">
            {{-- HEADER --}}
            <div class="header">
                <img src="{{ $logoUrl }}" class="logo">
                <h1 class="institution-name">{{ strtoupper($centerName) }}</h1>
                <p class="location">{{ strtoupper($centerTagline) }}</p>
            </div>

            {{-- BODY --}}
            <div class="body">
                <h2 class="cert-title">CERTIFICATE OF COMPLETION</h2>
                <p class="declaration">This is to certify that</p>

                <div class="student-name">
                    {{ $certificate->student->full_name ?? $certificate->student->name }}
                </div>

                <div class="course-section">
                    <p class="course-info">has successfully completed the prescribed course of study in</p>
                    <div class="course-name">{{ $certificate->display_course_title }}</div>
                </div>
            </div>

            {{-- FOOTER: رسمي واحترافي --}}
            <div class="footer-wrap">
                <div class="footer-inner">
                    <div class="footer-date-block">
                        <span class="footer-date-label">Date of Issue</span>
                        <span class="footer-date-line"></span>
                        <span class="footer-date-value">{{ $certificate->issue_date->format('d F Y') }}</span>
                    </div>
                    <div class="footer-divider"></div>
                    <div class="footer-contact-block">
                        <div class="footer-contact-title">{{ $centerName }}</div>
                        <div class="footer-contact-lines">
                            <span>{{ $ukAddress }}</span>
                            <span class="footer-sep"> · </span>
                            <span>{{ $ukPhone }}</span>
                            <span class="footer-sep"> · </span>
                            <span>{{ $ukEmail }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="serial-number">
            <span class="serial-label">Certificate No.</span> {{ $certificate->certificate_number }}
        </div>
    </div>
</div>

<style>
/* إعدادات الصفحة للطباعة */
@page {
    size: A4 landscape; /* تغيير الوضع إلى عرضي ليناسب شكل الشهادة */
    margin: 0;
}

.cert-outer {
    background: #f0f0f0;
    padding: 20px;
    display: flex;
    justify-content: center;
    font-family: 'Libre Baskerville', serif;
}

.cert-outer.cert-pdf {
    background: #fff;
    padding: 0;
}

.certificate {
    width: 297mm; /* قياس A4 عرضي */
    height: 210mm;
    background: #fff;
    border: 15px solid #001f3f;
    position: relative;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
}

/* الإطار الذهبي الداخلي */
.certificate::after {
    content: '';
    position: absolute;
    top: 10px; left: 10px; right: 10px; bottom: 10px;
    border: 2px solid #c5a059;
    pointer-events: none;
}

.watermark {
    position: absolute;
    top: 55%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 400px;
    opacity: 0.05;
    z-index: 0;
}

.content {
    position: relative;
    z-index: 2;
    padding: 40px 60px 50px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
}

/* HEADER */
.header .logo { width: 90px; margin-bottom: 10px; }
.institution-name {
    font-family: 'Cinzel', serif;
    font-size: 28px;
    color: #8b0000;
    margin: 0;
}
.location {
    font-family: 'Cinzel', serif;
    font-size: 14px;
    letter-spacing: 2px;
    color: #001f3f;
}

/* BODY */
.cert-title {
    font-family: 'Cinzel', serif;
    font-size: 40px;
    color: #c5a059;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
    margin: 15px auto;
    width: 80%;
}

.student-name {
    font-family: 'Great Vibes', cursive;
    font-size: 65px;
    color: #001f3f;
    margin: 10px 0;
}

.course-name {
    font-size: 26px;
    color: #8b0000;
    font-weight: bold;
    margin-top: 5px;
}

/* FOOTER — رسمي واحترافي */
.footer-wrap {
    margin-top: 24px;
    padding-top: 16px;
    border-top: 2px solid #c5a059;
    width: 100%;
}

.footer-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 28px;
    flex-wrap: wrap;
    max-width: 720px;
    margin: 0 auto;
}

.footer-date-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 140px;
}
.footer-date-label {
    font-size: 9px;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 6px;
}
.footer-date-line {
    display: block;
    width: 100px;
    height: 0;
    border-top: 1.5px solid #001f3f;
    margin-bottom: 6px;
}
.footer-date-value {
    font-size: 12px;
    font-weight: 700;
    color: #001f3f;
    font-variant-numeric: tabular-nums;
}

.footer-divider {
    width: 1px;
    height: 36px;
    background: linear-gradient(to bottom, transparent, #c5a059, transparent);
    flex-shrink: 0;
}

.footer-contact-block {
    text-align: center;
    min-width: 0;
}
.footer-contact-title {
    font-family: 'Cinzel', serif;
    font-size: 11px;
    font-weight: 700;
    color: #8b0000;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.footer-contact-lines {
    font-size: 10px;
    color: #333;
    line-height: 1.4;
}
.footer-contact-lines .footer-sep {
    color: #999;
    margin: 0 2px;
}

/* السيريال في الزاوية اليمنى السفلى — الموضع الرسمي المعتاد للشهادات */
.serial-number {
    position: absolute;
    bottom: 16px;
    right: 24px;
    text-align: right;
    font-size: 11px;
    color: #444;
    font-family: 'Libre Baskerville', serif;
    letter-spacing: 0.3px;
}
.serial-number .serial-label {
    font-weight: 600;
    color: #555;
    margin-right: 4px;
}
</style>
