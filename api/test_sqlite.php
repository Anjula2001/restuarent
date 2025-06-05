<?php
require_once '../config/database_sqlite.php';

setCorsHeaders();

// Test database connection and basic functionality
try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h1>Grand Restaurant Backend Test</h1>";
    echo "<h2>Database Connection: ✅ Success</h2>";
    
    // Test menu items
    echo "<h3>Menu Items:</h3>";
    $stmt = $db->query("SELECT * FROM menu_items LIMIT 5");
    $menu_items = $stmt->fetchAll();
    echo "<ul>";
    foreach ($menu_items as $item) {
        echo "<li>{$item['name']} - $" . number_format($item['price'], 2) . " ({$item['category']})</li>";
    }
    echo "</ul>";
    
    // Test reviews
    echo "<h3>Customer Reviews:</h3>";
    $stmt = $db->query("SELECT * FROM reviews WHERE is_approved = 1 LIMIT 3");
    $reviews = $stmt->fetchAll();
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li><strong>{$review['customer_name']}</strong> ({$review['rating']}/5): {$review['review_text']}</li>";
    }
    echo "</ul>";
    
    // Test API endpoints
    echo "<h3>API Endpoints Test:</h3>";
    echo "<ul>";
    echo "<li><a href='menu.php' target='_blank'>Menu API</a> - GET /api/menu.php</li>";
    echo "<li><a href='reviews.php' target='_blank'>Reviews API</a> - GET /api/reviews.php</li>";
    echo "<li><a href='reservations.php' target='_blank'>Reservations API</a> - GET /api/reservations.php</li>";
    echo "<li><a href='orders.php' target='_blank'>Orders API</a> - GET /api/orders.php</li>";
    echo "</ul>";
    
    echo "<h3>Admin Dashboard:</h3>";
    echo "<p><a href='../admin/index.html' target='_blank'>Open Admin Dashboard</a></p>";
    
    echo "<h3>Frontend Website:</h3>";
    echo "<p><a href='../index.html' target='_blank'>Open Restaurant Website</a></p>";
    
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #d4af37; }
        h2 { color: #2c3e50; }
        h3 { color: #34495e; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        ul { margin: 10px 0; }
        li { margin: 5px 0; }
    </style>";
    
} catch (Exception $e) {
    echo "<h2>❌ Database Error: " . $e->getMessage() . "</h2>";
}
?>
