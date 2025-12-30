<?php
/**
 * Diagnostic file to check if /public/ is accessible
 * Access via: https://cambridgecollage.com/public/check-public-access.php
 */

header('Content-Type: text/plain');

echo "=== PUBLIC ACCESS DIAGNOSTIC ===\n\n";

echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n\n";

echo "Current Directory: " . __DIR__ . "\n";
echo "Root index.php exists: " . (file_exists(__DIR__ . '/../index.php') ? 'YES' : 'NO') . "\n";
echo "Public index.php exists: " . (file_exists(__DIR__ . '/index.php') ? 'YES' : 'NO') . "\n\n";

echo "=== THE PROBLEM ===\n";
if (strpos($_SERVER['REQUEST_URI'], '/public/') !== false) {
    echo "❌ You accessed via /public/ - THIS SHOULD BE BLOCKED!\n";
    echo "This means the blocking mechanism is not working.\n\n";
    
    echo "=== POSSIBLE CAUSES ===\n";
    echo "1. Apache Alias directive pointing /public → public folder\n";
    echo "2. Symlink from /public to public folder\n";
    echo "3. Document Root is set to public folder\n";
    echo "4. .htaccess rules not being applied\n";
    echo "5. mod_rewrite not enabled properly\n";
} else {
    echo "✅ You accessed correctly (not via /public/)\n";
}

echo "\n=== SOLUTION ===\n";
echo "Add this to public/.htaccess at the TOP:\n\n";
echo "RewriteCond %{REQUEST_URI} ^/public/ [NC]\n";
echo "RewriteRule ^(.*)$ /%{REQUEST_URI} [R=301,L]\n";

