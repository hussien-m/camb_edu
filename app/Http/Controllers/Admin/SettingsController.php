<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
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
            // Update .env file
            $this->updateEnvFile([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => $validated['mail_host'],
                'MAIL_PORT' => $validated['mail_port'],
                'MAIL_USERNAME' => $validated['mail_username'],
                'MAIL_PASSWORD' => $validated['mail_password'] ?? config('mail.mailers.smtp.password'),
                'MAIL_ENCRYPTION' => 'tls',
                'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
                'MAIL_FROM_NAME' => $validated['mail_from_name'],
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
     * Send test email
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => 'required|email',
            'test_message' => 'nullable|string',
        ]);

        try {
            // Send test email
            Mail::send([], [], function (Message $message) use ($validated) {
                $message
                    ->to($validated['test_email'])
                    ->subject('📧 Test Email from Cambridge College')
                    ->html($this->getTestEmailHtml($validated['test_message']));
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $validated['test_email'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
            ], 400);
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
