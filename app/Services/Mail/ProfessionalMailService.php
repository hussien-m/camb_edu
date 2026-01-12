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

        // Send directly via SMTP (no SendGrid)
        try {
            // Set shorter timeout for SMTP
            config(['mail.mailers.smtp.timeout' => 15]);

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

        // Send directly via SMTP (no SendGrid)
        try {
            // Set shorter timeout for SMTP
            config(['mail.mailers.smtp.timeout' => 15]);

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
