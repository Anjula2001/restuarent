<?php
require_once '../config/database_sqlite.php';

class ReviewManager {
    private $conn;
    private $table_name = "reviews";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add new review
    public function addReview($customer_name, $customer_email, $rating, $review_text) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (customer_name, customer_email, rating, review_text) 
                  VALUES (:customer_name, :customer_email, :rating, :review_text)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':customer_email', $customer_email);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review_text', $review_text);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Review submitted successfully. It will be published after approval.'];
        } else {
            return ['success' => false, 'message' => 'Failed to submit review'];
        }
    }

    // Get approved reviews for public display
    public function getApprovedReviews($limit = null) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_approved = 1 
                  ORDER BY created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all reviews (admin only)
    public function getAllReviews($approved = null) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if ($approved !== null) {
            $query .= " WHERE is_approved = :approved";
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($approved !== null) {
            $stmt->bindParam(':approved', $approved);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get review by ID
    public function getReviewById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Approve review (admin only)
    public function approveReview($id) {
        $query = "UPDATE " . $this->table_name . " SET is_approved = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Reject/unapprove review (admin only)
    public function rejectReview($id) {
        $query = "UPDATE " . $this->table_name . " SET is_approved = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Delete review (admin only)
    public function deleteReview($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get review statistics
    public function getReviewStatistics() {
        $stats = [];

        // Average rating
        $query = "SELECT AVG(rating) as avg_rating FROM " . $this->table_name . " WHERE is_approved = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['average_rating'] = round($stmt->fetch()['avg_rating'], 1);

        // Total approved reviews
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE is_approved = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_reviews'] = $stmt->fetch()['total'];

        // Pending reviews
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE is_approved = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['pending_reviews'] = $stmt->fetch()['total'];

        // Rating distribution
        $query = "SELECT rating, COUNT(*) as count FROM " . $this->table_name . " 
                  WHERE is_approved = 1 GROUP BY rating ORDER BY rating DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['rating_distribution'] = $stmt->fetchAll();

        return $stats;
    }

    // Get reviews by customer email
    public function getReviewsByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE customer_email = :email 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>