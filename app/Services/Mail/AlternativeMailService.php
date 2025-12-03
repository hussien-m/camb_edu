<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AlternativeMailService
{
    /**
     * Send email using alternative method if SMTP fails
     * Note: SMTP should already be tried before calling this method
     */
    public static function sendWithFallback($to, $subject, $html, $fromEmail = null, $fromName = null)
    {
        $fromEmail = $fromEmail ?? config('mail.from.address');
        $fromName = $fromName ?? config('mail.from.name');
        
        // Skip SMTP (already tried), go directly to sendmail
        return self::sendViaSendmail($to, $subject, $html, $fromEmail, $fromName);
    }
    
    /**
     * Send email using sendmail (works on most shared hosting)
     */
    private static function sendViaSendmail($to, $subject, $html, $fromEmail, $fromName)
    {
        try {
            // Set shorter timeout for sendmail
            @ini_set('default_socket_timeout', 5);
            @set_time_limit(10);
            
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
            
            // Last resort: use PHP mail() function (fastest)
            return self::sendViaPhpMail($to, $subject, $html, $fromEmail, $fromName);
        }
    }
    
    /**
     * Send email using PHP mail() function (last resort - fastest method)
     */
    private static function sendViaPhpMail($to, $subject, $html, $fromEmail, $fromName)
    {
        try {
            // PHP mail() is synchronous but returns immediately
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: ' . $fromName . ' <' . $fromEmail . '>',
                'Reply-To: ' . $fromEmail,
                'X-Mailer: PHP/' . phpversion(),
            ];
            
            // Use @ to suppress warnings, mail() returns immediately
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

