<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AlternativeMailService
{
    /**
     * Send email using alternative method if SMTP fails
     */
    public static function sendWithFallback($to, $subject, $html, $fromEmail = null, $fromName = null)
    {
        $fromEmail = $fromEmail ?? config('mail.from.address');
        $fromName = $fromName ?? config('mail.from.name');
        
        // Try SMTP first
        try {
            Mail::send([], [], function ($message) use ($to, $subject, $html, $fromEmail, $fromName) {
                $message->to($to)
                    ->subject($subject)
                    ->html($html)
                    ->from($fromEmail, $fromName);
            });
            
            Log::info('Email sent successfully via SMTP');
            return true;
        } catch (\Exception $e) {
            Log::warning('SMTP failed, trying alternative method: ' . $e->getMessage());
            
            // Try sendmail as fallback
            return self::sendViaSendmail($to, $subject, $html, $fromEmail, $fromName);
        }
    }
    
    /**
     * Send email using sendmail (works on most shared hosting)
     */
    private static function sendViaSendmail($to, $subject, $html, $fromEmail, $fromName)
    {
        try {
            // Change mailer to sendmail temporarily
            $originalMailer = config('mail.default');
            config(['mail.default' => 'sendmail']);
            
            Mail::send([], [], function ($message) use ($to, $subject, $html, $fromEmail, $fromName) {
                $message->to($to)
                    ->subject($subject)
                    ->html($html)
                    ->from($fromEmail, $fromName);
            });
            
            // Restore original mailer
            config(['mail.default' => $originalMailer]);
            
            Log::info('Email sent successfully via sendmail');
            return true;
        } catch (\Exception $e) {
            Log::error('Sendmail also failed: ' . $e->getMessage());
            
            // Last resort: use PHP mail() function
            return self::sendViaPhpMail($to, $subject, $html, $fromEmail, $fromName);
        }
    }
    
    /**
     * Send email using PHP mail() function (last resort)
     */
    private static function sendViaPhpMail($to, $subject, $html, $fromEmail, $fromName)
    {
        try {
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: ' . $fromName . ' <' . $fromEmail . '>',
                'Reply-To: ' . $fromEmail,
                'X-Mailer: PHP/' . phpversion(),
            ];
            
            $result = @mail($to, $subject, $html, implode("\r\n", $headers));
            
            if ($result) {
                Log::info('Email sent successfully via PHP mail()');
                return true;
            } else {
                Log::error('PHP mail() failed');
                return false;
            }
        } catch (\Exception $e) {
            Log::error('PHP mail() exception: ' . $e->getMessage());
            return false;
        }
    }
}

