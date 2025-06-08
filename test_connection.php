<?php
// Test database connection for XAMPP
require_once 'config/database.php';

echo "<h2>Database Connection Test</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Database connection successful!</p>";
        
        // Check if grand_restaurant database exists
        $query = "SHOW DATABASES LIKE 'grand_restaurant'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result) {
            echo "<p style='color: green;'>✅ Database 'grand_restaurant' exists!</p>";
            
            // Check tables
            $query = "SHOW TABLES";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $tables = $stmt->fetchAll();
            
            if (count($tables) > 0) {
                echo "<p style='color: green;'>✅ Found " . count($tables) . " tables:</p>";
                echo "<ul>";
                foreach ($tables as $table) {
                    echo "<li>" . $table[array_keys($table)[0]] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color: orange;'>⚠️ Database exists but no tables found. You need to import the SQL file.</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Database 'grand_restaurant' does not exist!</p>";
            echo "<p>You need to create it first:</p>";
            echo "<ol>";
            echo "<li>Go to <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a></li>";
            echo "<li>Click 'New' to create a database</li>";
            echo "<li>Name it 'grand_restaurant'</li>";
            echo "<li>Set Collation to 'utf8mb4_unicode_ci'</li>";
            echo "<li>Import the SQL file from database/grand_restaurant.sql</li>";
            echo "</ol>";
        }
    } else {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        echo "<p>The database 'grand_restaurant' doesn't exist yet.</p>";
    } elseif (strpos($e->getMessage(), "Access denied") !== false) {
        echo "<p>Check your MySQL username/password in config/database.php</p>";
    } elseif (strpos($e->getMessage(), "Connection refused") !== false) {
        echo "<p>Make sure XAMPP MySQL service is running!</p>";
    }
    
    echo "<h3>Troubleshooting Steps:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP Control Panel shows MySQL as 'Running'</li>";
    echo "<li>Check if you can access <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a></li>";
    echo "<li>Create database 'grand_restaurant' if it doesn't exist</li>";
    echo "<li>Import database/grand_restaurant.sql file</li>";
    echo "</ol>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #333; }
p { margin: 10px 0; }
ol, ul { margin: 10px 0 10px 20px; }
</style>
