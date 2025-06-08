-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 09:19 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grand_restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@grandrestaurant.com', '2025-06-02 05:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `initialization_marker`
--

CREATE TABLE `initialization_marker` (
  `id` int(11) NOT NULL,
  `initialized_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `initialization_marker`
--

INSERT INTO `initialization_marker` (`id`, `initialized_at`) VALUES
(1, '2025-06-08 09:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `category`, `image_url`, `is_available`, `created_at`, `updated_at`) VALUES
(86, 'Fish Curry with Basmati Rice', 'Tender fish in a rich, spicy coconut curry, served with fluffy Basmati rice.', '300.00', 'Rice & Curry', 'uploads/menu-items/menu_item_6845c005d6276_1749401605.jpg', 1, '2025-06-08 16:53:29', '2025-06-08 16:53:29'),
(87, 'Chicken Curry with Basmati Rice', 'Homestyle Sri Lankan Chicken Curry with Basmati Rice', '350.00', 'Rice & Curry', 'uploads/menu-items/menu_item_6845c05ef32b1_1749401694.jpg', 1, '2025-06-08 16:54:57', '2025-06-08 16:54:57'),
(88, 'Rice with Vegetable Curries', 'raditional Sri Lankan Vegetable Rice & Curry', '250.00', 'Rice & Curry', 'uploads/menu-items/menu_item_6845c0afc01e1_1749401775.jpg', 1, '2025-06-08 16:56:19', '2025-06-08 16:56:19'),
(89, 'Mixed Fried Rice', 'A hearty mix of rice, chicken, egg, and vegetables, wok-fried to perfection.', '650.00', 'Chinese', 'uploads/menu-items/menu_item_6845c1b5ad258_1749402037.jpg', 1, '2025-06-08 17:00:40', '2025-06-08 17:00:40'),
(90, 'Egg Fried Rice', ' Fluffy rice wok-fried with scrambled egg, fresh vegetables, and a savory seasoning blend.', '450.00', 'Chinese', 'uploads/menu-items/menu_item_6845c1f04dc51_1749402096.jpg', 1, '2025-06-08 17:01:42', '2025-06-08 17:01:42'),
(91, 'Chicken Fried Rice', 'Savory fried rice with tender chicken, garden vegetables, and a light soy seasoning.', '500.00', 'Chinese', 'uploads/menu-items/menu_item_6845c23a668ed_1749402170.jpg', 1, '2025-06-08 17:02:52', '2025-06-08 17:02:52'),
(92, 'Creamy Seafood Linguine', ' Linguine tossed with prawns, calamari, mussels in a rich garlic cream sauce.', '1200.00', 'Seafood', 'uploads/menu-items/menu_item_6845c52a302d0_1749402922.jpg', 1, '2025-06-08 17:16:14', '2025-06-08 17:16:14'),
(93, 'Seafood Fried Rice', 'Aromatic fried rice with a medley of fresh prawns, calamari, and selected vegetables.', '1200.00', 'Seafood', 'uploads/menu-items/menu_item_6845c5648f10b_1749402980.jpg', 1, '2025-06-08 17:17:04', '2025-06-08 17:17:04'),
(94, 'Grilled Seafood Platter', 'A delectable assortment of grilled prawns, calamari, and fish, served with lemon butter.', '2400.00', 'Seafood', 'uploads/menu-items/menu_item_6845c5aca6c3d_1749403052.jpg', 1, '2025-06-08 17:18:24', '2025-06-08 17:18:24'),
(95, 'Rich Chocolate Milkshake', 'Indulgent blend of premium chocolate ice cream and fresh milk, thick and creamy.', '400.00', 'Juices', 'uploads/menu-items/menu_item_6845d17a83e37_1749406074.png', 1, '2025-06-08 18:09:27', '2025-06-08 18:09:27'),
(96, 'Classic Virgin Mojito', 'Refreshing blend of fresh mint, lime, sugar, and soda, muddled to perfection.', '600.00', 'Juices', 'uploads/menu-items/menu_item_6845d21403770_1749406228.png', 1, '2025-06-08 18:10:37', '2025-06-08 18:10:37'),
(97, 'Falooda', 'A sweet, refreshing drink with rose syrup, vermicelli, basil seeds, jelly, and ice cream.', '350.00', 'Juices', 'uploads/menu-items/menu_item_6845d2292a2df_1749406249.png', 1, '2025-06-08 18:11:38', '2025-06-08 18:11:38'),
(98, 'Cheesy Chicken Kottu', 'Shredded roti mixed with chicken, vegetables, egg, and melted cheese, griddled perfectly.', '900.00', 'Kottu', 'uploads/menu-items/menu_item_6845d9144bd51_1749408020.jpg', 1, '2025-06-08 18:19:07', '2025-06-08 18:41:49'),
(99, 'Classic Egg Kottu', 'Shredded godamba roti stir-fried with scrambled egg, fresh vegetables, and savory spices.', '650.00', 'Kottu', 'uploads/menu-items/menu_item_6845d4261fcb7_1749406758.png', 1, '2025-06-08 18:20:10', '2025-06-08 18:20:10'),
(100, 'Chicken Kottu', 'Shredded godamba roti stir-fried with tender chicken, fresh vegetables, and savory spices.', '700.00', 'Kottu', 'uploads/menu-items/menu_item_6845d46928ea5_1749406825.png', 1, '2025-06-08 18:20:48', '2025-06-08 18:20:48'),
(101, 'Ice Cream', 'Creamy, delightful scoop of premium ice cream available in various classic and seasonal flavors.', '250.00', 'Desserts', 'uploads/menu-items/menu_item_6845d57092612_1749407088.png', 1, '2025-06-08 18:25:48', '2025-06-08 18:25:48'),
(102, 'Fresh Fruit Salad', 'A vibrant mix of seasonal fresh fruits, diced and lightly tossed.', '200.00', 'Desserts', 'uploads/menu-items/menu_item_6845d5b2d108a_1749407154.png', 1, '2025-06-08 18:26:33', '2025-06-08 18:26:33'),
(103, 'Chocolate Lava Cake', 'Warm, rich chocolate cake with a molten chocolate center, served with a dusting of cocoa.', '400.00', 'Desserts', 'uploads/menu-items/menu_item_6845d5e184e9d_1749407201.png', 1, '2025-06-08 18:27:07', '2025-06-08 18:27:07'),
(104, 'Indonesian Nasi Goreng', 'Spicy fried rice with chicken, shrimp, egg, and vegetables, topped with a fried egg.', '900.00', 'Other Specialties', 'uploads/menu-items/menu_item_6845de16a8b77_1749409302.png', 1, '2025-06-08 18:31:14', '2025-06-08 19:01:45'),
(105, 'Chicken Biriyani', 'Fragrant Basmati rice cooked with tender chicken, aromatic spices, and caramelized onions.', '800.00', 'Other Specialties', 'uploads/menu-items/menu_item_6845d6ff6a95d_1749407487.png', 1, '2025-06-08 18:32:08', '2025-06-08 18:32:08'),
(106, 'Savory Chicken & Vegetable Noodles', 'Wok-tossed noodles with tender chicken, an assortment of fresh vegetables, and savory sauce.', '600.00', 'Other Specialties', 'uploads/menu-items/menu_item_6845d731416c6_1749407537.png', 1, '2025-06-08 18:32:48', '2025-06-08 18:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `order_type` varchar(20) DEFAULT 'delivery',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `total_amount`, `status`, `order_type`, `created_at`, `updated_at`) VALUES
(42, 'anjula', 'prasadanjula1@gmail.com', '(041) 251-6896', '', '800.00', 'preparing', 'pickup', '2025-06-08 19:00:00', '2025-06-08 19:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_item_id`, `quantity`, `price`) VALUES
(25, 42, 87, 1, '350.00'),
(26, 42, 90, 1, '450.00');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `party_size` int(11) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `customer_name`, `customer_email`, `customer_phone`, `reservation_date`, `reservation_time`, `party_size`, `special_requests`, `status`, `created_at`, `updated_at`) VALUES
(3, 'anjula prasad', 'prasadanjula1@gmail.com', '0771950486', '2025-06-18', '19:00:00', 4, '', 'confirmed', '2025-06-08 18:57:41', '2025-06-08 18:59:11'),
(4, 'Amila pathum', 'prasadanjula1@gmail.com', '0771950486', '2025-06-10', '19:00:00', 4, '', 'confirmed', '2025-06-08 19:17:14', '2025-06-08 19:17:25');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `customer_name`, `customer_email`, `rating`, `review_text`, `is_approved`, `created_at`) VALUES
(1, 'Amaya Perera', 'amaya.perera@example.com', 5, 'Absolutely loved the food and ambiance! The service was top-notch. Will definitely visit again.', 1, '2025-06-08 19:06:03'),
(2, 'Kamal Jayasinghe', 'kamalj@example.com', 4, 'The dishes were tasty and well presented. Just wish the portions were slightly bigger.', 1, '2025-06-08 19:06:03'),
(3, 'Nadeesha Silva', 'nadeesha.silva@example.com', 3, 'Good overall experience. However, the waiting time was a bit long.', 1, '2025-06-08 19:06:03'),
(4, 'Tharindu Fernando', 'tharindu.fernando@example.com', 2, 'Food was average and our server forgot one of our orders. Hope they improve.', 1, '2025-06-08 19:06:03'),
(5, 'Ishara Gunasekara', 'ishara.g@example.com', 1, 'Very disappointing experience. The food was cold and lacked flavor.', 1, '2025-06-08 19:06:03'),
(6, 'Mevan Rajapaksha', 'mevanr@example.com', 5, 'Excellent restaurant! The chef deserves a special mention for the unique flavors.', 1, '2025-06-08 19:06:03'),
(7, 'Dinithi Alwis', 'dinithi.alwis@example.com', 4, 'Great ambiance and friendly staff. Prices are reasonable too.', 1, '2025-06-08 19:06:03'),
(8, 'Yohan Dissanayake', 'yohan.d@example.com', 3, 'It was okay. Nothing stood out, but not bad either.', 1, '2025-06-08 19:06:03'),
(9, 'Ruwani Samarasinghe', 'ruwani.s@example.com', 5, 'Loved everything about this place. Highly recommend!', 1, '2025-06-08 19:06:03'),
(10, 'Sahan Wickramasinghe', 'sahan.w@example.com', 4, 'Very good food and fast service. A little noisy though.', 1, '2025-06-08 19:06:03'),
(11, 'Amila Pathum', '', 4, 'Loved the spicy chicken curry. Great portions and fair pricing.', 1, '2025-06-08 19:08:06'),
(12, 'Anjula Prasad', '', 5, 'Impressed with the fast delivery and neatly packed food. Everything was still hot and fresh!', 1, '2025-06-08 19:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password_hash`, `address`, `date_of_birth`, `email_verified`, `is_active`, `created_at`, `updated_at`, `last_login`) VALUES
(8, 'anjula', 'prasad', 'prasadanjula1@gmail.com', '0771950486', '$2y$10$aiToNJMJ7iX.o58hL0GOpuXI.RXYZRPi91JqcUGNplALlcM0E8QvG', '70000\n70000', '2001-11-03', 0, 1, '2025-06-08 09:18:32', '2025-06-08 19:17:50', '2025-06-08 19:17:50'),
(11, 'amila', 'pathum', 'amila@gmail.com', '(041) 251-6896', '$2y$10$NhdSxaeeQKINEImc6TCo.eqvizgZs0vHDdgG67tmQe4.xQ/6I7x7W', '', '2025-06-05', 0, 1, '2025-06-08 15:36:54', '2025-06-08 19:18:07', '2025-06-08 19:18:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `initialization_marker`
--
ALTER TABLE `initialization_marker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `initialization_marker`
--
ALTER TABLE `initialization_marker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
