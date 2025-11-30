<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 50px; text-align: center;">
                            @if($siteSettings['site_logo'])
                                <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}" alt="{{ $siteSettings['site_title'] }}" style="max-height: 60px; margin-bottom: 20px;">
                            @endif
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                Verify Your Email Address
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 16px;">
                                You're almost there!
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 50px;">
                            <!-- Greeting -->
                            <p style="font-size: 18px; color: #2d3748; margin: 0 0 25px;">
                                Hello <strong style="color: #667eea;">{{ $student->first_name }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #4a5568; line-height: 1.7; margin: 0 0 25px;">
                                Thank you for registering at <strong>{{ $siteSettings['site_title'] }}</strong>!
                                To complete your registration and start your learning journey, please verify your email address.
                            </p>

                            <!-- Verification Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 35px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verificationUrl }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 18px 50px; border-radius: 50px; font-size: 16px; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);">
                                            ✓ Verify My Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <div style="background-color: #f7fafc; border-radius: 12px; padding: 20px; margin: 30px 0;">
                                <p style="font-size: 14px; color: #718096; margin: 0 0 10px;">
                                    If the button doesn't work, copy and paste this link into your browser:
                                </p>
                                <p style="font-size: 13px; color: #667eea; word-break: break-all; margin: 0; background: #fff; padding: 12px; border-radius: 8px; border: 1px dashed #e2e8f0;">
                                    {{ $verificationUrl }}
                                </p>
                            </div>

                            <!-- Expiry Notice -->
                            <div style="display: flex; align-items: center; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; padding: 15px 20px; margin: 25px 0;">
                                <span style="font-size: 24px; margin-right: 15px;">⏰</span>
                                <p style="font-size: 14px; color: #92400e; margin: 0;">
                                    <strong>Important:</strong> This verification link will expire in <strong>24 hours</strong>.
                                </p>
                            </div>

                            <!-- Security Notice -->
                            <p style="font-size: 14px; color: #718096; line-height: 1.6; margin: 25px 0 0; padding-top: 25px; border-top: 1px solid #e2e8f0;">
                                🔒 If you didn't create an account with us, please ignore this email or contact our support team.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #2d3748; padding: 35px 50px; text-align: center;">
                            <!-- Social Links -->
                            <table role="presentation" style="margin: 0 auto 20px;">
                                <tr>
                                    @if($siteSettings['social_facebook'])
                                    <td style="padding: 0 8px;">
                                        <a href="{{ $siteSettings['social_facebook'] }}" style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255,255,255,0.1); border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                            <img src="https://cdn-icons-png.flaticon.com/24/733/733547.png" alt="Facebook" style="width: 18px; height: 18px; vertical-align: middle;">
                                        </a>
                                    </td>
                                    @endif
                                    @if($siteSettings['social_twitter'])
                                    <td style="padding: 0 8px;">
                                        <a href="{{ $siteSettings['social_twitter'] }}" style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255,255,255,0.1); border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                            <img src="https://cdn-icons-png.flaticon.com/24/733/733579.png" alt="Twitter" style="width: 18px; height: 18px; vertical-align: middle;">
                                        </a>
                                    </td>
                                    @endif
                                    @if($siteSettings['social_instagram'])
                                    <td style="padding: 0 8px;">
                                        <a href="{{ $siteSettings['social_instagram'] }}" style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255,255,255,0.1); border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                            <img src="https://cdn-icons-png.flaticon.com/24/2111/2111463.png" alt="Instagram" style="width: 18px; height: 18px; vertical-align: middle;">
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            </table>

                            <p style="color: #a0aec0; font-size: 14px; margin: 0 0 10px;">
                                {{ $siteSettings['site_title'] }}
                            </p>
                            @if($siteSettings['contact_email'])
                            <p style="color: #718096; font-size: 13px; margin: 0 0 5px;">
                                📧 {{ $siteSettings['contact_email'] }}
                            </p>
                            @endif
                            @if($siteSettings['contact_phone'])
                            <p style="color: #718096; font-size: 13px; margin: 0;">
                                📞 {{ $siteSettings['contact_phone'] }}
                            </p>
                            @endif

                            <p style="color: #4a5568; font-size: 12px; margin: 20px 0 0; padding-top: 20px; border-top: 1px solid #4a5568;">
                                © {{ date('Y') }} {{ $siteSettings['site_title'] }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
