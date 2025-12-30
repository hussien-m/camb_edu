<?php

/**
 * Laravel Application Entry Point (ROOT)
 *
 * This file acts as a router for Laravel applications where
 * the document root cannot be set to the public folder.
 *
 * CRITICAL: This file should NEVER execute for internal rewrites
 * to avoid redirect loops.
 */

// Check if this is an internal rewrite from .htaccess
// If so, let Apache handle it and pass to public/index.php
if (isset($_SERVER['REDIRECT_REWRITTEN']) ||
    isset($_SERVER['REDIRECT_SKIP_REWRITE']) ||
    getenv('REWRITTEN') === '1' ||
    getenv('REDIRECT_REWRITTEN') === '1') {
    // This is an internal rewrite, pass to public/index.php
    require __DIR__.'/public/index.php';
    exit;
}

// Check if someone is trying to access /public/ directly
if (preg_match('#^/public/#i', $_SERVER['REQUEST_URI'])) {
    // Remove /public/ from the path
    $newPath = preg_replace('#^/public/#i', '/', $_SERVER['REQUEST_URI']);

    // Redirect permanently (301) - .htaccess should handle this
    // But this is a backup in case .htaccess fails
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $newPath);
    exit;
}

// Normal request - pass to Laravel
require __DIR__.'/public/index.php';

