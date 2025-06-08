<?php
require_once '../config/database.php';

class UserManager {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Register new user
    public function registerUser($first_name, $last_name, $email, $phone, $password, $address = '', $date_of_birth = null) {
        // Check if email already exists
        if ($this->emailExists($email)) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // Validate password strength
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters long'];
        }

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table_name . " 
                  (first_name, last_name, email, phone, password_hash, address, date_of_birth) 
                  VALUES (:first_name, :last_name, :email, :phone, :password_hash, :address, :date_of_birth)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':date_of_birth', $date_of_birth);
            
            if ($stmt->execute()) {
                $user_id = $this->conn->lastInsertId();
                return [
                    'success' => true, 
                    'message' => 'User registered successfully',
                    'user_id' => $user_id
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to register user'];
            }
        } catch (PDOException $e) {
            // Check if it's a duplicate email error
            if (strpos($e->getMessage(), 'UNIQUE constraint failed: users.email') !== false) {
                return ['success' => false, 'message' => 'Email already exists'];
            }
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    // Login user
    public function loginUser($email, $password) {
        $query = "SELECT id, first_name, last_name, email, phone, password_hash, address, is_active 
                  FROM " . $this->table_name . " 
                  WHERE email = :email AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login time
            $this->updateLastLogin($user['id']);
            
            // Remove password hash from returned data
            unset($user['password_hash']);
            
            return [
                'success' => true,
                'message' => 'Login successful',
                'user' => $user
            ];
        } else {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
    }

    // Check if email exists
    private function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Update last login time
    private function updateLastLogin($user_id) {
        $query = "UPDATE " . $this->table_name . " SET last_login = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
    }

    // Get user by ID
    public function getUserById($id) {
        $query = "SELECT id, first_name, last_name, email, phone, address, date_of_birth, 
                         email_verified, created_at, last_login 
                  FROM " . $this->table_name . " 
                  WHERE id = :id AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Update user profile
    public function updateProfile($user_id, $first_name, $last_name, $phone, $address, $date_of_birth) {
        $query = "UPDATE " . $this->table_name . " 
                  SET first_name = :first_name, last_name = :last_name, phone = :phone, 
                      address = :address, date_of_birth = :date_of_birth, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Profile updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update profile'];
        }
    }

    // Change password
    public function changePassword($user_id, $current_password, $new_password) {
        // Get current password hash
        $query = "SELECT password_hash FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // Verify current password
        if (!password_verify($current_password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        // Validate new password
        if (strlen($new_password) < 6) {
            return ['success' => false, 'message' => 'New password must be at least 6 characters long'];
        }

        // Update password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE " . $this->table_name . " 
                        SET password_hash = :password_hash, updated_at = CURRENT_TIMESTAMP 
                        WHERE id = :id";
        
        $update_stmt = $this->conn->prepare($update_query);
        $update_stmt->bindParam(':id', $user_id);
        $update_stmt->bindParam(':password_hash', $new_password_hash);
        
        if ($update_stmt->execute()) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to change password'];
        }
    }

    // Get user orders
    public function getUserOrders($user_id, $limit = null) {
        $query = "SELECT o.*, 
                         GROUP_CONCAT(mi.name || ' (' || oi.quantity || ')', ', ') as items
                  FROM orders o
                  LEFT JOIN order_items oi ON o.id = oi.order_id
                  LEFT JOIN menu_items mi ON oi.menu_item_id = mi.id
                  WHERE o.customer_email = (SELECT email FROM " . $this->table_name . " WHERE id = :user_id)
                  GROUP BY o.id ORDER BY o.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($limit) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get user reservations
    public function getUserReservations($user_id, $limit = null) {
        $query = "SELECT id, customer_name as name, customer_email as email, customer_phone as phone, 
                         reservation_date as date, reservation_time as time, party_size as guests, 
                         special_requests, status, created_at, updated_at 
                  FROM reservations 
                  WHERE customer_email = (SELECT email FROM " . $this->table_name . " WHERE id = :user_id)
                  ORDER BY reservation_date DESC, reservation_time DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($limit) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Deactivate user account
    public function deactivateAccount($user_id) {
        $query = "UPDATE " . $this->table_name . " SET is_active = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Account deactivated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to deactivate account'];
        }
    }
}
?>
