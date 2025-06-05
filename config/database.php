<?php
class Database {
    // MAMP MySQL Configuration
    private $host = 'localhost';
    private $port = '8889'; // Default MAMP MySQL port
    private $db_name = 'grand_restaurant';
    private $username = 'root';
    private $password = 'root'; // Default MAMP MySQL password
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            // MAMP MySQL connection with port
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// CORS headers for API requests
function setCorsHeaders() {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    setCorsHeaders();
    http_response_code(200);
    exit();
}

// Function to validate and sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to send JSON response
function sendJsonResponse($data, $status_code = 200) {
    setCorsHeaders();
    http_response_code($status_code);
    echo json_encode($data);
    exit();
}

// Legacy function name support
function sendResponse($data, $status_code = 200) {
    sendJsonResponse($data, $status_code);
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
?>