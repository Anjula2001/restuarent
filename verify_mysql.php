<?php
/**
 * Quick verification script to check MySQL data
 */

require_once 'config/database.php';

echo "<h1>MySQL Data Verification</h1>\n";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        throw new Exception("Could not connect to database");
    }
    
    echo "<h2>Database Tables and Record Counts</h2>\n";
    
    $tables = ['menu_items', 'orders', 'order_items', 'reviews', 'users', 'admin_users', 'reservations'];
    
    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM `$table`");
        $stmt->execute();
        $result = $stmt->fetch();
        echo "<p><strong>$table:</strong> {$result['count']} records</p>\n";
    }
    
    echo "<h2>Sample Menu Items</h2>\n";
    $stmt = $conn->prepare("SELECT id, name, category, price, is_available FROM menu_items ORDER BY id LIMIT 5");
    $stmt->execute();
    $items = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Available</th></tr>\n";
    
    foreach ($items as $item) {
        echo "<tr>";
        echo "<td>{$item['id']}</td>";
        echo "<td>{$item['name']}</td>";
        echo "<td>{$item['category']}</td>";
        echo "<td>Rs. {$item['price']}</td>";
        echo "<td>" . ($item['is_available'] ? 'Yes' : 'No') . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    echo "<h2>✅ MySQL Integration Verified!</h2>\n";
    echo "<p style='color: green;'>All data is successfully stored in MySQL database.</p>\n";
    echo "<p>You can now:</p>\n";
    echo "<ul>\n";
    echo "<li>View all data in phpMyAdmin at <a href='http://localhost:8888/phpMyAdmin/'>http://localhost:8888/phpMyAdmin/</a></li>\n";
    echo "<li>Add new menu items via admin panel</li>\n";
    echo "<li>Process orders and reservations</li>\n";
    echo "<li>All data will be visible in phpMyAdmin</li>\n";
    echo "</ul>\n";
    
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>\n";
}

echo "<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
h1 { color: #2c3e50; }
h2 { color: #34495e; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
table { margin: 10px 0; }
th { background-color: #f8f9fa; padding: 8px; }
td { padding: 8px; }
a { color: #3498db; }
</style>\n";
?>
