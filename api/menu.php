<?php
require_once '../config/database_sqlite.php';
require_once '../classes/MenuManager.php';

setCorsHeaders();

$menu_manager = new MenuManager();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get specific menu item
            $admin_view = isset($_GET['admin']) && $_GET['admin'] === 'true';
            $item = $menu_manager->getItemById($_GET['id'], $admin_view);
            if ($item) {
                sendJsonResponse($item);
            } else {
                sendJsonResponse(['error' => 'Menu item not found'], 404);
            }
        } elseif (isset($_GET['search'])) {
            // Search menu items
            $items = $menu_manager->searchItems($_GET['search']);
            sendJsonResponse($items);
        } elseif (isset($_GET['categories'])) {
            // Get all categories
            $categories = $menu_manager->getCategories();
            sendJsonResponse($categories);
        } else {
            // Get all menu items
            $category = isset($_GET['category']) ? $_GET['category'] : null;
            $admin_view = isset($_GET['admin']) && $_GET['admin'] === 'true';
            $items = $menu_manager->getAllItems($category, $admin_view);
            sendJsonResponse($items);
        }
        break;

    case 'POST':
        // Handle different POST actions
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Check if this is a sample data restoration request
        if (isset($input['action']) && $input['action'] === 'restore_sample_data') {
            $result = $menu_manager->restoreSampleData();
            if ($result) {
                sendJsonResponse(['message' => 'Sample data restored successfully'], 200);
            } else {
                sendJsonResponse(['error' => 'Failed to restore sample data'], 500);
            }
            break;
        }
        
        // Regular add new menu item (admin only)
        if (!isset($input['name']) || !isset($input['price']) || !isset($input['category'])) {
            sendJsonResponse(['error' => 'Missing required fields'], 400);
        }

        $result = $menu_manager->addItem(
            $input['name'],
            $input['description'] ?? '',
            $input['price'],
            $input['category'],
            $input['image_url'] ?? ''
        );

        if ($result) {
            sendJsonResponse(['message' => 'Menu item added successfully'], 201);
        } else {
            sendJsonResponse(['error' => 'Failed to add menu item'], 500);
        }
        break;

    case 'PUT':
        // Update menu item (admin only)
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            sendJsonResponse(['error' => 'Menu item ID is required'], 400);
        }

        $result = $menu_manager->updateItem(
            $input['id'],
            $input['name'] ?? null,
            $input['description'] ?? null,
            $input['price'] ?? null,
            $input['category'] ?? null,
            $input['image_url'] ?? null,
            $input['is_available'] ?? null
        );

        if ($result) {
            sendJsonResponse(['message' => 'Menu item updated successfully']);
        } else {
            sendJsonResponse(['error' => 'Failed to update menu item'], 500);
        }
        break;

    case 'DELETE':
        // Delete menu item (admin only)
        if (!isset($_GET['id'])) {
            sendJsonResponse(['error' => 'Menu item ID is required'], 400);
        }

        // Check if hard delete is requested
        $hardDelete = isset($_GET['hard']) && $_GET['hard'] === 'true';
        
        if ($hardDelete) {
            $result = $menu_manager->hardDeleteItem($_GET['id']);
        } else {
            $result = $menu_manager->deleteItem($_GET['id']);
        }

        if ($result) {
            sendJsonResponse(['message' => 'Menu item deleted successfully']);
        } else {
            sendJsonResponse(['error' => 'Failed to delete menu item'], 500);
        }
        break;

    default:
        sendJsonResponse(['error' => 'Method not allowed'], 405);
        break;
}
?>