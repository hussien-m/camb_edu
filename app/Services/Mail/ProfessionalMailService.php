<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Simple Email Service using SMTP only
 */
class ProfessionalMailService
{
    /**
     * Send email via SMTP
     */
    public static function send($to, $subject, $html, $from = null, $fromName = null)
    {
        $from = $from ?? config('mail.from.address');
        $fromName = $fromName ?? config('mail.from.name');

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
