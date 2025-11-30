<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden;">

                    <!-- Header with Celebration -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 50px; text-align: center;">
                            @if($siteSettings['site_logo'])
                                <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}" alt="{{ $siteSettings['site_title'] }}" style="max-height: 60px; margin-bottom: 25px;">
                            @endif

                            <!-- Celebration Emoji -->
                            <div style="font-size: 60px; margin-bottom: 15px;">🎉</div>

                            <h1 style="color: #ffffff; margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">
                                Welcome Aboard!
                            </h1>
                            <p style="color: rgba(255,255,255,0.95); margin: 15px 0 0; font-size: 18px;">
                                Your account has been verified successfully
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 50px;">
                            <!-- Personal Greeting -->
                            <p style="font-size: 20px; color: #2d3748; margin: 0 0 25px;">
                                Dear <strong style="color: #10b981;">{{ $student->first_name }} {{ $student->last_name }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #4a5568; line-height: 1.8; margin: 0 0 30px;">
                                Congratulations! 🎊 Your email has been verified and your account at
                                <strong>{{ $siteSettings['site_title'] }}</strong> is now fully activated.
                                You're now part of our learning community!
                            </p>

                            <!-- What's Next Section -->
                            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 16px; padding: 30px; margin: 30px 0;">
                                <h2 style="color: #166534; font-size: 20px; margin: 0 0 20px; display: flex; align-items: center;">
                                    🚀 What You Can Do Now
                                </h2>

                                <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 12px 0; border-bottom: 1px solid rgba(22, 101, 52, 0.1);">
                                            <table role="presentation">
                                                <tr>
                                                    <td style="width: 50px; text-align: center; font-size: 24px;">📚</td>
                                                    <td>
                                                        <strong style="color: #166534;">Browse Courses</strong>
                                                        <p style="color: #4a5568; margin: 5px 0 0; font-size: 14px;">Explore our wide range of professional courses</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 0; border-bottom: 1px solid rgba(22, 101, 52, 0.1);">
                                            <table role="presentation">
                                                <tr>
                                                    <td style="width: 50px; text-align: center; font-size: 24px;">✍️</td>
                                                    <td>
                                                        <strong style="color: #166534;">Enroll in Courses</strong>
                                                        <p style="color: #4a5568; margin: 5px 0 0; font-size: 14px;">Start your learning journey today</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 0; border-bottom: 1px solid rgba(22, 101, 52, 0.1);">
                                            <table role="presentation">
                                                <tr>
                                                    <td style="width: 50px; text-align: center; font-size: 24px;">📝</td>
                                                    <td>
                                                        <strong style="color: #166534;">Take Exams</strong>
                                                        <p style="color: #4a5568; margin: 5px 0 0; font-size: 14px;">Test your knowledge and earn certificates</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 0;">
                                            <table role="presentation">
                                                <tr>
                                                    <td style="width: 50px; text-align: center; font-size: 24px;">🏆</td>
                                                    <td>
                                                        <strong style="color: #166534;">Earn Certificates</strong>
                                                        <p style="color: #4a5568; margin: 5px 0 0; font-size: 14px;">Get recognized for your achievements</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 35px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('student.dashboard') }}" style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; text-decoration: none; padding: 18px 50px; border-radius: 50px; font-size: 16px; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);">
                                            🎓 Go to My Dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Account Info Box -->
                            <div style="background-color: #f8fafc; border-radius: 12px; padding: 25px; margin: 30px 0; border-left: 4px solid #10b981;">
                                <h3 style="color: #2d3748; font-size: 16px; margin: 0 0 15px;">📋 Your Account Details</h3>
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="padding: 8px 0; color: #718096; font-size: 14px; width: 100px;">Name:</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 600;">{{ $student->first_name }} {{ $student->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #718096; font-size: 14px;">Email:</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 600;">{{ $student->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #718096; font-size: 14px;">Member Since:</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 600;">{{ $student->created_at->format('F d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Support Section -->
                            <p style="font-size: 15px; color: #4a5568; line-height: 1.7; margin: 30px 0 0; padding-top: 25px; border-top: 1px solid #e2e8f0;">
                                💬 <strong>Need Help?</strong> Our support team is always here to assist you.
                                Feel free to reach out if you have any questions!
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
                                        <a href="{{ $siteSettings['social_facebook'] }}" style="display: inline-block; width: 40px; height: 40px; background-color: rgba(255,255,255,0.1); border-radius: 50%; text-align: center; line-height: 40px; text-decoration: none;">
                                            <img src="https://cdn-icons-png.flaticon.com/24/733/733547.png" alt="Facebook" style="width: 20px; height: 20px; vertical-align: middle;">
                                        </a>
                                    </td>
                                    @endif
                                    @if($siteSettings['social_twitter'])
                                    <td style="padding: 0 8px;">
                                        <a href="{{ $siteSettings['social_twitter'] }}" style="display: inline-block; width: 40px; height: 40px; background-color: rgba(255,255,255,0.1); border-radius: 50%; text-align: center; line-height: 40px; text-decoration: none;">
                                            <img src="https://cdn-icons-png.flaticon.com/24/733/733579.png" alt="Twitter" style="width: 20px; height: 20px; vertical-align: middle;">
                                        </a>
                                    </td>
                                    @endif
                                    @if($siteSettings['social_instagram'])
                                    <td style="padding: 0 8px;">
                                        <a href="{{ $siteSettings['social_instagram'] }}" style="display: inline-block; width: 40px; height: 40px; background-color: rgba(255,255,255,0.1); border-radius: 50%; text-align: center; line-height: 40px; text-decoration: none;">
                                            <img src="https://cdn-icons-png.flaticon.com/24/2111/2111463.png" alt="Instagram" style="width: 20px; height: 20px; vertical-align: middle;">
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            </table>

                            <p style="color: #a0aec0; font-size: 15px; margin: 0 0 10px; font-weight: 600;">
                                {{ $siteSettings['site_title'] }}
                            </p>
                            @if($siteSettings['site_description'])
                            <p style="color: #718096; font-size: 13px; margin: 0 0 15px;">
                                {{ $siteSettings['site_description'] }}
                            </p>
                            @endif

                            <table role="presentation" style="margin: 15px auto;">
                                <tr>
                                    @if($siteSettings['contact_email'])
                                    <td style="padding: 0 15px;">
                                        <span style="color: #718096; font-size: 13px;">📧 {{ $siteSettings['contact_email'] }}</span>
                                    </td>
                                    @endif
                                    @if($siteSettings['contact_phone'])
                                    <td style="padding: 0 15px;">
                                        <span style="color: #718096; font-size: 13px;">📞 {{ $siteSettings['contact_phone'] }}</span>
                                    </td>
                                    @endif
                                </tr>
                            </table>

                            <p style="color: #4a5568; font-size: 12px; margin: 25px 0 0; padding-top: 20px; border-top: 1px solid #4a5568;">
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
