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
        if (config('services.sendgrid.api_key')) {
            try {
                Log::info("Attempting to send email via SendGrid API", [
                    'to' => $to,
                    'subject' => $subject
                ]);

                $sendgrid = new SendGridApiService();
                $result = $sendgrid->send($to, $subject, $html, $from, $fromName);

                Log::info("Email sent successfully via SendGrid API to: {$to}");
                return true;

            } catch (\Exception $e) {
                Log::warning("SendGrid API failed, falling back to SMTP: " . $e->getMessage());
                // Fall through to SMTP
            }
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
                        ->from($from, $fromName);
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
