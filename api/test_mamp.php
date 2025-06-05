<?php
require_once '../config/database.php';

setCorsHeaders();

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>Grand Restaurant - MAMP Test</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
    .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
    h1 { color: #d4af37; text-align: center; }
    h2 { color: #2c3e50; border-bottom: 2px solid #d4af37; padding-bottom: 5px; }
    h3 { color: #34495e; }
    .success { color: #27ae60; font-weight: bold; }
    .error { color: #e74c3c; font-weight: bold; }
    .info { background: #ecf0f1; padding: 10px; border-left: 4px solid #3498db; margin: 10px 0; }
    a { color: #3498db; text-decoration: none; }
    a:hover { text-decoration: underline; }
    ul { margin: 10px 0; }
    li { margin: 5px 0; }
    .api-test { background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 4px; }
    .status-ok { color: #27ae60; }
    .status-error { color: #e74c3c; }
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<h1>🍽️ Grand Restaurant - MAMP System Test</h1>";

// Test database connection
echo "<h2>Database Connection Test</h2>";
try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "<p class='success'>✅ Database Connection: SUCCESS</p>";
        echo "<div class='info'>Connected to MySQL database 'grand_restaurant' on MAMP</div>";
        
        // Test tables exist
        echo "<h3>Database Tables:</h3>";
        $tables = ['menu_items', 'reservations', 'orders', 'order_items', 'reviews', 'admin_users'];
        foreach ($tables as $table) {
            try {
                $stmt = $db->query("SELECT COUNT(*) as count FROM $table");
                $count = $stmt->fetch()['count'];
                echo "<p class='status-ok'>✅ Table '$table': $count records</p>";
            } catch (Exception $e) {
                echo "<p class='status-error'>❌ Table '$table': " . $e->getMessage() . "</p>";
            }
        }
        
        // Test sample data
        echo "<h3>Sample Menu Items:</h3>";
        try {
            $stmt = $db->query("SELECT name, price, category FROM menu_items LIMIT 5");
            $items = $stmt->fetchAll();
            if (count($items) > 0) {
                echo "<ul>";
                foreach ($items as $item) {
                    echo "<li>{$item['name']} - $" . number_format($item['price'], 2) . " ({$item['category']})</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='status-error'>❌ No menu items found. Please import schema.sql</p>";
            }
        } catch (Exception $e) {
            echo "<p class='status-error'>❌ Error fetching menu items: " . $e->getMessage() . "</p>";
        }
        
        // Test customer reviews
        echo "<h3>Sample Reviews:</h3>";
        try {
            $stmt = $db->query("SELECT customer_name, rating, review_text FROM reviews WHERE is_approved = 1 LIMIT 3");
            $reviews = $stmt->fetchAll();
            if (count($reviews) > 0) {
                echo "<ul>";
                foreach ($reviews as $review) {
                    echo "<li><strong>{$review['customer_name']}</strong> ({$review['rating']}/5): " . substr($review['review_text'], 0, 50) . "...</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='info'>ℹ️ No approved reviews yet</p>";
            }
        } catch (Exception $e) {
            echo "<p class='status-error'>❌ Error fetching reviews: " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p class='error'>❌ Database Connection: FAILED</p>";
        echo "<div class='info'>Please check MAMP MySQL is running and database exists</div>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database Connection Error: " . $e->getMessage() . "</p>";
    echo "<div class='info'>
        <strong>Troubleshooting:</strong><br>
        1. Ensure MAMP is running<br>
        2. Check MySQL port is 8889<br>
        3. Verify database 'grand_restaurant' exists<br>
        4. Import database/schema.sql in phpMyAdmin
    </div>";
}

// Test API endpoints
echo "<h2>API Endpoints Test</h2>";
$base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
$apis = [
    'Menu API' => 'menu.php',
    'Reviews API' => 'reviews.php',
    'Reservations API' => 'reservations.php',
    'Orders API' => 'orders.php'
];

foreach ($apis as $name => $file) {
    echo "<div class='api-test'>";
    echo "<strong>$name:</strong> ";
    $url = $base_url . '/' . $file;
    echo "<a href='$url' target='_blank'>$url</a>";
    echo "</div>";
}

// Test frontend pages
echo "<h2>Frontend Pages</h2>";
$frontend_base = str_replace('/api', '', $base_url);
$pages = [
    'Main Website' => 'index.html',
    'Menu Page' => 'menu.html',
    'Reservations' => 'reservation.html',
    'Orders' => 'order.html',
    'Contact' => 'contact.html',
    'Admin Dashboard' => 'admin/index.html'
];

foreach ($pages as $name => $file) {
    echo "<div class='api-test'>";
    echo "<strong>$name:</strong> ";
    $url = $frontend_base . '/' . $file;
    echo "<a href='$url' target='_blank'>$url</a>";
    echo "</div>";
}

// MAMP specific information
echo "<h2>MAMP Configuration</h2>";
echo "<div class='info'>";
echo "<strong>Current MAMP Setup:</strong><br>";
echo "• Apache Port: " . $_SERVER['SERVER_PORT'] . "<br>";
echo "• Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "• PHP Version: " . phpversion() . "<br>";
echo "• Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "</div>";

// Quick actions
echo "<h2>Quick Actions</h2>";
echo "<div class='api-test'>";
echo "🗄️ <a href='http://localhost:8888/MAMP/' target='_blank'>Open MAMP WebStart</a><br>";
echo "🗃️ <a href='http://localhost:8888/phpmyadmin/' target='_blank'>Open phpMyAdmin</a><br>";
echo "🏠 <a href='$frontend_base/' target='_blank'>Visit Restaurant Website</a><br>";
echo "⚙️ <a href='$frontend_base/admin/' target='_blank'>Open Admin Panel</a>";
echo "</div>";

echo "</div>";
echo "</body></html>";
?>
