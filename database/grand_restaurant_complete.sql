-- Grand Restaurant Complete Database (SQLite)
-- This file contains the complete database structure and all existing data
-- Compatible with MAMP (Mac), XAMPP (Windows), and LAMP (Linux)
-- 
-- To restore this database:
-- 1. Delete existing database: rm database/grand_restaurant.db
-- 2. Create new database: sqlite3 database/grand_restaurant.db < database/grand_restaurant_complete.sql
-- 
-- Or use the setup script: php setup.php

PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;

-- Initialization marker to track database setup
CREATE TABLE IF NOT EXISTS initialization_marker (
    initialized_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu Items Table
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

-- Insert existing menu items with all your current data
INSERT OR IGNORE INTO menu_items VALUES(59,'Test Rice Curry','A delicious test item',750,'Rice & Curry','images/popular/1.png',0,'2025-06-05 19:52:48','2025-06-05 19:52:48');
INSERT OR IGNORE INTO menu_items VALUES(60,'Sweet and Sour Chicken','Chinese style chicken',950,'Chinese','images/popular/2.png',1,'2025-06-05 19:52:58','2025-06-05 19:52:58');
INSERT OR IGNORE INTO menu_items VALUES(61,'Special Fusion Dish','Our chef special creation',1200,'Other Specialties','images/popular/3.png',1,'2025-06-05 19:53:06','2025-06-05 19:53:06');
INSERT OR IGNORE INTO menu_items VALUES(64,'Seafood Platter','Fresh mixed seafood with rice and vegetables',28.99,'Seafood','https://via.placeholder.com/300x200?text=Seafood+Platter',1,'2025-06-05 20:08:06','2025-06-05 20:08:06');
INSERT OR IGNORE INTO menu_items VALUES(65,'Chicken Kottu','Traditional Sri Lankan street food with chicken and vegetables',15.99,'Kottu','https://via.placeholder.com/300x200?text=Chicken+Kottu',1,'2025-06-05 20:08:14','2025-06-05 20:08:14');
INSERT OR IGNORE INTO menu_items VALUES(67,'Fresh Orange Juice','Freshly squeezed orange juice',4.99,'Juices','https://via.placeholder.com/300x200?text=Orange+Juice',1,'2025-06-05 20:08:27','2025-06-05 20:08:27');
INSERT OR IGNORE INTO menu_items VALUES(68,'Watalappan','Traditional Sri Lankan coconut custard dessert',6.99,'Desserts','https://via.placeholder.com/300x200?text=Watalappan',1,'2025-06-05 20:08:35','2025-06-05 20:08:35');
INSERT OR IGNORE INTO menu_items VALUES(69,'Traditional Rice & Curry','A hearty plate of steamed rice served with a variety of traditional curries including dhal, vegetable curry, and chicken curry',12.99,'Rice & Curry','/images/rice-curry.jpg',1,'2025-06-05 22:01:25','2025-06-05 22:01:25');

-- Additional sample menu items for new installations
INSERT OR IGNORE INTO menu_items (name, description, price, category, image_url) VALUES
('Sri Lankan String Hoppers', 'Traditional Sri Lankan breakfast made from rice flour, served with coconut sambol and curry', 450.00, 'Traditional', 'images/popular/1.png'),
('Seafood Fried Rice', 'Fragrant rice with prawns, calamari, and crab meat', 750.00, 'Rice & Curry', 'images/popular/3.png'),
('Mutton Biryani', 'Aromatic basmati rice cooked with tender mutton and traditional spices', 850.00, 'Rice & Curry', 'images/popular/2.png'),
('Fish Kottu', 'Traditional kottu roti with fresh fish, vegetables, and aromatic spices', 650.00, 'Kottu', 'images/popular/1.png'),
('General Tso Chicken', 'Crispy chicken pieces in sweet and spicy sauce', 720.00, 'Chinese', 'images/popular/2.png');

-- Reservations Table
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

-- Orders Table
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

-- Insert existing order data
INSERT OR IGNORE INTO orders VALUES(18,'anjula prasad','prasadanjula1@gmail.com','0771950486','',1200,'pending','pickup','2025-06-07 19:16:10','2025-06-07 19:16:10');

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    menu_item_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

-- Insert existing order items data
INSERT OR IGNORE INTO order_items VALUES(14,18,61,1,1200);

-- Customer Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    is_approved BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert existing reviews (all your current reviews)
INSERT OR IGNORE INTO reviews VALUES(17,'Test User','test@example.com',5,'This is a test review with more than 10 characters to check validation.',1,'2025-06-07 08:33:00');
INSERT OR IGNORE INTO reviews VALUES(18,'Test User','test@example.com',4,'This is a test review with sufficient length',1,'2025-06-07 08:40:24');
INSERT OR IGNORE INTO reviews VALUES(19,'madhawa','madhawa@example.com',3,'tharahai oyth ekk mn',1,'2025-06-07 08:42:32');
INSERT OR IGNORE INTO reviews VALUES(20,'dona','dona@example.com',2,'donthodi hodi hodi dannam',1,'2025-06-07 08:47:55');
INSERT OR IGNORE INTO reviews VALUES(21,'anjula prasad','anjula@example.com',4,'good food tase. rasai gdk',1,'2025-06-07 08:50:37');
INSERT OR IGNORE INTO reviews VALUES(22,'kaputa','kaputa@example.com',3,'kaak kaak kaak kaak',1,'2025-06-07 08:53:33');

-- Insert additional sample reviews for demo purposes
INSERT OR IGNORE INTO reviews (customer_name, customer_email, rating, review_text, is_approved) VALUES
('John Smith', 'john@email.com', 5, 'Amazing food and great service! The ribeye steak was perfectly cooked.', 1),
('Sarah Johnson', 'sarah@email.com', 4, 'Loved the atmosphere and the salmon was delicious. Will definitely come back!', 1),
('Mike Wilson', 'mike@email.com', 5, 'Best pizza in town! The margherita was authentic and fresh.', 1),
('Emily Davis', 'emily@email.com', 5, 'Great restaurant for special occasions. The lobster ravioli was outstanding.', 1);

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert existing admin user
INSERT OR IGNORE INTO admin_users VALUES(1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin@grandrestaurant.com','2025-06-02 11:16:15');

-- Customer Users Table
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

-- Insert existing user data
INSERT OR IGNORE INTO users VALUES(1,'MAMP','User','mampuser@example.com','1234567890','$2y$10$k9y08.g/JbLSfUsC9DjnQekLfZ97W/.G3iSTAL34.juaDIoMx7.iu','','','0','1','2025-06-02 11:16:22','2025-06-02 11:16:22','2025-06-02 11:16:22');
INSERT OR IGNORE INTO users VALUES(2,'anju','pu','anju@gmail.com','0909088787','$2y$10$LsR25lD36GehvDW94XHZGuZ4.GPyBGuPat4NrogBULUMyHGHjcjkq','asd,jhgd','2025-06-13','0','1','2025-06-02 11:19:59','2025-06-02 11:19:59','2025-06-02 11:30:07');
INSERT OR IGNORE INTO users VALUES(3,'Test','User','test@example.com','','$2y$12$BvhanhnSWiMTmGjcB5r7S.mXlcnpSBmhkuh4kqKfsSeUoSZQGKfRy','','','0','1','2025-06-02 22:48:19','2025-06-02 22:48:19','2025-06-02 22:48:19');
INSERT OR IGNORE INTO users VALUES(4,'Jane','Doe','jane@example.com','','$2y$12$WkX/PmgYKn4nsZPIprl6N.pY26BNkcp0OveG9erczzY8q6/7P8H4C','','','0','1','2025-06-02 22:49:08','2025-06-02 22:49:08','2025-06-02 22:49:09');
INSERT OR IGNORE INTO users VALUES(5,'Final','Test','finaltest@example.com','555-123-4567','$2y$12$j.FtMr2pLIHVcLs7IZpK2uhyJ./2LWqdjZihZyTKaXVy3eBCSJIDu','123 Test Street','','0','1','2025-06-02 22:51:08','2025-06-02 22:51:08','2025-06-02 22:51:14');
INSERT OR IGNORE INTO users VALUES(6,'anjula','prasad','prasadanjula1@gmail.com','0771950486','$2y$10$qo3DaUf2dmiTVBXWLvcZKOaAq2B5s/RHlMWKn0xgL25aeVAHeIXVG','70000','2001-11-03','0','0','2025-06-02 22:52:33','2025-06-02 23:00:54','2025-06-07 19:15:57');

-- Mark database as initialized
INSERT OR IGNORE INTO initialization_marker (initialized_at) VALUES (CURRENT_TIMESTAMP);

COMMIT;
