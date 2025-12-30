<?php

/**
 * Security: Block direct access to /public/ directory
 * This file should be in the ROOT of your Laravel project
 *
 * If someone tries to access /public/, redirect them
 */

// Check if the URL contains /public/
if (preg_match('#^/public/#i', $_SERVER['REQUEST_URI'])) {
    // Remove /public/ from the path
    $newPath = preg_replace('#^/public/#i', '/', $_SERVER['REQUEST_URI']);

    // Redirect permanently (301)
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $newPath);
    exit;
}

// If not /public/, redirect to Laravel's public/index.php
require __DIR__.'/public/index.php';

