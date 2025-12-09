<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Professional Email Service with multiple providers
 * Supports: SMTP, SendGrid API, Mailgun API, Amazon SES, PHP mail()
 */
class ProfessionalMailService
{
    private static $provider;
    private static $config;

    /**
     * Initialize mail service
     */
    private static function init()
    {
        self::$provider = env('MAIL_PROVIDER', 'smtp');
        self::$config = [
            'sendgrid_api_key' => env('SENDGRID_API_KEY'),
            'mailgun_api_key' => env('MAILGUN_API_KEY'),
            'mailgun_domain' => env('MAILGUN_DOMAIN'),
            'aws_ses_key' => env('AWS_ACCESS_KEY_ID'),
            'aws_ses_secret' => env('AWS_SECRET_ACCESS_KEY'),
            'aws_ses_region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        ];
    }

    /**
     * Send email using the best available method
     */
    public static function send($to, $subject, $html, $from = null, $fromName = null)
    {
        self::init();

        $from = $from ?? config('mail.from.address');
        $fromName = $fromName ?? config('mail.from.name');

        Log::info("Attempting to send email via " . self::$provider, [
            'to' => $to,
            'subject' => $subject
        ]);

        try {
            switch (self::$provider) {
                case 'sendgrid':
                    return self::sendViaSendGrid($to, $subject, $html, $from, $fromName);

                case 'mailgun':
                    return self::sendViaMailgun($to, $subject, $html, $from, $fromName);

                case 'aws_ses':
                    return self::sendViaAwsSes($to, $subject, $html, $from, $fromName);

                case 'smtp':
                default:
                    return self::sendViaSmtp($to, $subject, $html, $from, $fromName);
            }
        } catch (\Exception $e) {
            Log::error("Primary mail provider failed: " . $e->getMessage());

            // Auto fallback to PHP mail
            return self::sendViaPhpMail($to, $subject, $html, $from, $fromName);
        }
    }

    /**
     * Send via SMTP (Laravel default)
     */
    private static function sendViaSmtp($to, $subject, $html, $from, $fromName)
    {
        Mail::send([], [], function ($message) use ($to, $subject, $html, $from, $fromName) {
            $message->to($to)
                    ->subject($subject)
                    ->html($html)
                    ->from($from, $fromName);
        });

        Log::info("Email sent successfully via SMTP");
        return true;
    }

    /**
     * Send via SendGrid API (Fast & Reliable)
     */
    private static function sendViaSendGrid($to, $subject, $html, $from, $fromName)
    {
        $apiKey = self::$config['sendgrid_api_key'];

        if (!$apiKey) {
            throw new \Exception("SendGrid API key not configured");
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.sendgrid.com/v3/mail/send', [
            'personalizations' => [[
                'to' => [['email' => $to]],
                'subject' => $subject,
            ]],
            'from' => [
                'email' => $from,
                'name' => $fromName,
            ],
            'content' => [[
                'type' => 'text/html',
                'value' => $html,
            ]],
        ]);

        if ($response->successful()) {
            Log::info("Email sent successfully via SendGrid");
            return true;
        }

        throw new \Exception("SendGrid API failed: " . $response->body());
    }

    /**
     * Send via Mailgun API (Fast & Reliable)
     */
    private static function sendViaMailgun($to, $subject, $html, $from, $fromName)
    {
        $apiKey = self::$config['mailgun_api_key'];
        $domain = self::$config['mailgun_domain'];

        if (!$apiKey || !$domain) {
            throw new \Exception("Mailgun credentials not configured");
        }

        $response = Http::withBasicAuth('api', $apiKey)
            ->asForm()
            ->post("https://api.mailgun.net/v3/{$domain}/messages", [
                'from' => "{$fromName} <{$from}>",
                'to' => $to,
                'subject' => $subject,
                'html' => $html,
            ]);

        if ($response->successful()) {
            Log::info("Email sent successfully via Mailgun");
            return true;
        }

        throw new \Exception("Mailgun API failed: " . $response->body());
    }

    /**
     * Send via AWS SES (Cost-effective)
     */
    private static function sendViaAwsSes($to, $subject, $html, $from, $fromName)
    {
        // Using Laravel's built-in SES support
        config(['mail.default' => 'ses']);

        Mail::send([], [], function ($message) use ($to, $subject, $html, $from, $fromName) {
            $message->to($to)
                    ->subject($subject)
                    ->html($html)
                    ->from($from, $fromName);
        });

        Log::info("Email sent successfully via AWS SES");
        return true;
    }

    /**
     * Fallback: PHP mail() function
     */
    private static function sendViaPhpMail($to, $subject, $html, $from, $fromName)
    {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $fromName . ' <' . $from . '>',
            'Reply-To: ' . $from,
            'X-Mailer: PHP/' . phpversion(),
        ];

        $result = @mail($to, $subject, $html, implode("\r\n", $headers));

        if ($result) {
            Log::info("Email sent successfully via PHP mail()");
            return true;
        }

        throw new \Exception("All email methods failed");
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
