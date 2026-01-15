<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElasticEmailApiService
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('services.elastic_email.api_key');
        $this->apiUrl = (string) config('services.elastic_email.api_url');
    }

    /**
     * Send email using Elastic Email HTTP API.
     */
    public function send($to, $subject, $htmlContent, $fromEmail = null, $fromName = null)
    {
        return $this->sendWithPlainText($to, $subject, $htmlContent, null, $fromEmail, $fromName);
    }

    /**
     * Send email with both HTML and plain text versions.
     */
    public function sendWithPlainText($to, $subject, $htmlContent, $plainText = null, $fromEmail = null, $fromName = null)
    {
        $from = $fromEmail ?? config('mail.from.address');
        $name = $fromName ?? config('mail.from.name');
        $cleanSubject = trim(preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', (string) $subject));
        $verifySsl = filter_var(env('ELASTIC_EMAIL_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);

        if (empty($this->apiKey)) {
            throw new \RuntimeException('Elastic Email API key is missing');
        }

        $payload = [
            'apikey' => $this->apiKey,
            'from' => $from,
            'fromName' => $name,
            'to' => $to,
            'subject' => $cleanSubject,
            'bodyHtml' => $htmlContent,
            'isTransactional' => true,
        ];

        if ($plainText) {
            $payload['bodyText'] = $plainText;
        }

        try {
            $response = Http::asForm()
                ->withOptions(['verify' => $verifySsl])
                ->timeout(20)
                ->post($this->apiUrl, $payload);

            $data = $response->json();

            if (!$response->ok() || (isset($data['success']) && $data['success'] === false)) {
                Log::error('Elastic Email API failed', [
                    'status' => $response->status(),
                    'response' => $data,
                    'to' => $to,
                    'subject' => $cleanSubject,
                ]);
                $errorMessage = 'Elastic Email API request failed';
                if (isset($data['error'])) {
                    $errorMessage .= ': ' . $data['error'];
                } elseif (isset($data['message'])) {
                    $errorMessage .= ': ' . $data['message'];
                } else {
                    $errorMessage .= ': HTTP ' . $response->status();
                }
                throw new \RuntimeException($errorMessage);
            }

            Log::info('Elastic Email API email sent successfully', [
                'to' => $to,
                'subject' => $cleanSubject,
                'status' => $response->status(),
            ]);

            return [
                'success' => true,
                'status_code' => $response->status(),
                'message' => 'Email sent successfully via Elastic Email API',
            ];
        } catch (\Exception $e) {
            Log::error('Elastic Email API email failed', [
                'to' => $to,
                'subject' => $cleanSubject,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
