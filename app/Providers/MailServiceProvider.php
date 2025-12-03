<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // This helps with server compatibility issues, especially with Office365
        $streamContextOptions = [
            'ssl' => [
                'verify_peer' => env('MAIL_VERIFY_PEER', true),
                'verify_peer_name' => env('MAIL_VERIFY_PEER_NAME', true),
                'allow_self_signed' => env('MAIL_SSL_ALLOW_SELF_SIGNED', false),
                'cafile' => env('MAIL_SSL_CAFILE'),
                'capath' => env('MAIL_SSL_CAPATH'),
                'SNI_enabled' => true,
                'disable_compression' => true,
                'peer_name' => env('MAIL_HOST'),
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

        // Also set socket timeout for better server compatibility
        @ini_set('default_socket_timeout', env('MAIL_TIMEOUT', 60));

        // Set additional PHP settings for better SMTP connectivity on shared hosting
        @ini_set('max_execution_time', 120);

        // Enable allow_url_fopen if possible (for some SMTP connections)
        if (ini_get('allow_url_fopen') == '0' && function_exists('ini_set')) {
            @ini_set('allow_url_fopen', '1');
        }
    }
}

