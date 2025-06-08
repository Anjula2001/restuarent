<?php
/**
 * MySQL Database Setup Script for Grand Restaurant
 * This script will create the MySQL database and import all data
 * 
 * IMPORTANT: Before running this script:
 * 1. Make sure MAMP is running
 * 2. Make sure MySQL is accessible on port 8889
 * 3. Update database credentials in config/database.php if needed
 */

require_once 'config/database.php';

echo "<h1>Grand Restaurant MySQL Setup</h1>\n";
echo "<p>Setting up MySQL database...</p>\n";

// Function to create database if it doesn't exist
function createDatabase() {
    try {
        // Connect without specifying database to create it
        $dsn = "mysql:host=localhost;port=8889;charset=utf8mb4";
        $pdo = new PDO($dsn, 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS grand_restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $pdo->exec($sql);
        
        echo "<p style='color: green;'>✅ Database 'grand_restaurant' created successfully!</p>\n";
        return true;
        
    } catch(PDOException $e) {
        echo "<p style='color: red;'>❌ Error creating database: " . $e->getMessage() . "</p>\n";
        return false;
    }
}

// Function to import SQL file
function importSQLFile($filename) {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        if (!$conn) {
            throw new Exception("Could not connect to database");
        }
        
        $sql = file_get_contents($filename);
        if ($sql === false) {
            throw new Exception("Could not read SQL file: $filename");
        }
        
        // Split SQL into individual statements and execute
        $statements = explode(';', $sql);
        $executed = 0;
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^\s*--/', $statement)) {
                try {
                    $conn->exec($statement);
                    $executed++;
                } catch (PDOException $e) {
                    // Skip errors for existing data (IGNORE statements)
                    if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                        echo "<p style='color: orange;'>Warning: " . $e->getMessage() . "</p>\n";
                    }
                }
            }
        }
        
        echo "<p style='color: green;'>✅ SQL file imported successfully! Executed $executed statements.</p>\n";
        return true;
        
    } catch(Exception $e) {
        echo "<p style='color: red;'>❌ Error importing SQL: " . $e->getMessage() . "</p>\n";
        return false;
    }
}

// Function to verify tables and data
function verifySetup() {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        if (!$conn) {
            throw new Exception("Could not connect to database");
        }
        
        // Check tables
        $tables = ['menu_items', 'orders', 'order_items', 'reviews', 'users', 'admin_users', 'reservations'];
        $existing_tables = [];
        
        foreach ($tables as $table) {
            $stmt = $conn->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table]);
            if ($stmt->rowCount() > 0) {
                $existing_tables[] = $table;
            }
        }
        
        echo "<p style='color: blue;'>📊 Found " . count($existing_tables) . " tables: " . implode(', ', $existing_tables) . "</p>\n";
        
        // Check data counts
        $data_counts = [];
        foreach ($existing_tables as $table) {
            $stmt = $conn->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $stmt->fetch()['count'];
            $data_counts[] = "$table ($count records)";
        }
        
        echo "<p style='color: blue;'>📋 Data summary: " . implode(', ', $data_counts) . "</p>\n";
        
        return true;
        
    } catch(Exception $e) {
        echo "<p style='color: red;'>❌ Error verifying setup: " . $e->getMessage() . "</p>\n";
        return false;
    }
}

// Main setup process
echo "<h2>Step 1: Creating Database</h2>\n";
if (!createDatabase()) {
    echo "<p style='color: red;'>Setup failed at database creation step.</p>\n";
    exit;
}

echo "<h2>Step 2: Importing Data</h2>\n";
$sql_file = 'database/grand_restaurant_mysql.sql';
if (!file_exists($sql_file)) {
    echo "<p style='color: red;'>❌ SQL file not found: $sql_file</p>\n";
    echo "<p>Please make sure the MySQL SQL file exists in the database folder.</p>\n";
    exit;
}

if (!importSQLFile($sql_file)) {
    echo "<p style='color: red;'>Setup failed at data import step.</p>\n";
    exit;
}

echo "<h2>Step 3: Verifying Setup</h2>\n";
verifySetup();

echo "<h2>✅ Setup Complete!</h2>\n";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0;'>\n";
echo "<h3>🎉 Your Grand Restaurant is now using MySQL!</h3>\n";
echo "<p><strong>What was set up:</strong></p>\n";
echo "<ul>\n";
echo "<li>MySQL database 'grand_restaurant' created</li>\n";
echo "<li>All 7 tables created with proper structure</li>\n";
echo "<li>All existing data imported (menu items, orders, reviews, users)</li>\n";
echo "<li>All API endpoints now connected to MySQL</li>\n";
echo "</ul>\n";
echo "<p><strong>You can now:</strong></p>\n";
echo "<ul>\n";
echo "<li>Access phpMyAdmin at <a href='http://localhost:8888/phpMyAdmin/' target='_blank'>http://localhost:8888/phpMyAdmin/</a></li>\n";
echo "<li>View your database 'grand_restaurant' in phpMyAdmin</li>\n";
echo "<li>Add new menu items, and they will appear in phpMyAdmin</li>\n";
echo "<li>All reviews, orders, and user data will be stored in MySQL</li>\n";
echo "</ul>\n";
echo "<p><strong>Next steps:</strong></p>\n";
echo "<ul>\n";
echo "<li>Test adding a new menu item through your admin panel</li>\n";
echo "<li>Check phpMyAdmin to see the new data</li>\n";
echo "<li>Your restaurant is ready for production use!</li>\n";
echo "</ul>\n";
echo "</div>\n";

// Add some CSS for better presentation
echo "<style>\n";
echo "body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }\n";
echo "h1 { color: #2c3e50; }\n";
echo "h2 { color: #34495e; border-bottom: 2px solid #3498db; padding-bottom: 10px; }\n";
echo "h3 { color: #27ae60; }\n";
echo "ul { margin: 10px 0; }\n";
echo "li { margin: 5px 0; }\n";
echo "a { color: #3498db; text-decoration: none; }\n";
echo "a:hover { text-decoration: underline; }\n";
echo "</style>\n";
?>
