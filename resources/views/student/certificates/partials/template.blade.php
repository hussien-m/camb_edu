@php
    $backgroundUrl = $backgroundImage ?? asset('certificates/certificate-template.png');
@endphp

<div class="certificate-wrapper">
    <div class="certificate-canvas">
        <div class="certificate-layer cert-line">
            Certifies that <span class="student-inline">{{ $certificate->student->full_name ?? $certificate->student->name }}</span>
            successfully awarded the Certificate of the
        </div>
        <div class="certificate-layer course-title">
            {{ $certificate->display_course_title }}
        </div>
        <div class="certificate-layer certificate-number">
            Registered Serial #{{ $certificate->certificate_number }}
        </div>
        <div class="certificate-layer issue-date">
            {{ $certificate->issue_date->format('d / m / Y') }}
        </div>
    </div>
</div>

<style>
@page {
    size: A4 portrait;
    margin: 0;
}

html, body {
    margin: 0;
    padding: 0;
    width: 210mm;
    height: 297mm;
}

.certificate-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    background: #fff;
}

.certificate-canvas {
    position: relative;
    width: 210mm;
    height: 297mm;
    background: url("{{ $backgroundUrl }}") no-repeat center center;
    background-size: 100% 100%;
    overflow: hidden;
}

.certificate-layer {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    color: #1b1b1b;
    font-family: "Times New Roman", "Georgia", serif;
}

.cert-line {
    top: 36.2%;
    font-size: 15px;
    font-weight: 600;
    max-width: 760px;
    line-height: 1.3;
}

.student-inline {
    font-weight: 700;
}

.course-title {
    top: 42.3%;
    font-size: 17px;
    font-weight: 700;
    color: #b8742c;
    max-width: 760px;
}

.certificate-number {
    top: 54.1%;
    font-size: 13px;
    font-weight: 700;
    color: #b31818;
}

.issue-date {
    top: 62.8%;
    left: 17.8%;
    transform: translateX(0);
    font-size: 12px;
    font-weight: 600;
    color: #1b1b1b;
}

@media print {
    .certificate-canvas {
        width: 100%;
        height: auto;
        aspect-ratio: 2550 / 3300;
    }
}

@media (max-width: 1200px) {
    .certificate-canvas {
        width: 95vw;
        height: calc(95vw * 1.414);
    }

    .cert-line { font-size: 2.0vw; }
    .course-title { font-size: 2.0vw; }
    .certificate-number { font-size: 1.5vw; }
    .issue-date { font-size: 1.3vw; }
}
</style>
