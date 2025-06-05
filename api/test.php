<?php
// Grand Restaurant Backend Test Script
header('Content-Type: application/json');

$tests = [];
$overall_status = true;

// Test 1: PHP Version
$php_version = phpversion();
$php_ok = version_compare($php_version, '7.4.0', '>=');
$tests['php_version'] = [
    'name' => 'PHP Version',
    'status' => $php_ok ? 'PASS' : 'FAIL',
    'message' => "PHP $php_version " . ($php_ok ? '(>= 7.4 required)' : '(< 7.4, upgrade required)'),
    'required' => true
];
if (!$php_ok) $overall_status = false;

// Test 2: PDO Extension
$pdo_ok = extension_loaded('pdo') && extension_loaded('pdo_mysql');
$tests['pdo_extension'] = [
    'name' => 'PDO MySQL Extension',
    'status' => $pdo_ok ? 'PASS' : 'FAIL',
    'message' => $pdo_ok ? 'PDO MySQL extension is available' : 'PDO MySQL extension is missing',
    'required' => true
];
if (!$pdo_ok) $overall_status = false;

// Test 3: Database Configuration File
$config_exists = file_exists('../config/database.php');
$tests['config_file'] = [
    'name' => 'Database Config File',
    'status' => $config_exists ? 'PASS' : 'FAIL',
    'message' => $config_exists ? 'Database configuration file exists' : 'Database configuration file missing',
    'required' => true
];
if (!$config_exists) $overall_status = false;

// Test 4: Database Connection
$db_connection = false;
if ($config_exists) {
    try {
        require_once '../config/database.php';
        $database = new Database();
        $conn = $database->getConnection();
        $db_connection = $conn !== null;
        
        if ($db_connection) {
            // Test if tables exist
            $stmt = $conn->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $required_tables = ['menu_items', 'reservations', 'orders', 'order_items', 'reviews', 'admin_users'];
            $missing_tables = array_diff($required_tables, $tables);
            
            if (empty($missing_tables)) {
                $tests['database_tables'] = [
                    'name' => 'Database Tables',
                    'status' => 'PASS',
                    'message' => 'All required tables exist',
                    'required' => true
                ];
            } else {
                $tests['database_tables'] = [
                    'name' => 'Database Tables',
                    'status' => 'FAIL',
                    'message' => 'Missing tables: ' . implode(', ', $missing_tables),
                    'required' => true
                ];
                $overall_status = false;
            }
        }
    } catch (Exception $e) {
        $db_connection = false;
    }
}

$tests['database_connection'] = [
    'name' => 'Database Connection',
    'status' => $db_connection ? 'PASS' : 'FAIL',
    'message' => $db_connection ? 'Successfully connected to database' : 'Failed to connect to database',
    'required' => true
];
if (!$db_connection) $overall_status = false;

// Test 5: API Endpoints
$api_files = ['menu.php', 'reservations.php', 'orders.php', 'reviews.php'];
$missing_apis = [];
foreach ($api_files as $file) {
    if (!file_exists($file)) {
        $missing_apis[] = $file;
    }
}

$tests['api_files'] = [
    'name' => 'API Files',
    'status' => empty($missing_apis) ? 'PASS' : 'FAIL',
    'message' => empty($missing_apis) ? 'All API files exist' : 'Missing API files: ' . implode(', ', $missing_apis),
    'required' => true
];
if (!empty($missing_apis)) $overall_status = false;

// Test 6: Class Files
$class_files = ['MenuManager.php', 'ReservationManager.php', 'OrderManager.php', 'ReviewManager.php'];
$missing_classes = [];
foreach ($class_files as $file) {
    if (!file_exists("../classes/$file")) {
        $missing_classes[] = $file;
    }
}

$tests['class_files'] = [
    'name' => 'Class Files',
    'status' => empty($missing_classes) ? 'PASS' : 'FAIL',
    'message' => empty($missing_classes) ? 'All class files exist' : 'Missing class files: ' . implode(', ', $missing_classes),
    'required' => true
];
if (!empty($missing_classes)) $overall_status = false;

// Test 7: Write Permissions (for uploads, logs, etc.)
$writable_dirs = ['../images', '../logs'];
$permission_issues = [];
foreach ($writable_dirs as $dir) {
    if (is_dir($dir) && !is_writable($dir)) {
        $permission_issues[] = $dir;
    }
}

$tests['write_permissions'] = [
    'name' => 'Write Permissions',
    'status' => empty($permission_issues) ? 'PASS' : 'WARNING',
    'message' => empty($permission_issues) ? 'Directories are writable' : 'Some directories may not be writable: ' . implode(', ', $permission_issues),
    'required' => false
];

// Return results
$result = [
    'overall_status' => $overall_status ? 'PASS' : 'FAIL',
    'message' => $overall_status ? 'Backend is ready for use!' : 'Some issues need to be resolved',
    'timestamp' => date('Y-m-d H:i:s'),
    'tests' => $tests
];

echo json_encode($result, JSON_PRETTY_PRINT);
?>
