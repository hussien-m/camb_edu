<?php
/**
 * SMTP Connection Test Script
 * 
 * Upload this file to your server root and access it via browser
 * to test SMTP connection settings
 * 
 * Usage: https://yourdomain.com/test_smtp_connection.php
 */

// Security: Only allow access from specific IPs or remove this check for testing
// $allowed_ips = ['127.0.0.1', '::1'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
//     die('Access denied');
// }

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار اتصال SMTP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 5px;
            border-left: 4px solid #3b82f6;
        }
        .success {
            color: #10b981;
            font-weight: bold;
        }
        .error {
            color: #ef4444;
            font-weight: bold;
        }
        .info {
            color: #3b82f6;
        }
        pre {
            background: #1e293b;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 اختبار اتصال SMTP</h1>
        
        <?php
        // Test 1: PHP Extensions
        echo '<div class="test-section">';
        echo '<h2>1. فحص إضافات PHP</h2>';
        
        $extensions = ['openssl', 'sockets', 'curl'];
        foreach ($extensions as $ext) {
            $loaded = extension_loaded($ext);
            echo '<p>';
            echo $loaded ? '<span class="success">✅</span>' : '<span class="error">❌</span>';
            echo " {$ext}: " . ($loaded ? 'مفعّل' : 'غير مفعّل');
            echo '</p>';
        }
        echo '</div>';
        
        // Test 2: PHP Settings
        echo '<div class="test-section">';
        echo '<h2>2. إعدادات PHP</h2>';
        
        $settings = [
            'allow_url_fopen' => ini_get('allow_url_fopen'),
            'default_socket_timeout' => ini_get('default_socket_timeout'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
        ];
        
        echo '<pre>';
        foreach ($settings as $key => $value) {
            $status = ($key === 'allow_url_fopen' && $value) || 
                     ($key !== 'allow_url_fopen' && $value > 0) ? 'success' : 'error';
            echo "<span class='{$status}'>";
            echo str_pad($key, 30) . ": " . ($value ?: 'غير مفعّل');
            echo "</span>\n";
        }
        echo '</pre>';
        echo '</div>';
        
        // Test 3: SMTP Connection Test
        echo '<div class="test-section">';
        echo '<h2>3. اختبار الاتصال بـ SMTP</h2>';
        
        if (isset($_POST['test_connection'])) {
            $host = $_POST['host'] ?? 'smtp.office365.com';
            $port = intval($_POST['port'] ?? 587);
            $timeout = 10;
            
            echo "<p class='info'>محاولة الاتصال بـ {$host}:{$port}...</p>";
            
            $start = microtime(true);
            $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
            $duration = round((microtime(true) - $start) * 1000, 2);
            
            if ($connection) {
                echo "<p class='success'>✅ الاتصال نجح! ({$duration}ms)</p>";
                fclose($connection);
            } else {
                echo "<p class='error'>❌ فشل الاتصال!</p>";
                echo "<p>خطأ: {$errstr} (كود: {$errno})</p>";
                
                // Suggestions
                echo "<h3>💡 اقتراحات:</h3>";
                echo "<ul>";
                if ($errno == 110 || strpos($errstr, 'timed out') !== false) {
                    echo "<li>المنفذ {$port} محظور من قبل Firewall</li>";
                    echo "<li>جرب المنفذ 465 مع SSL</li>";
                    echo "<li>جرب المنفذ 25</li>";
                    echo "<li>اتصل بمزود الاستضافة لفتح المنفذ</li>";
                } elseif ($errno == 111) {
                    echo "<li>السيرفر غير متاح أو العنوان خاطئ</li>";
                    echo "<li>تحقق من صحة عنوان SMTP</li>";
                } else {
                    echo "<li>تحقق من إعدادات Firewall</li>";
                    echo "<li>اتصل بمزود الاستضافة</li>";
                }
                echo "</ul>";
            }
        } else {
            echo '<form method="POST">';
            echo '<p>';
            echo '<label>SMTP Host:</label><br>';
            echo '<input type="text" name="host" value="smtp.office365.com" style="width:100%;padding:8px;margin:5px 0;">';
            echo '</p>';
            echo '<p>';
            echo '<label>Port:</label><br>';
            echo '<select name="port" style="width:100%;padding:8px;margin:5px 0;">';
            echo '<option value="587">587 (TLS - Recommended)</option>';
            echo '<option value="465">465 (SSL)</option>';
            echo '<option value="25">25 (No encryption)</option>';
            echo '</select>';
            echo '</p>';
            echo '<button type="submit" name="test_connection" class="btn">اختبار الاتصال</button>';
            echo '</form>';
        }
        echo '</div>';
        
        // Test 4: Environment Check
        echo '<div class="test-section">';
        echo '<h2>4. معلومات السيرفر</h2>';
        echo '<pre>';
        echo "PHP Version: " . PHP_VERSION . "\n";
        echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
        echo "Server IP: " . ($_SERVER['SERVER_ADDR'] ?? 'Unknown') . "\n";
        echo "Your IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";
        echo "OS: " . PHP_OS . "\n";
        echo '</pre>';
        echo '</div>';
        
        // Test 5: Laravel Config Check
        echo '<div class="test-section">';
        echo '<h2>5. فحص إعدادات Laravel</h2>';
        
        $envPath = __DIR__ . '/.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $mailSettings = [
                'MAIL_MAILER',
                'MAIL_HOST',
                'MAIL_PORT',
                'MAIL_USERNAME',
                'MAIL_ENCRYPTION',
                'MAIL_TIMEOUT',
            ];
            
            echo '<pre>';
            foreach ($mailSettings as $setting) {
                $pattern = "/^{$setting}=(.*)$/m";
                if (preg_match($pattern, $envContent, $matches)) {
                    $value = $matches[1];
                    // Hide password
                    if (strpos($setting, 'PASSWORD') !== false) {
                        $value = str_repeat('*', min(strlen($value), 10));
                    }
                    echo str_pad($setting, 25) . ": " . trim($value, '"\'') . "\n";
                } else {
                    echo str_pad($setting, 25) . ": <span class='error'>غير موجود</span>\n";
                }
            }
            echo '</pre>';
        } else {
            echo '<p class="error">ملف .env غير موجود في المجلد الحالي</p>';
        }
        echo '</div>';
        ?>
        
        <div class="test-section">
            <h2>📝 ملاحظات</h2>
            <ul>
                <li>احذف هذا الملف بعد الانتهاء من الاختبار لأسباب أمنية</li>
                <li>إذا فشل الاتصال، جرب استخدام SMTP خاص بالاستضافة</li>
                <li>استخدم SMTP service مثل SendGrid أو Mailgun للاستضافات المشتركة</li>
            </ul>
        </div>
    </div>
</body>
</html>

