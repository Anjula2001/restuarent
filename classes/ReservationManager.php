<?php
require_once '../config/database_sqlite.php';

class ReservationManager {
    private $conn;
    private $table_name = "reservations";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create new reservation
    public function createReservation($name, $email, $phone, $date, $time, $guests, $special_requests = '') {
        // Check if the date/time slot is available
        if (!$this->isTimeSlotAvailable($date, $time)) {
            return ['success' => false, 'message' => 'This time slot is not available'];
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (name, email, phone, date, time, guests, special_requests) 
                  VALUES (:name, :email, :phone, :date, :time, :guests, :special_requests)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':guests', $guests);
        $stmt->bindParam(':special_requests', $special_requests);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Reservation created successfully', 'id' => $this->conn->lastInsertId()];
        } else {
            return ['success' => false, 'message' => 'Failed to create reservation'];
        }
    }

    // Check if time slot is available
    private function isTimeSlotAvailable($date, $time) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " 
                  WHERE date = :date AND time = :time AND status != 'cancelled'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['count'] < 3; // Allow maximum 3 reservations per time slot
    }

    // Get all reservations (admin only)
    public function getAllReservations($status = null) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if ($status) {
            $query .= " WHERE status = :status";
        }
        
        $query .= " ORDER BY date DESC, time DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get reservation by ID
    public function getReservationById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get reservations by email
    public function getReservationsByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email ORDER BY date DESC, time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update reservation status
    public function updateReservationStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    // Get available time slots for a date
    public function getAvailableTimeSlots($date) {
        $time_slots = [
            '11:00:00', '11:30:00', '12:00:00', '12:30:00', '13:00:00', '13:30:00',
            '18:00:00', '18:30:00', '19:00:00', '19:30:00', '20:00:00', '20:30:00', '21:00:00'
        ];

        $query = "SELECT time, COUNT(*) as count FROM " . $this->table_name . " 
                  WHERE date = :date AND status != 'cancelled' 
                  GROUP BY time";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        
        $booked_slots = $stmt->fetchAll();
        $booked_times = [];
        
        foreach ($booked_slots as $slot) {
            if ($slot['count'] >= 3) {
                $booked_times[] = $slot['time'];
            }
        }

        return array_diff($time_slots, $booked_times);
    }

    // Cancel reservation
    public function cancelReservation($id) {
        return $this->updateReservationStatus($id, 'cancelled');
    }

    // Confirm reservation
    public function confirmReservation($id) {
        return $this->updateReservationStatus($id, 'confirmed');
    }
}
?>