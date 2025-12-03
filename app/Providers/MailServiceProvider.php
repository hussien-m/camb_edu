<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set default stream context options for SMTP connections
        // Disable SSL verification by default for shared hosting compatibility
        $verifyPeer = filter_var(env('MAIL_VERIFY_PEER', false), FILTER_VALIDATE_BOOLEAN);
        $verifyPeerName = filter_var(env('MAIL_VERIFY_PEER_NAME', false), FILTER_VALIDATE_BOOLEAN);
        $allowSelfSigned = filter_var(env('MAIL_SSL_ALLOW_SELF_SIGNED', true), FILTER_VALIDATE_BOOLEAN);

        $streamContextOptions = [
            'ssl' => [
                'verify_peer' => $verifyPeer,
                'verify_peer_name' => $verifyPeerName,
                'allow_self_signed' => $allowSelfSigned,
                'cafile' => env('MAIL_SSL_CAFILE'),
                'capath' => env('MAIL_SSL_CAPATH'),
                'SNI_enabled' => true,
                'disable_compression' => true,
                'peer_name' => env('MAIL_HOST', 'smtp.office365.com'),
            ],
            'socket' => [
                'tcp_nodelay' => true,
            ],
        ];

        // Set global stream context for better server compatibility
        // This helps resolve connection timeout issues on servers
        if (function_exists('stream_context_set_default')) {
            @stream_context_set_default($streamContextOptions);
        }

        // Also set socket timeout for better server compatibility (shorter for faster response)
        @ini_set('default_socket_timeout', env('MAIL_TIMEOUT', 10));

        // Set additional PHP settings for better SMTP connectivity on shared hosting
        @ini_set('max_execution_time', 60);

        // Enable allow_url_fopen if possible (for some SMTP connections)
        if (ini_get('allow_url_fopen') == '0' && function_exists('ini_set')) {
            @ini_set('allow_url_fopen', '1');
        }

        // Set error reporting to help debug connection issues
        if (config('app.debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', '0'); // Don't display, just log
        }
    }
}

