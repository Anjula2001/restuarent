<?php
require_once '../config/database_sqlite.php';

class OrderManager {
    private $conn;
    private $orders_table = "orders";
    private $order_items_table = "order_items";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create new order
    public function createOrder($customer_name, $customer_email, $customer_phone, $delivery_address, $order_type, $items) {
        try {
            $this->conn->beginTransaction();

            // Calculate total amount
            $total_amount = 0;
            foreach ($items as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }

            // Insert order
            $query = "INSERT INTO " . $this->orders_table . " 
                      (customer_name, customer_email, customer_phone, delivery_address, order_type, total_amount) 
                      VALUES (:customer_name, :customer_email, :customer_phone, :delivery_address, :order_type, :total_amount)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':customer_name', $customer_name);
            $stmt->bindParam(':customer_email', $customer_email);
            $stmt->bindParam(':customer_phone', $customer_phone);
            $stmt->bindParam(':delivery_address', $delivery_address);
            $stmt->bindParam(':order_type', $order_type);
            $stmt->bindParam(':total_amount', $total_amount);
            
            $stmt->execute();
            $order_id = $this->conn->lastInsertId();

            // Insert order items
            $item_query = "INSERT INTO " . $this->order_items_table . " 
                          (order_id, menu_item_id, quantity, price) 
                          VALUES (:order_id, :menu_item_id, :quantity, :price)";
            
            $item_stmt = $this->conn->prepare($item_query);
            
            foreach ($items as $item) {
                $item_stmt->bindParam(':order_id', $order_id);
                $item_stmt->bindParam(':menu_item_id', $item['menu_item_id']);
                $item_stmt->bindParam(':quantity', $item['quantity']);
                $item_stmt->bindParam(':price', $item['price']);
                $item_stmt->execute();
            }

            $this->conn->commit();
            return ['success' => true, 'message' => 'Order created successfully', 'order_id' => $order_id];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Failed to create order: ' . $e->getMessage()];
        }
    }

    // Get all orders (admin only)
    public function getAllOrders($status = null) {
        $query = "SELECT o.*, 
                         GROUP_CONCAT(mi.name || ' (' || oi.quantity || ')', ', ') as items
                  FROM " . $this->orders_table . " o
                  LEFT JOIN " . $this->order_items_table . " oi ON o.id = oi.order_id
                  LEFT JOIN menu_items mi ON oi.menu_item_id = mi.id";
        
        if ($status) {
            $query .= " WHERE o.status = :status";
        }
        
        $query .= " GROUP BY o.id ORDER BY o.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get order by ID
    public function getOrderById($id) {
        $query = "SELECT o.*, 
                         oi.quantity, oi.price as item_price,
                         mi.name as item_name, mi.description as item_description
                  FROM " . $this->orders_table . " o
                  LEFT JOIN " . $this->order_items_table . " oi ON o.id = oi.order_id
                  LEFT JOIN menu_items mi ON oi.menu_item_id = mi.id
                  WHERE o.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        
        if (empty($results)) {
            return null;
        }

        // Structure the data
        $order = [
            'id' => $results[0]['id'],
            'customer_name' => $results[0]['customer_name'],
            'customer_email' => $results[0]['customer_email'],
            'customer_phone' => $results[0]['customer_phone'],
            'delivery_address' => $results[0]['delivery_address'],
            'order_type' => $results[0]['order_type'],
            'total_amount' => $results[0]['total_amount'],
            'status' => $results[0]['status'],
            'created_at' => $results[0]['created_at'],
            'items' => []
        ];

        foreach ($results as $row) {
            if ($row['item_name']) {
                $order['items'][] = [
                    'name' => $row['item_name'],
                    'description' => $row['item_description'],
                    'quantity' => $row['quantity'],
                    'price' => $row['item_price']
                ];
            }
        }

        return $order;
    }

    // Get orders by email
    public function getOrdersByEmail($email) {
        $query = "SELECT o.*, 
                         GROUP_CONCAT(CONCAT(mi.name, ' (', oi.quantity, ')') SEPARATOR ', ') as items
                  FROM " . $this->orders_table . " o
                  LEFT JOIN " . $this->order_items_table . " oi ON o.id = oi.order_id
                  LEFT JOIN menu_items mi ON oi.menu_item_id = mi.id
                  WHERE o.customer_email = :email
                  GROUP BY o.id ORDER BY o.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update order status
    public function updateOrderStatus($id, $status) {
        $query = "UPDATE " . $this->orders_table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    // Get order statistics (admin only)
    public function getOrderStatistics() {
        $stats = [];

        // Total orders today
        $query = "SELECT COUNT(*) as total FROM " . $this->orders_table . " WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['today'] = $stmt->fetch()['total'];

        // Pending orders
        $query = "SELECT COUNT(*) as total FROM " . $this->orders_table . " WHERE status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['pending'] = $stmt->fetch()['total'];

        // Revenue today
        $query = "SELECT COALESCE(SUM(total_amount), 0) as revenue FROM " . $this->orders_table . " 
                  WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['revenue_today'] = $stmt->fetch()['revenue'];

        return $stats;
    }

    // Bulk delete delivered and cancelled orders
    public function clearProcessedOrders() {
        $query = "DELETE FROM " . $this->orders_table . " WHERE status IN ('delivered', 'cancelled')";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // Get count of orders by status
    public function getOrderCountByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->orders_table . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $counts = [];
        $results = $stmt->fetchAll();
        foreach ($results as $result) {
            $counts[$result['status']] = $result['count'];
        }
        
        return $counts;
    }
}
?>