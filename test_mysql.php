<?php
/**
 * MySQL Connection Test for Grand Restaurant
 * Quick script to verify MySQL connection is working
 */

echo "<h1>MySQL Connection Test</h1>\n";

// Test direct MySQL connection
echo "<h2>Testing Direct MySQL Connection</h2>\n";
try {
    $dsn = "mysql:host=localhost;port=8889;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Direct MySQL connection successful!</p>\n";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'grand_restaurant'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Database 'grand_restaurant' exists!</p>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Database 'grand_restaurant' does not exist yet.</p>\n";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ MySQL connection failed: " . $e->getMessage() . "</p>\n";
    echo "<p>Please check:</p>\n";
    echo "<ul>\n";
    echo "<li>MAMP is running</li>\n";
    echo "<li>MySQL port is 8889</li>\n";
    echo "<li>MySQL username is 'root'</li>\n";
    echo "<li>MySQL password is 'root'</li>\n";
    echo "</ul>\n";
}

// Test application database connection
echo "<h2>Testing Application Database Connection</h2>\n";
try {
    require_once 'config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Application database connection successful!</p>\n";
        
        // Test a simple query
        $stmt = $conn->query("SELECT 1 as test");
        $result = $stmt->fetch();
        if ($result['test'] == 1) {
            echo "<p style='color: green;'>✅ Database queries working correctly!</p>\n";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Application database connection failed!</p>\n";
    }
    
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Application connection error: " . $e->getMessage() . "</p>\n";
}

// Check current database configuration
echo "<h2>Current Database Configuration</h2>\n";
echo "<p><strong>Host:</strong> localhost</p>\n";
echo "<p><strong>Port:</strong> 8889</p>\n";
echo "<p><strong>Database:</strong> grand_restaurant</p>\n";
echo "<p><strong>Username:</strong> root</p>\n";
echo "<p><strong>Configuration file:</strong> config/database.php</p>\n";

echo "<h2>Next Steps</h2>\n";
echo "<p>If connections are successful, run <a href='setup_mysql.php'><strong>setup_mysql.php</strong></a> to complete the setup.</p>\n";

// Add some CSS
echo "<style>\n";
echo "body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }\n";
echo "h1 { color: #2c3e50; }\n";
echo "h2 { color: #34495e; border-bottom: 2px solid #3498db; padding-bottom: 10px; }\n";
echo "ul { margin: 10px 0; }\n";
echo "li { margin: 5px 0; }\n";
echo "a { color: #3498db; text-decoration: none; font-weight: bold; }\n";
echo "a:hover { text-decoration: underline; }\n";
echo "</style>\n";
?>
