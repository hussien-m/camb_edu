<?php
// Enhanced email test script with detailed debugging
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "=== EMAIL CONFIGURATION TEST ===\n\n";
echo "Configuration:\n";
echo "  MAIL_MAILER: " . config('mail.default') . "\n";
echo "  MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "  MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "  MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "  MAIL_FROM: " . config('mail.from.address') . "\n";
echo "  MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "  MAIL_TIMEOUT: " . config('mail.mailers.smtp.timeout') . "\n\n";

// Test SMTP connection
echo "Testing SMTP connection...\n";
$host = config('mail.mailers.smtp.host');
$port = config('mail.mailers.smtp.port');

$connection = @fsockopen($host, $port, $errno, $errstr, 5);
if ($connection) {
    echo "✅ SMTP server is reachable ($host:$port)\n\n";
    fclose($connection);
} else {
    echo "❌ Cannot connect to SMTP server: $errstr ($errno)\n\n";
}

// Test sending email
echo "Attempting to send test email...\n";
$testEmail = 'hussien.mohammed1991@gmail.com'; // أدخل بريدك الشخصي هنا للاختبار

try {
    Mail::raw('This is a test email from Cambridge College. If you receive this, email is working correctly!', function($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('🧪 Test Email - ' . date('Y-m-d H:i:s'));
    });

    echo "✅ Email sent successfully!\n";
    echo "📧 Check inbox: $testEmail\n";
    echo "📧 Also check spam/junk folder\n\n";

    // Check logs
    echo "Check logs for details:\n";
    echo "  storage/logs/laravel.log\n";

} catch (Exception $e) {
    echo "❌ Failed to send email:\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "Full trace:\n";
    echo $e->getTraceAsString() . "\n";
}
