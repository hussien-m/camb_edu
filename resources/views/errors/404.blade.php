<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | {{ \App\Models\Setting::get('site_title', 'Cambridge International College') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #06b6d4 100%);
            min-height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float-particle 20s infinite;
        }

        @keyframes float-particle {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        .container {
            text-align: center;
            padding: 40px 20px;
            max-width: 800px;
            width: 100%;
            z-index: 1;
            position: relative;
        }

        .error-content {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            margin-bottom: 40px;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .logo h1 {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .logo p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }

        .error-number {
            font-size: 180px;
            font-weight: 900;
            color: white;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: glitch 3s infinite;
            position: relative;
        }

        @keyframes glitch {
            0%, 100% {
                transform: translate(0);
            }
            20% {
                transform: translate(-2px, 2px);
            }
            40% {
                transform: translate(-2px, -2px);
            }
            60% {
                transform: translate(2px, 2px);
            }
            80% {
                transform: translate(2px, -2px);
            }
        }

        h2 {
            font-size: 42px;
            color: white;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }

        .btn {
            padding: 16px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        .btn-primary {
            background: white;
            color: #1e3a8a;
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(255, 255, 255, 0.5);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #1e3a8a;
        }

        .quick-links {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .quick-link {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-link:hover {
            color: white;
            transform: translateY(-2px);
        }

        .icon {
            font-size: 100px;
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.2));
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @media (max-width: 768px) {
            .error-number {
                font-size: 120px;
            }

            h2 {
                font-size: 32px;
            }

            p {
                font-size: 16px;
            }

            .logo h1 {
                font-size: 24px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .quick-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="particle" style="width: 20px; height: 20px; left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="width: 30px; height: 30px; left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="width: 15px; height: 15px; left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="width: 25px; height: 25px; left: 40%; animation-delay: 1s;"></div>
        <div class="particle" style="width: 18px; height: 18px; left: 50%; animation-delay: 3s;"></div>
        <div class="particle" style="width: 22px; height: 22px; left: 60%; animation-delay: 5s;"></div>
        <div class="particle" style="width: 28px; height: 28px; left: 70%; animation-delay: 2.5s;"></div>
        <div class="particle" style="width: 16px; height: 16px; left: 80%; animation-delay: 4.5s;"></div>
        <div class="particle" style="width: 24px; height: 24px; left: 90%; animation-delay: 1.5s;"></div>
    </div>

    <div class="container">
        <div class="error-content">
            <div class="logo">
                <h1>{{ \App\Models\Setting::get('site_title', 'Cambridge International College') }}</h1>
            </div>

            <div class="icon">🔍</div>

            <div class="error-number">404</div>

            <h2>Page Not Found</h2>

            <p>
                The page you are looking for might have been removed, had its name changed,
                or is temporarily unavailable. Please check the URL or return to the homepage.
            </p>

            <div class="buttons">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    🏠 Go to Homepage
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    ← Go Back
                </a>
            </div>

            <div class="quick-links">
                <a href="{{ route('student.login') }}" class="quick-link">
                    <span>🎓</span> Student Login
                </a>
                <a href="{{ route('student.register') }}" class="quick-link">
                    <span>📝</span> Register
                </a>
                <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'info@cambridge-college.uk') }}" class="quick-link">
                    <span>📧</span> Contact Us
                </a>
            </div>
        </div>
    </div>
</body>
</html>
