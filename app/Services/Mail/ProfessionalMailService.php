<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\Mail\SendGridApiService;

/**
 * Email Service with SendGrid API fallback
 */
class ProfessionalMailService
{
    /**
     * Send email via SendGrid API or SMTP fallback
     */
    public static function send($to, $subject, $html, $from = null, $fromName = null)
    {
        $from = $from ?? config('mail.from.address');
        $fromName = $fromName ?? config('mail.from.name');

        // Try SendGrid API first if configured
        $apiKey = config('services.sendgrid.api_key');

        Log::info("Checking SendGrid API Key", [
            'api_key_exists' => !empty($apiKey),
            'api_key_length' => $apiKey ? strlen($apiKey) : 0,
            'api_key_prefix' => $apiKey ? substr($apiKey, 0, 10) : 'null'
        ]);

        if (!empty($apiKey)) {
            try {
                Log::info("✅ Attempting to send email via SendGrid API", [
                    'to' => $to,
                    'subject' => $subject,
                    'from' => $from
                ]);

                $sendgrid = new SendGridApiService();
                $result = $sendgrid->send($to, $subject, $html, $from, $fromName);

                Log::info("✅ Email sent successfully via SendGrid API to: {$to}", [
                    'result' => $result
                ]);
                return true;

            } catch (\Exception $e) {
                Log::error("❌ SendGrid API failed, falling back to SMTP", [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                // Fall through to SMTP
            }
        } else {
            Log::warning("⚠️ SendGrid API Key not configured, using SMTP fallback");
        }

        // Fallback to SMTP
        Log::info("Sending email via SMTP", [
            'to' => $to,
            'subject' => $subject
        ]);

        try {
            Mail::send([], [], function ($message) use ($to, $subject, $html, $from, $fromName) {
                $message->to($to)
                        ->subject($subject)
                        ->html($html)
                        ->from($from, $fromName)
                        ->replyTo($from, $fromName)
                        ->returnPath($from)
                        ->getHeaders()
                        ->addTextHeader('X-Mailer', 'Cambridge International College Email System')
                        ->addTextHeader('X-Priority', '1')
                        ->addTextHeader('Importance', 'high')
                        ->addTextHeader('List-Unsubscribe', '<' . url('/student/unsubscribe') . '>, <mailto:' . $from . '?subject=Unsubscribe>')
                        ->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');
            });

            Log::info("Email sent successfully via SMTP to: {$to}");
            return true;

        } catch (\Exception $e) {
            Log::error("SMTP email failed: " . $e->getMessage(), [
                'to' => $to,
                'subject' => $subject
            ]);
            throw $e;
        }
    }

    /**
     * Send email with both HTML and plain text versions (better deliverability)
     */
    public static function sendWithPlainText($to, $subject, $html, $plainText, $from = null, $fromName = null)
    {
        $from = $from ?? config('mail.from.address');
        $fromName = $fromName ?? config('mail.from.name');

        // Try SendGrid API first if configured
        $apiKey = config('services.sendgrid.api_key');

        if (!empty($apiKey)) {
            try {
                $sendgrid = new SendGridApiService();
                $result = $sendgrid->sendWithPlainText($to, $subject, $html, $plainText, $from, $fromName);
                return true;
            } catch (\Exception $e) {
                Log::error("❌ SendGrid API failed, falling back to SMTP", [
                    'error' => $e->getMessage()
                ]);
                // Fall through to SMTP
            }
        }

        // Fallback to SMTP
        try {
            Mail::send([], [], function ($message) use ($to, $subject, $html, $plainText, $from, $fromName) {
                $message->to($to)
                        ->subject($subject)
                        ->html($html)
                        ->text($plainText)
                        ->from($from, $fromName)
                        ->replyTo($from, $fromName)
                        ->returnPath($from)
                        ->getHeaders()
                        ->addTextHeader('X-Mailer', 'Cambridge International College Email System')
                        ->addTextHeader('X-Priority', '1')
                        ->addTextHeader('Importance', 'high')
                        ->addTextHeader('List-Unsubscribe', '<' . url('/student/unsubscribe') . '>, <mailto:' . $from . '?subject=Unsubscribe>')
                        ->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click')
                        ->addTextHeader('Message-ID', '<' . time() . '.' . md5($to . time()) . '@' . parse_url(config('app.url'), PHP_URL_HOST) . '>');
            });

            Log::info("Email sent successfully via SMTP to: {$to}");
            return true;

        } catch (\Exception $e) {
            Log::error("SMTP email failed: " . $e->getMessage(), [
                'to' => $to,
                'subject' => $subject
            ]);
            throw $e;
        }
    }

    /**
     * Queue email for background sending
     */
    public static function queue($to, $subject, $html, $from = null, $fromName = null, $delay = 0)
    {
        \Illuminate\Support\Facades\Queue::later(
            now()->addSeconds($delay),
            new \App\Jobs\SendEmailJob($to, $subject, $html, $from, $fromName)
        );

        Log::info("Email queued for sending", ['to' => $to, 'delay' => $delay]);
        return true;
    }
}
