<?php
// Setup database for XAMPP
echo "<h2>Database Setup for XAMPP</h2>";

// Database connection without specifying database name first
try {
    $host = 'localhost';
    $port = '3306';
    $username = 'root';
    $password = '';
    
    // Connect without database name to create it if needed
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL server<br>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS grand_restaurant");
    echo "✅ Database 'grand_restaurant' created/verified<br>";
    
    // Use the database
    $pdo->exec("USE grand_restaurant");
    echo "✅ Using database 'grand_restaurant'<br>";
    
    // Create users table if it doesn't exist
    $create_users_table = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        phone VARCHAR(20),
        password_hash VARCHAR(255) NOT NULL,
        address TEXT,
        date_of_birth DATE,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL
    )";
    
    $pdo->exec($create_users_table);
    echo "✅ Users table created/verified<br>";
    
    // Check if any users exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $count = $stmt->fetch()['count'];
    
    echo "📊 Current users in database: $count<br>";
    
    // Create a test user if no users exist
    if ($count == 0) {
        $test_email = 'admin@restaurant.com';
        $test_password = 'admin123';
        $password_hash = password_hash($test_password, PASSWORD_DEFAULT);
        
        $insert_admin = "INSERT INTO users (first_name, last_name, email, password_hash, is_active) 
                        VALUES ('Admin', 'User', :email, :password_hash, 1)";
        
        $stmt = $pdo->prepare($insert_admin);
        $stmt->bindParam(':email', $test_email);
        $stmt->bindParam(':password_hash', $password_hash);
        
        if ($stmt->execute()) {
            echo "✅ Test admin user created<br>";
            echo "📧 Email: $test_email<br>";
            echo "🔑 Password: $test_password<br>";
        } else {
            echo "❌ Failed to create test user<br>";
        }
    }
    
    // Create other necessary tables
    
    // Menu items table
    $create_menu_table = "
    CREATE TABLE IF NOT EXISTS menu_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category VARCHAR(100) NOT NULL,
        image_url VARCHAR(500),
        is_available BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($create_menu_table);
    echo "✅ Menu items table created/verified<br>";
    
    // Orders table
    $create_orders_table = "
    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        delivery_address TEXT NOT NULL,
        items JSON NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
        payment_method VARCHAR(50),
        payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )";
    
    $pdo->exec($create_orders_table);
    echo "✅ Orders table created/verified<br>";
    
    // Reservations table
    $create_reservations_table = "
    CREATE TABLE IF NOT EXISTS reservations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        party_size INT NOT NULL,
        reservation_date DATE NOT NULL,
        reservation_time TIME NOT NULL,
        special_requests TEXT,
        status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )";
    
    $pdo->exec($create_reservations_table);
    echo "✅ Reservations table created/verified<br>";
    
    // Reviews table
    $create_reviews_table = "
    CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255) NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        review_text TEXT NOT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )";
    
    $pdo->exec($create_reviews_table);
    echo "✅ Reviews table created/verified<br>";
    
    echo "<h3>Database Setup Complete!</h3>";
    echo "<p>✅ All tables have been created and verified.</p>";
    echo "<p>🎯 You can now test the login system.</p>";
    
    if ($count == 0) {
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>Test Login Credentials:</h4>";
        echo "<strong>Email:</strong> admin@restaurant.com<br>";
        echo "<strong>Password:</strong> admin123<br>";
        echo "</div>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
    echo "<p>Please make sure XAMPP MySQL is running and try again.</p>";
}
?>
