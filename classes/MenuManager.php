<?php
require_once '../config/database_sqlite.php';

class MenuManager {
    private $conn;
    private $table_name = "menu_items";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all menu items
    public function getAllItems($category = null, $admin_view = false) {
        $query = "SELECT * FROM " . $this->table_name;
        
        // For admin view, show all items. For public view, only show available items
        if (!$admin_view) {
            $query .= " WHERE is_available = 1";
        }
        
        if ($category) {
            $query .= $admin_view ? " WHERE category = :category" : " AND category = :category";
        }
        
        $query .= " ORDER BY category, name";
        
        $stmt = $this->conn->prepare($query);
        
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get menu item by ID
    public function getItemById($id, $admin_view = false) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        if (!$admin_view) {
            $query .= " AND is_available = 1";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get all categories
    public function getCategories() {
        $query = "SELECT DISTINCT category FROM " . $this->table_name . " WHERE is_available = 1 ORDER BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Add new menu item (admin only)
    public function addItem($name, $description, $price, $category, $image_url = '') {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, description, price, category, image_url) 
                  VALUES (:name, :description, :price, :category, :image_url)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image_url', $image_url);
        
        return $stmt->execute();
    }

    // Update menu item (admin only)
    public function updateItem($id, $name = null, $description = null, $price = null, $category = null, $image_url = null, $is_available = null) {
        // First get the current item to keep existing values for null parameters
        $current = $this->getItemById($id, true); // Use admin view
        if (!$current) {
            return false;
        }

        // Use current values for null parameters
        $final_name = $name ?? $current['name'];
        $final_description = $description ?? $current['description'];
        $final_price = $price ?? $current['price'];
        $final_category = $category ?? $current['category'];
        $final_image_url = $image_url ?? $current['image_url'];
        $final_is_available = $is_available ?? $current['is_available'];

        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, description = :description, price = :price, 
                      category = :category, image_url = :image_url, is_available = :is_available
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $final_name);
        $stmt->bindParam(':description', $final_description);
        $stmt->bindParam(':price', $final_price);
        $stmt->bindParam(':category', $final_category);
        $stmt->bindParam(':image_url', $final_image_url);
        $stmt->bindParam(':is_available', $final_is_available);
        
        return $stmt->execute();
    }

    // Soft delete menu item (admin only) - sets is_available to 0
    public function deleteItem($id) {
        $query = "UPDATE " . $this->table_name . " SET is_available = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Hard delete menu item (admin only) - completely removes from database
    public function hardDeleteItem($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Search menu items
    public function searchItems($search_term) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_available = 1 AND (name LIKE :search OR description LIKE :search)
                  ORDER BY name";
        
        $stmt = $this->conn->prepare($query);
        $search_param = '%' . $search_term . '%';
        $stmt->bindParam(':search', $search_param);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Manually restore sample data (for admin use)
    public function restoreSampleData() {
        try {
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
            ";
            
            $this->conn->exec($sql);
            return true;
        } catch(PDOException $e) {
            error_log("Sample data restoration error: " . $e->getMessage());
            return false;
        }
    }

    // Get popular menu items (public view)
    public function getPopularItems($limit = 3) {
        // Get diverse popular items from different categories
        // First, try to get the highest-priced item from each category
        $query = "
            SELECT DISTINCT m1.id, m1.name, m1.description, m1.price, m1.category, m1.image_url
            FROM " . $this->table_name . " m1
            WHERE m1.is_available = 1
            AND m1.price = (
                SELECT MAX(m2.price) 
                FROM " . $this->table_name . " m2 
                WHERE m2.category = m1.category AND m2.is_available = 1
            )
            ORDER BY m1.price DESC, m1.category
            LIMIT :limit
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();
        
        // If we don't get enough items from category-based selection, 
        // fill the rest with highest-priced available items
        if (count($items) < $limit) {
            $existing_ids = array_column($items, 'id');
            $remaining_limit = $limit - count($items);
            
            $remaining_query = "SELECT id, name, description, price, category, image_url 
                               FROM " . $this->table_name . " 
                               WHERE is_available = 1";
            
            if (!empty($existing_ids)) {
                $placeholders = str_repeat('?,', count($existing_ids) - 1) . '?';
                $remaining_query .= " AND id NOT IN (" . $placeholders . ")";
            }
            
            $remaining_query .= " ORDER BY price DESC LIMIT " . $remaining_limit;
            
            $stmt = $this->conn->prepare($remaining_query);
            if (!empty($existing_ids)) {
                $stmt->execute($existing_ids);
            } else {
                $stmt->execute();
            }
            
            $additional_items = $stmt->fetchAll();
            $items = array_merge($items, $additional_items);
        }
        
        return $items;
    }
}
?>