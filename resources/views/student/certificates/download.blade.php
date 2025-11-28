<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #8B4513 0%, #D4AF37 100%);
            padding: 30px 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate-container {
            background: linear-gradient(to bottom, #fdfcf9 0%, #ffffff 100%);
            max-width: 1100px;
            width: 100%;
            padding: 0;
            border-radius: 0;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: visible;
            border: 20px solid #8B4513;
            border-image: repeating-linear-gradient(
                45deg,
                #8B4513,
                #8B4513 10px,
                #D4AF37 10px,
                #D4AF37 20px
            ) 20;
        }

        /* Ornamental Corners */
        .corner-ornament {
            position: absolute;
            width: 120px;
            height: 120px;
            border: 4px solid #D4AF37;
            z-index: 2;
        }

        .corner-ornament::before,
        .corner-ornament::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            border: 3px solid #8B4513;
        }

        .corner-ornament.top-left {
            top: 20px;
            left: 20px;
            border-right: none;
            border-bottom: none;
        }

        .corner-ornament.top-left::before {
            top: -3px;
            left: -3px;
            border-right: none;
            border-bottom: none;
        }

        .corner-ornament.top-right {
            top: 20px;
            right: 20px;
            border-left: none;
            border-bottom: none;
        }

        .corner-ornament.top-right::before {
            top: -3px;
            right: -3px;
            border-left: none;
            border-bottom: none;
        }

        .corner-ornament.bottom-left {
            bottom: 20px;
            left: 20px;
            border-right: none;
            border-top: none;
        }

        .corner-ornament.bottom-left::before {
            bottom: -3px;
            left: -3px;
            border-right: none;
            border-top: none;
        }

        .corner-ornament.bottom-right {
            bottom: 20px;
            right: 20px;
            border-left: none;
            border-top: none;
        }

        .corner-ornament.bottom-right::before {
            bottom: -3px;
            right: -3px;
            border-left: none;
            border-top: none;
        }

        /* Inner Decorative Border */
        .inner-border {
            position: absolute;
            top: 35px;
            left: 35px;
            right: 35px;
            bottom: 35px;
            border: 2px solid #D4AF37;
            pointer-events: none;
            z-index: 1;
        }

        .inner-border::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 1px solid #8B4513;
        }

        .certificate-header {
            padding: 50px 60px 30px;
            text-align: center;
            position: relative;
            background: linear-gradient(to bottom, rgba(212, 175, 55, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            border-bottom: 3px double #D4AF37;
        }

        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 4px #D4AF37, 0 0 0 8px #8B4513;
            position: relative;
            z-index: 10;
        }

        .logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 42px;
            font-weight: 900;
            color: #8B4513;
            font-family: 'Playfair Display', serif;
        }

        .site-name {
            font-size: 28px;
            color: #8B4513;
            font-weight: 900;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-family: 'Playfair Display', serif;
        }

        .site-tagline {
            font-size: 13px;
            color: #6B5742;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 300;
        }

        .certificate-body {
            padding: 50px 80px 60px;
            position: relative;
        }

        /* Golden Seal/Badge */
        .seal {
            position: absolute;
            top: 40px;
            right: 60px;
            width: 90px;
            height: 90px;
            background: radial-gradient(circle, #FFD700 0%, #D4AF37 50%, #8B4513 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.6);
            border: 3px solid #8B4513;
            z-index: 10;
        }

        .seal::before {
            content: '⭐';
            font-size: 40px;
            color: #8B4513;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .certificate-title {
            font-size: 64px;
            color: #8B4513;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 8px;
            font-family: 'Playfair Display', serif;
            position: relative;
            text-shadow: 2px 2px 4px rgba(212, 175, 55, 0.3);
        }

        .certificate-subtitle {
            font-size: 18px;
            color: #D4AF37;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .certificate-text {
            font-size: 18px;
            color: #6B5742;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 300;
            font-style: italic;
        }

        .presented-text {
            font-size: 22px;
            color: #8B4513;
            text-align: center;
            margin: 30px 0 20px;
            font-weight: 400;
            letter-spacing: 1px;
        }

        .student-name {
            font-size: 56px;
            color: #8B4513;
            font-weight: 400;
            text-align: center;
            margin: 30px 0;
            padding: 25px 40px;
            position: relative;
            font-family: 'Great Vibes', cursive;
            line-height: 1.3;
        }

        .name-decoration {
            width: 500px;
            height: 2px;
            margin: 15px auto;
            background: linear-gradient(to right, transparent, #D4AF37 20%, #D4AF37 80%, transparent);
            position: relative;
        }

        .name-decoration::before,
        .name-decoration::after {
            content: '❖';
            position: absolute;
            top: -8px;
            color: #D4AF37;
            font-size: 16px;
        }

        .name-decoration::before {
            left: 0;
        }

        .name-decoration::after {
            right: 0;
        }

        .completion-text {
            font-size: 19px;
            color: #6B5742;
            text-align: center;
            margin: 35px 0 25px;
            font-weight: 300;
            line-height: 1.6;
        }

        .course-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: rgba(212, 175, 55, 0.08);
            border-left: 4px solid #D4AF37;
            border-right: 4px solid #D4AF37;
        }

        .course-name {
            font-size: 38px;
            color: #8B4513;
            font-weight: 700;
            line-height: 1.4;
            font-family: 'Playfair Display', serif;
            margin-bottom: 15px;
        }

        .achievement-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
            color: #8B4513;
            padding: 12px 35px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
            margin-top: 15px;
            letter-spacing: 1px;
        }

        .details-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
            margin: 50px 0 40px;
        }

        .detail-item {
            text-align: center;
            position: relative;
        }

        .detail-item::after {
            content: '';
            position: absolute;
            right: -25px;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 50px;
            background: #D4AF37;
        }

        .detail-item:last-child::after {
            display: none;
        }

        .detail-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .detail-value {
            font-size: 20px;
            color: #8B4513;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 50px;
            margin-top: 40px;
            border-top: 2px solid #D4AF37;
        }

        .footer-item {
            text-align: center;
            flex: 1;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            background: white;
            border: 3px solid #D4AF37;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 36px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .qr-label {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signature-line {
            width: 200px;
            height: 1px;
            background: #8B4513;
            margin: 0 auto 12px;
        }

        .signature-name {
            font-size: 18px;
            color: #8B4513;
            font-weight: 700;
            margin-bottom: 3px;
            font-family: 'Playfair Display', serif;
        }

        .signature-title {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .certificate-id {
            font-size: 11px;
            color: #999;
            letter-spacing: 1px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
            color: #8B4513;
            border: 2px solid #8B4513;
            padding: 15px 35px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            font-family: 'Montserrat', sans-serif;
        }

        .print-button:hover {
            background: linear-gradient(135deg, #FFD700 0%, #D4AF37 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                min-height: auto !important;
            }

            .certificate-container {
                box-shadow: none !important;
                max-width: 100% !important;
                width: 100% !important;
                border: 15px solid #8B4513 !important;
                border-image: repeating-linear-gradient(
                    45deg,
                    #8B4513,
                    #8B4513 10px,
                    #D4AF37 10px,
                    #D4AF37 20px
                ) 20 !important;
                margin: 0 !important;
                page-break-inside: avoid !important;
            }

            .print-button {
                display: none !important;
            }

            .certificate-header {
                padding: 35px 50px 25px !important;
            }

            .certificate-body {
                padding: 45px 60px 50px !important;
            }

            .logo {
                width: 90px !important;
                height: 90px !important;
            }

            .logo img {
                width: 65px !important;
                height: 65px !important;
            }

            .site-name {
                font-size: 26px !important;
            }

            .site-tagline {
                font-size: 12px !important;
            }

            .certificate-title {
                font-size: 56px !important;
                margin-bottom: 25px !important;
            }

            .certificate-subtitle {
                font-size: 16px !important;
                margin-bottom: 30px !important;
            }

            .student-name {
                font-size: 48px !important;
                margin: 25px 0 !important;
                padding: 20px 30px !important;
            }

            .course-name {
                font-size: 32px !important;
            }

            .course-section {
                padding: 25px !important;
                margin: 30px 0 !important;
            }

            .details-section {
                margin: 35px 0 30px !important;
                gap: 40px !important;
            }

            .certificate-footer {
                padding-top: 35px !important;
                margin-top: 30px !important;
            }

            .seal {
                width: 80px !important;
                height: 80px !important;
                top: 35px !important;
                right: 50px !important;
            }

            .seal::before {
                font-size: 32px !important;
            }

            .corner-ornament {
                width: 100px !important;
                height: 100px !important;
            }

            .corner-ornament::before,
            .corner-ornament::after {
                width: 25px !important;
                height: 25px !important;
            }

            @page {
                size: landscape;
                margin: 0.3cm;
            }
        }

        @media (max-width: 768px) {
            .certificate-body {
                padding: 40px 30px;
            }

            .certificate-title {
                font-size: 42px;
                letter-spacing: 4px;
            }

            .student-name {
                font-size: 38px;
                padding: 20px;
            }

            .course-name {
                font-size: 26px;
            }

            .details-section {
                flex-direction: column;
                gap: 25px;
            }

            .detail-item::after {
                display: none;
            }

            .certificate-footer {
                flex-direction: column;
                gap: 35px;
            }

            .seal {
                width: 70px;
                height: 70px;
                top: 30px;
                right: 30px;
            }

            .seal::before {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Print Certificate
    </button>

    <div class="certificate-container">
        <!-- Decorative Corners -->
        <div class="corner-ornament top-left"></div>
        <div class="corner-ornament top-right"></div>
        <div class="corner-ornament bottom-left"></div>
        <div class="corner-ornament bottom-right"></div>
        <div class="inner-border"></div>

        <!-- Header Section -->
        <div class="certificate-header">
            <div class="logo">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_title', 'Training Camp') }}">
                @else
                    <span class="logo-text">TC</span>
                @endif
            </div>
            <h1 class="site-name">{{ setting('site_title', 'Training Camp') }}</h1>
            <p class="site-tagline">Professional Training & Development Institute</p>
        </div>

        <!-- Main Certificate Body -->
        <div class="certificate-body">
            <!-- Golden Seal -->
            <div class="seal"></div>

            <h2 class="certificate-title">Certificate</h2>
            <div class="certificate-subtitle">Of Achievement</div>

            <p class="presented-text">This Certificate is Proudly Presented to</p>

            <div class="student-name">
                {{ $certificate->student->first_name }} {{ $certificate->student->last_name }}
            </div>
            <div class="name-decoration"></div>

            <p class="completion-text">
                For successfully completing and demonstrating exceptional proficiency<br>
                in the professional training course
            </p>

            <div class="course-section">
                <div class="course-name">"{{ $certificate->course->title }}"</div>
                <div class="achievement-badge">
                    <span>🏆</span> Outstanding Achievement
                </div>
            </div>

            <!-- Certificate Details -->
            <div class="details-section">
                <div class="detail-item">
                    <div class="detail-label">Date of Issue</div>
                    <div class="detail-value">{{ $certificate->issue_date->format('F d, Y') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Final Score</div>
                    <div class="detail-value">{{ number_format($certificate->examAttempt->percentage, 1) }}%</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Certificate ID</div>
                    <div class="detail-value" style="font-size: 16px;">{{ $certificate->certificate_number }}</div>
                </div>
            </div>

            <!-- Footer with Signatures -->
            <div class="certificate-footer">

                <div class="footer-item">
                    <div class="signature-line"></div>
                    <div class="signature-name">Director of Training</div>
                    <div class="signature-title">{{ setting('site_title', 'Training Camp') }}</div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
