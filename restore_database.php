<?php
/**
 * Quick Database Restore Script
 * Use this to quickly restore the Grand Restaurant database with all existing data
 */

echo "🍽️ Grand Restaurant - Quick Database Restore\n";
echo "===========================================\n\n";

$db_path = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'grand_restaurant.db';
$complete_path = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'grand_restaurant_complete.sql';

// Check if complete database file exists
if (!file_exists($complete_path)) {
    echo "❌ Error: Complete database file not found!\n";
    echo "Expected: " . $complete_path . "\n";
    exit(1);
}

// Remove existing database if it exists
if (file_exists($db_path)) {
    echo "🗑️ Removing existing database...\n";
    unlink($db_path);
}

// Create database directory if needed
$db_dir = dirname($db_path);
if (!is_dir($db_dir)) {
    mkdir($db_dir, 0755, true);
    echo "📁 Created database directory\n";
}

try {
    echo "📊 Restoring database from complete backup...\n";
    
    // Create new database and restore from complete file
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute complete database file
    $sql = file_get_contents($complete_path);
    if ($sql === false) {
        throw new Exception("Could not read complete database file");
    }
    
    // Execute SQL statements
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    // Set permissions on Unix-like systems
    if (PHP_OS !== 'WINNT') {
        chmod($db_path, 0666);
    }
    
    echo "✅ Database restored successfully!\n\n";
    echo "📊 Restored data includes:\n";
    echo "   • All menu items with current pricing\n";
    echo "   • Customer orders and order history\n";
    echo "   • Customer reviews and ratings\n";
    echo "   • User accounts and profiles\n";
    echo "   • Admin account (admin/admin123)\n\n";
    
    // Get some stats
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menu_items");
    $menu_count = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $order_count = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM reviews WHERE is_approved = 1");
    $review_count = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $user_count = $stmt->fetch()['count'];
    
    echo "📈 Database Statistics:\n";
    echo "   🍽️ Menu Items: " . $menu_count . "\n";
    echo "   📋 Orders: " . $order_count . "\n";
    echo "   ⭐ Approved Reviews: " . $review_count . "\n";
    echo "   👥 Users: " . $user_count . "\n\n";
    
    echo "🌐 Access your system:\n";
    $is_windows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    if ($is_windows) {
        echo "   Frontend: http://localhost/restuarent/\n";
        echo "   Admin: http://localhost/restuarent/admin/\n";
    } else {
        echo "   Frontend: http://localhost:8888/restuarent/\n";
        echo "   Admin: http://localhost:8888/restuarent/admin/\n";
    }
    
} catch (Exception $e) {
    echo "❌ Restore failed: " . $e->getMessage() . "\n";
    
    // Clean up on failure
    if (file_exists($db_path)) {
        unlink($db_path);
    }
    
    echo "\n💡 Try running the main setup instead: php setup.php\n";
}
?>
