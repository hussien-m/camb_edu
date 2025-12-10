<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Mail\ProfessionalMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        return view('admin.settings.email');
    }

    /**
     * Save email settings
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer|between:1,65535',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'mail_username' => 'required|email',
            'mail_password' => 'nullable|string',
        ]);

        try {
            // Determine encryption based on port
            $encryption = 'tls';
            if ($validated['mail_port'] == 465) {
                $encryption = 'ssl';
            } elseif ($validated['mail_port'] == 25) {
                $encryption = null;
            }

            // Update .env file
            $this->updateEnvFile([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => $validated['mail_host'],
                'MAIL_PORT' => $validated['mail_port'],
                'MAIL_USERNAME' => $validated['mail_username'],
                'MAIL_PASSWORD' => $validated['mail_password'] ?? config('mail.mailers.smtp.password'),
                'MAIL_ENCRYPTION' => $encryption ?: 'null',
                'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
                'MAIL_FROM_NAME' => $validated['mail_from_name'],
                'MAIL_TIMEOUT' => '30',
                'MAIL_VERIFY_PEER' => 'false',
                'MAIL_VERIFY_PEER_NAME' => 'false',
                'MAIL_SSL_ALLOW_SELF_SIGNED' => 'true',
            ]);

            // Clear config cache
            \Artisan::call('config:clear');

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Email settings saved successfully! Test your configuration with a test email.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Error saving settings: ' . $e->getMessage());
        }
    }

    /**
     * Send test email with detailed error reporting
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => 'required|email',
            'test_message' => 'nullable|string',
        ]);

        try {
            Log::info('=== Test Email Started ===', [
                'to' => $validated['test_email'],
                'config' => [
                    'mailer' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from' => config('mail.from.address'),
                ]
            ]);

            // Send email
            ProfessionalMailService::send(
                $validated['test_email'],
                '📧 Test Email from ' . config('app.name'),
                $this->getTestEmailHtml($validated['test_message']),
                config('mail.from.address'),
                config('mail.from.name')
            );

            Log::info('=== Test Email Sent Successfully ===', [
                'to' => $validated['test_email'],
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Test email sent successfully to ' . $validated['test_email'] . '! Check your inbox (and spam folder).',
                'details' => [
                    'sent_at' => now()->toDateTimeString(),
                    'to' => $validated['test_email'],
                    'from' => config('mail.from.address'),
                ]
            ]);

        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // SMTP Transport errors
            Log::error('=== SMTP Transport Error ===', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                ]
            ]);

            return response()->json([
                'success' => false,
                'message' => '❌ SMTP Connection Error',
                'error' => $e->getMessage(),
                'details' => [
                    'type' => 'SMTP Transport Exception',
                    'server' => config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'suggestion' => 'Check SMTP server address, port, and firewall settings'
                ]
            ], 500);

        } catch (\Swift_TransportException $e) {
            // Swift Mailer transport errors (legacy)
            Log::error('=== Swift Transport Error ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '❌ Email Transport Error',
                'error' => $e->getMessage(),
                'details' => [
                    'type' => 'Swift Transport Exception',
                    'suggestion' => 'SMTP connection failed - check server settings'
                ]
            ], 500);

        } catch (\Exception $e) {
            // Generic errors
            $errorMsg = $e->getMessage();
            $errorType = 'Unknown Error';
            $suggestion = 'Check email configuration and logs';

            // Analyze error message
            if (stripos($errorMsg, 'authentication') !== false || stripos($errorMsg, 'auth') !== false) {
                $errorType = 'Authentication Failed';
                $suggestion = 'Username or password is incorrect';
            } elseif (stripos($errorMsg, 'timeout') !== false) {
                $errorType = 'Connection Timeout';
                $suggestion = 'Server is not responding - check host and port';
            } elseif (stripos($errorMsg, 'connection') !== false && stripos($errorMsg, 'refused') !== false) {
                $errorType = 'Connection Refused';
                $suggestion = 'Port may be blocked by firewall';
            } elseif (stripos($errorMsg, 'ssl') !== false || stripos($errorMsg, 'tls') !== false) {
                $errorType = 'SSL/TLS Error';
                $suggestion = 'Check encryption settings (TLS/SSL)';
            } elseif (stripos($errorMsg, '535') !== false) {
                $errorType = 'Authentication Error (535)';
                $suggestion = 'Invalid username or password';
            } elseif (stripos($errorMsg, '550') !== false) {
                $errorType = 'Sender Verification Failed (550)';
                $suggestion = 'From address must be verified on SMTP server';
            }

            Log::error('=== Email Test Failed ===', [
                'error' => $errorMsg,
                'error_type' => $errorType,
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                ]
            ]);

            return response()->json([
                'success' => false,
                'message' => '❌ ' . $errorType,
                'error' => $errorMsg,
                'details' => [
                    'type' => $errorType,
                    'exception' => get_class($e),
                    'location' => basename($e->getFile()) . ':' . $e->getLine(),
                    'suggestion' => $suggestion,
                    'config' => [
                        'Host' => config('mail.mailers.smtp.host'),
                        'Port' => config('mail.mailers.smtp.port'),
                        'Encryption' => config('mail.mailers.smtp.encryption'),
                        'Username' => config('mail.mailers.smtp.username'),
                        'From' => config('mail.from.address'),
                    ]
                ]
            ], 500);
        }
    }

    /**
     * Get HTML for test email
     */
    private function getTestEmailHtml($message = null)
    {
        $appName = config('app.name', 'Cambridge College');
        $timestamp = now()->format('Y-m-d H:i:s');

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #f5f5f5; padding: 20px; border-radius: 8px; }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 30px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { background: white; padding: 30px; border-radius: 0 0 8px 8px; }
        .success { color: #10b981; font-weight: bold; font-size: 18px; }
        .info-box { background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 15px 0; border-radius: 4px; }
        .footer { text-align: center; padding-top: 20px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ SMTP Configuration Test</h1>
            <p>Your email configuration is working correctly!</p>
        </div>

        <div class="content">
            <p class="success">Email delivery is working properly!</p>

            <div class="info-box">
                <strong>Test Information:</strong><br>
                Application: {$appName}<br>
                Test Time: {$timestamp}<br>
                Email Provider: SMTP
            </div>

            <h3>Configuration Status</h3>
            <ul>
                <li>✅ SMTP Connection: OK</li>
                <li>✅ Authentication: OK</li>
                <li>✅ Email Delivery: OK</li>
            </ul>

            <p>Your {$appName} system is now ready to send emails in production.</p>
HTML;

        if ($message) {
            $html .= <<<HTML

            <h3>Your Message:</h3>
            <p>{$message}</p>
HTML;
        }

        $html .= <<<HTML

            <div class="footer">
                <p>This is an automated test email. Please do not reply to this message.</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;

        return $html;
    }

    /**
     * Update .env file
     */
    private function updateEnvFile($values)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            // Escape quotes in value
            $value = addslashes($value);

            // Check if key exists
            if (strpos($envContent, $key . '=') !== false) {
                // Update existing key
                $envContent = preg_replace(
                    '/^' . preg_quote($key) . '=.*$/m',
                    $key . '="' . $value . '"',
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= "\n" . $key . '="' . $value . '"';
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
