<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Student $student;
    public string $verificationUrl;
    public array $siteSettings;

    public function __construct(Student $student, string $verificationUrl)
    {
        $this->student = $student;
        $this->verificationUrl = $verificationUrl;
        $this->siteSettings = $this->getSiteSettings();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✉️ Verify Your Email - ' . $this->siteSettings['site_title'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student.verification',
        );
    }

    private function getSiteSettings(): array
    {
        return [
            'site_title' => Setting::where('key', 'site_title')->value('value') ?? 'Cambridge British International College in UK',
            'site_logo' => Setting::where('key', 'site_logo')->value('value'),
            'contact_email' => Setting::where('key', 'contact_email')->value('value') ?? 'info@cambridgecollege.com',
            'contact_phone' => Setting::where('key', 'contact_phone')->value('value'),
            'site_description' => Setting::where('key', 'site_description')->value('value'),
            'social_facebook' => Setting::where('key', 'social_facebook')->value('value'),
            'social_twitter' => Setting::where('key', 'social_twitter')->value('value'),
            'social_instagram' => Setting::where('key', 'social_instagram')->value('value'),
        ];
    }
}
