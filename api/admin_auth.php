<?php
require_once '../config/database.php';

setCorsHeaders();

class AdminAuth {
    private $conn;
    private $table_name = "admin_users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        // For demo purposes, use simple hardcoded credentials
        // In production, this should check against hashed passwords in database
        if ($username === 'admin' && $password === 'admin123') {
            return [
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'username' => $username,
                    'role' => 'admin'
                ]
            ];
        }

        // Try to check database for additional admin users
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'username' => $user['username'],
                        'role' => $user['role'] ?? 'admin'
                    ]
                ];
            }
        } catch (Exception $e) {
            // If database table doesn't exist, continue with hardcoded check
        }

        return [
            'success' => false,
            'message' => 'Invalid username or password'
        ];
    }

    public function validateSession() {
        // For demo purposes, just return true
        // In production, this should validate session tokens
        return true;
    }
}

$admin_auth = new AdminAuth();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['action'])) {
            sendJsonResponse(['error' => 'Action is required'], 400);
        }

        switch ($input['action']) {
            case 'login':
                if (!isset($input['username']) || !isset($input['password'])) {
                    sendJsonResponse(['error' => 'Username and password are required'], 400);
                }

                $result = $admin_auth->login($input['username'], $input['password']);
                sendJsonResponse($result, $result['success'] ? 200 : 401);
                break;

            default:
                sendJsonResponse(['error' => 'Invalid action'], 400);
                break;
        }
        break;

    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'validate') {
            $isValid = $admin_auth->validateSession();
            sendJsonResponse(['valid' => $isValid]);
        } else {
            sendJsonResponse(['error' => 'Invalid request'], 400);
        }
        break;

    default:
        sendJsonResponse(['error' => 'Method not allowed'], 405);
        break;
}
?>
