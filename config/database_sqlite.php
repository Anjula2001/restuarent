<?php
class Database {
    private $db_file;
    private $conn;

    public function __construct() {
        // Use absolute path that works for both local development and MAMP
        $base_path = dirname(dirname(__FILE__));
        $this->db_file = $base_path . '/database/grand_restaurant.db';
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Create database directory if it doesn't exist
            $dir = dirname($this->db_file);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            $this->conn = new PDO("sqlite:" . $this->db_file);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Initialize database structure if tables don't exist
            $this->initializeDatabase();
            
        } catch(PDOException $exception) {
            // Log error silently instead of outputting it directly
            error_log("Database connection error: " . $exception->getMessage());
            // Don't echo errors as it breaks JSON responses
        }
        
        return $this->conn;
    }
    
    private function initializeDatabase() {
        try {
            $sql = "
        -- Create menu_items table
        CREATE TABLE IF NOT EXISTS menu_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            category VARCHAR(50) NOT NULL,
            image_url VARCHAR(255),
            is_available BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Create reservations table
        CREATE TABLE IF NOT EXISTS reservations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name VARCHAR(100) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20),
            reservation_date DATE NOT NULL,
            reservation_time TIME NOT NULL,
            party_size INTEGER NOT NULL,
            special_requests TEXT,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Create orders table
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name VARCHAR(100) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20),
            delivery_address TEXT,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            order_type VARCHAR(20) DEFAULT 'delivery',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Create order_items table
        CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            menu_item_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
        );

        -- Create reviews table
        CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name VARCHAR(100) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
            review_text TEXT,
            is_approved BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Create admin_users table
        CREATE TABLE IF NOT EXISTS admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Create users table for customer accounts
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone VARCHAR(20),
            password_hash VARCHAR(255) NOT NULL,
            address TEXT,
            date_of_birth DATE,
            email_verified BOOLEAN DEFAULT 0,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL
        );
        ";
        
        $this->conn->exec($sql);
        } catch(PDOException $exception) {
            // Log initialization errors silently to prevent breaking JSON responses
            error_log("Database table creation error: " . $exception->getMessage());
        }
        
        // Insert sample data only on first initialization (not every time)
        try {
            $this->insertSampleDataOnFirstRun();
        } catch(PDOException $exception) {
            error_log("Sample data insertion error: " . $exception->getMessage());
        }
    }
    
    private function insertSampleDataOnFirstRun() {
        // Check if this is truly the first initialization by looking for a marker
        try {
            $stmt = $this->conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='initialization_marker'");
            $markerExists = $stmt->fetch();
            
            if (!$markerExists) {
                // First time setup - create marker table and insert sample data
                $this->conn->exec("CREATE TABLE initialization_marker (initialized_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
                $this->conn->exec("INSERT INTO initialization_marker DEFAULT VALUES");
                
                // Now insert sample data since this is the first run
                $this->insertInitialSampleData();
            }
        } catch(PDOException $e) {
            error_log("Initialization marker error: " . $e->getMessage());
        }
    }
    
    private function insertInitialSampleData() {
        // Check if menu_items table is empty
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM menu_items");
        $count = $stmt->fetch()['count'];
        
        if ($count == 0) {
            $sql = "
            INSERT INTO menu_items (name, description, price, category, image_url) VALUES
            ('Classic Margherita Pizza', 'Fresh tomatoes, mozzarella, basil, olive oil', 18.99, 'pizza', 'images/margherita.jpg'),
            ('Grilled Salmon', 'Atlantic salmon with lemon herb butter, seasonal vegetables', 28.99, 'seafood', 'images/salmon.jpg'),
            ('Chicken Parmesan', 'Breaded chicken breast with marinara sauce and mozzarella', 24.99, 'chicken', 'images/chicken-parm.jpg'),
            ('Caesar Salad', 'Romaine lettuce, croutons, parmesan cheese, caesar dressing', 14.99, 'salad', 'images/caesar.jpg'),
            ('Beef Ribeye Steak', '12oz ribeye steak grilled to perfection', 35.99, 'beef', 'images/ribeye.jpg'),
            ('Chocolate Lava Cake', 'Warm chocolate cake with molten center, vanilla ice cream', 8.99, 'dessert', 'images/lava-cake.jpg'),
            ('Lobster Ravioli', 'Homemade ravioli filled with lobster in creamy sauce', 32.99, 'pasta', 'images/lobster-ravioli.jpg'),
            ('Greek Salad', 'Mixed greens, olives, feta cheese, tomatoes, cucumber', 16.99, 'salad', 'images/greek-salad.jpg');

            INSERT INTO reviews (customer_name, customer_email, rating, review_text, is_approved) VALUES
            ('John Smith', 'john@email.com', 5, 'Amazing food and great service! The ribeye steak was perfectly cooked.', 1),
            ('Sarah Johnson', 'sarah@email.com', 4, 'Loved the atmosphere and the salmon was delicious. Will definitely come back!', 1),
            ('Mike Wilson', 'mike@email.com', 5, 'Best pizza in town! The margherita was authentic and fresh.', 1),
            ('Emily Davis', 'emily@email.com', 4, 'Great restaurant for special occasions. The lobster ravioli was outstanding.', 1);

            INSERT OR IGNORE INTO admin_users (username, password_hash, email) VALUES
            ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@grandrestaurant.com');
            ";
            
            $this->conn->exec($sql);
        }
    }
}

// CORS Headers for API requests
function setCorsHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    // Prevent caching to ensure fresh data on every request
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit(0);
    }
}

// Utility function to send JSON response
function sendJsonResponse($data, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}

// Utility function to validate required fields
function validateRequiredFields($data, $required_fields) {
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }
    return $missing_fields;
}

// Utility function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}
?>
