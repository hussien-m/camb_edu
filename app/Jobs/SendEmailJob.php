<?php

namespace App\Jobs;

use App\Services\Mail\ProfessionalMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    public $to;
    public $subject;
    public $html;
    public $from;
    public $fromName;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct($to, $subject, $html, $from = null, $fromName = null)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->html = $html;
        $this->from = $from;
        $this->fromName = $fromName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            ProfessionalMailService::send(
                $this->to,
                $this->subject,
                $this->html,
                $this->from,
                $this->fromName
            );
        } catch (\Exception $e) {
            Log::error('SendEmailJob failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendEmailJob failed after all retries', [
            'to' => $this->to,
            'error' => $exception->getMessage()
        ]);
    }
}
