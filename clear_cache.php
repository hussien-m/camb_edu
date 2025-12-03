<?php
/**
 * Clear Cache Script for cPanel/Shared Hosting
 * 
 * Upload this file to your project root and access it via:
 * https://yourdomain.com/clear_cache.php
 * 
 * IMPORTANT: Delete this file after use for security!
 */

// Prevent public access (optional - add your IP)
// $allowed_ips = ['YOUR_IP_HERE'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
//     die('Access denied');
// }

echo "<h1>🧹 Laravel Cache Cleaner</h1>";
echo "<pre>";

// Change directory to project root (if needed)
// chdir(__DIR__);

echo "Starting cache clear process...\n\n";

// Array of commands to run
$commands = [
    'optimize:clear' => 'Clear all optimizations',
    'view:clear' => 'Clear compiled views',
    'cache:clear' => 'Clear application cache',
    'config:clear' => 'Clear configuration cache',
    'route:clear' => 'Clear route cache',
];

// Execute clearing commands
foreach ($commands as $command => $description) {
    echo "📌 $description ($command)...\n";
    $output = shell_exec("php artisan $command 2>&1");
    echo $output . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n\n";

// Rebuild cache for production
$rebuild_commands = [
    'config:cache' => 'Cache configuration',
    'route:cache' => 'Cache routes',
    'view:cache' => 'Cache views',
];

echo "🔧 Rebuilding cache for production...\n\n";

foreach ($rebuild_commands as $command => $description) {
    echo "📌 $description ($command)...\n";
    $output = shell_exec("php artisan $command 2>&1");
    echo $output . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n\n";

// Additional cleanup
echo "📁 Clearing manual cache directories...\n\n";

$cache_dirs = [
    'storage/framework/cache/data',
    'storage/framework/views',
    'bootstrap/cache',
];

foreach ($cache_dirs as $dir) {
    if (is_dir($dir)) {
        $files_deleted = 0;
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                if (unlink($file)) {
                    $files_deleted++;
                }
            }
        }
        echo "✓ Deleted $files_deleted files from $dir\n";
    }
}

echo "\n" . str_repeat('=', 50) . "\n\n";
echo "✅ <strong style='color:green;'>Cache cleared successfully!</strong>\n\n";
echo "🚀 Next steps:\n";
echo "   1. Refresh your browser (Ctrl+F5)\n";
echo "   2. Test the website: <a href='/' target='_blank'>Go to Homepage</a>\n";
echo "   3. Test admin panel: <a href='/admin' target='_blank'>Go to Admin</a>\n";
echo "   4. <strong style='color:red;'>DELETE THIS FILE (clear_cache.php) for security!</strong>\n";

echo "</pre>";

echo "<hr>";
echo "<h3>⚠️ Security Warning</h3>";
echo "<p style='color:red;'><strong>IMPORTANT:</strong> Delete this file immediately after use!</p>";
echo "<form method='post'>";
echo "<button type='submit' name='delete_self' style='padding:10px 20px; background:red; color:white; border:none; cursor:pointer;'>🗑️ Delete This File Now</button>";
echo "</form>";

// Self-delete functionality
if (isset($_POST['delete_self'])) {
    if (unlink(__FILE__)) {
        die("<h2 style='color:green;'>✅ File deleted successfully!</h2>");
    } else {
        die("<h2 style='color:red;'>❌ Failed to delete file. Please delete manually: " . __FILE__ . "</h2>");
    }
}

