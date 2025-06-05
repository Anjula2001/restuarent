<?php
// PHP Error Diagnostics Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PHP Diagnostics</h1>";

echo "<h2>PHP Version & Configuration</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Error Reporting Level: " . error_reporting() . "<br>";
echo "Display Errors: " . (ini_get('display_errors') ? 'ON' : 'OFF') . "<br>";

echo "<h2>Required Extensions</h2>";
echo "PDO: " . (extension_loaded('pdo') ? '✅ Loaded' : '❌ Missing') . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Loaded' : '❌ Missing') . "<br>";

echo "<h2>File System Check</h2>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Config Directory Exists: " . (is_dir('config') ? '✅ Yes' : '❌ No') . "<br>";
echo "Database Config Exists: " . (file_exists('config/database.php') ? '✅ Yes' : '❌ No') . "<br>";
echo "Classes Directory Exists: " . (is_dir('classes') ? '✅ Yes' : '❌ No') . "<br>";

echo "<h2>Database Configuration Test</h2>";
try {
    if (file_exists('config/database.php')) {
        // Test including the database config
        ob_start();
        include_once 'config/database.php';
        $output = ob_get_clean();
        
        if (isset($pdo)) {
            echo "✅ Database connection successful<br>";
            echo "Connection object created: " . get_class($pdo) . "<br>";
        } else {
            echo "❌ Database connection failed - \$pdo variable not set<br>";
        }
    } else {
        echo "❌ Database config file not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Database connection error: " . $e->getMessage() . "<br>";
}

echo "<h2>API Files Check</h2>";
$apiFiles = ['menu.php', 'reservations.php', 'orders.php', 'reviews.php'];
foreach ($apiFiles as $file) {
    $path = "api/$file";
    if (file_exists($path)) {
        echo "$file: ✅ Exists<br>";
        // Check for syntax errors
        $output = shell_exec("php -l $path 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "$file: ✅ Valid syntax<br>";
        } else {
            echo "$file: ❌ Syntax error - $output<br>";
        }
    } else {
        echo "$file: ❌ Missing<br>";
    }
}

echo "<h2>Permissions Check</h2>";
echo "Current script readable: " . (is_readable(__FILE__) ? '✅ Yes' : '❌ No') . "<br>";
echo "Config directory readable: " . (is_readable('config') ? '✅ Yes' : '❌ No') . "<br>";
echo "API directory readable: " . (is_readable('api') ? '✅ Yes' : '❌ No') . "<br>";

echo "<h2>Recent PHP Errors</h2>";
$errorLog = ini_get('error_log');
if ($errorLog && file_exists($errorLog)) {
    $errors = tail($errorLog, 10);
    echo "<pre>$errors</pre>";
} else {
    echo "No error log found or configured<br>";
}

function tail($filename, $lines = 10) {
    $data = file($filename);
    return implode("", array_slice($data, -$lines));
}
?>
