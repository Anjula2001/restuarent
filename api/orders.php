<?php
require_once '../config/database_sqlite.php';
require_once '../classes/OrderManager.php';

setCorsHeaders();

$order_manager = new OrderManager();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get specific order
            $order = $order_manager->getOrderById($_GET['id']);
            if ($order) {
                sendJsonResponse($order);
            } else {
                sendJsonResponse(['error' => 'Order not found'], 404);
            }
        } elseif (isset($_GET['email'])) {
            // Get orders by email
            $orders = $order_manager->getOrdersByEmail($_GET['email']);
            sendJsonResponse($orders);
        } elseif (isset($_GET['statistics'])) {
            // Get order statistics (admin only)
            $stats = $order_manager->getOrderStatistics();
            sendJsonResponse($stats);
        } else {
            // Get all orders (admin only)
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $orders = $order_manager->getAllOrders($status);
            sendJsonResponse($orders);
        }
        break;

    case 'POST':
        // Create new order
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        $required_fields = ['customer_name', 'customer_email', 'customer_phone', 'order_type', 'items'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                sendJsonResponse(['error' => "Field '$field' is required"], 400);
            }
        }

        // Validate order type
        if (!in_array($input['order_type'], ['delivery', 'pickup'])) {
            sendJsonResponse(['error' => 'Order type must be either "delivery" or "pickup"'], 400);
        }

        // Validate delivery address for delivery orders
        if ($input['order_type'] === 'delivery' && empty($input['delivery_address'])) {
            sendJsonResponse(['error' => 'Delivery address is required for delivery orders'], 400);
        }

        // Validate items
        if (!is_array($input['items']) || empty($input['items'])) {
            sendJsonResponse(['error' => 'At least one item is required'], 400);
        }

        foreach ($input['items'] as $item) {
            if (!isset($item['menu_item_id']) || !isset($item['quantity']) || !isset($item['price'])) {
                sendJsonResponse(['error' => 'Each item must have menu_item_id, quantity, and price'], 400);
            }
            if ($item['quantity'] <= 0) {
                sendJsonResponse(['error' => 'Item quantity must be greater than 0'], 400);
            }
        }

        $result = $order_manager->createOrder(
            sanitizeInput($input['customer_name']),
            sanitizeInput($input['customer_email']),
            sanitizeInput($input['customer_phone']),
            sanitizeInput($input['delivery_address'] ?? ''),
            $input['order_type'],
            $input['items']
        );

        if ($result['success']) {
            sendJsonResponse($result, 201);
        } else {
            sendJsonResponse(['error' => $result['message']], 400);
        }
        break;

    case 'PUT':
        // Update order status (admin only)
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id']) || !isset($input['status'])) {
            sendJsonResponse(['error' => 'Order ID and status are required'], 400);
        }

        $valid_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
        if (!in_array($input['status'], $valid_statuses)) {
            sendJsonResponse(['error' => 'Invalid status'], 400);
        }

        $result = $order_manager->updateOrderStatus($input['id'], $input['status']);

        if ($result) {
            sendJsonResponse(['message' => 'Order status updated successfully']);
        } else {
            sendJsonResponse(['error' => 'Failed to update order status'], 500);
        }
        break;

    case 'DELETE':
        // Check if this is a bulk clear operation
        if (isset($_GET['clear_processed']) && $_GET['clear_processed'] === 'true') {
            // Clear all delivered and cancelled orders (admin only)
            $counts = $order_manager->getOrderCountByStatus();
            $toDelete = ($counts['delivered'] ?? 0) + ($counts['cancelled'] ?? 0);
            
            if ($toDelete === 0) {
                sendJsonResponse(['message' => 'No delivered or cancelled orders to clear']);
                break;
            }
            
            $result = $order_manager->clearProcessedOrders();

            if ($result) {
                sendJsonResponse([
                    'message' => "Successfully cleared {$toDelete} processed orders",
                    'cleared_count' => $toDelete
                ]);
            } else {
                sendJsonResponse(['error' => 'Failed to clear orders'], 500);
            }
        } else {
            sendJsonResponse(['error' => 'Operation not supported'], 400);
        }
        break;

    default:
        sendJsonResponse(['error' => 'Method not allowed'], 405);
        break;
}
?>