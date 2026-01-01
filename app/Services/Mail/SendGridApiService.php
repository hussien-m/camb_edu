<?php

namespace App\Services\Mail;

use SendGrid;
use SendGrid\Mail\Mail;
use Illuminate\Support\Facades\Log;

class SendGridApiService
{
    protected $sendgrid;

    public function __construct()
    {
        $apiKey = config('services.sendgrid.api_key');
        $this->sendgrid = new SendGrid($apiKey);
    }

    /**
     * Send email using SendGrid API
     */
    public function send($to, $subject, $htmlContent, $fromEmail = null, $fromName = null)
    {
        try {
            $email = new Mail();

            // From
            $from = $fromEmail ?? config('mail.from.address');
            $name = $fromName ?? config('mail.from.name');
            $email->setFrom($from, $name);

            // To
            $email->addTo($to);

            // Subject
            $email->setSubject($subject);

            // Content
            $email->addContent("text/html", $htmlContent);

            // Send
            $response = $this->sendgrid->send($email);

            Log::info('SendGrid API email sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'status_code' => $response->statusCode(),
                'response_body' => $response->body(),
                'response_headers' => $response->headers()
            ]);

            return [
                'success' => true,
                'status_code' => $response->statusCode(),
                'message' => 'Email sent successfully via SendGrid API'
            ];

        } catch (\Exception $e) {
            Log::error('SendGrid API email failed', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Send email with both HTML and plain text versions
     */
    public function sendWithPlainText($to, $subject, $htmlContent, $plainText, $fromEmail = null, $fromName = null)
    {
        try {
            $email = new Mail();

            // From
            $from = $fromEmail ?? config('mail.from.address');
            $name = $fromName ?? config('mail.from.name');
            $email->setFrom($from, $name);

            // To
            $email->addTo($to);

            // Subject (remove emojis for better deliverability)
            $cleanSubject = preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $subject);
            $email->setSubject(trim($cleanSubject));

            // Content - both HTML and plain text
            $email->addContent("text/html", $htmlContent);
            $email->addContent("text/plain", $plainText);

            // Add headers for better deliverability
            $email->addHeader("List-Unsubscribe", '<' . url('/student/unsubscribe') . '>, <mailto:' . $from . '?subject=Unsubscribe>');
            $email->addHeader("List-Unsubscribe-Post", "List-Unsubscribe=One-Click");
            $email->addHeader("X-Mailer", "Cambridge College Email System");
            $email->addHeader("Precedence", "bulk");

            // Send
            $response = $this->sendgrid->send($email);

            Log::info('SendGrid API email sent successfully (with plain text)', [
                'to' => $to,
                'subject' => $cleanSubject,
                'status_code' => $response->statusCode()
            ]);

            return [
                'success' => true,
                'status_code' => $response->statusCode(),
                'message' => 'Email sent successfully via SendGrid API'
            ];

        } catch (\Exception $e) {
            Log::error('SendGrid API email failed', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}
