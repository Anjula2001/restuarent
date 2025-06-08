<?php
require_once 'config/database.php';
require_once 'classes/MenuManager.php';

// Delete all menu items first
$pdo = new Database();
$conn = $pdo->getConnection();
$conn->exec("DELETE FROM menu_items");

// Now test what MenuManager returns
$menu_manager = new MenuManager();
$items = $menu_manager->getAllItems(null, true);

header('Content-Type: application/json');
echo json_encode($items);
?>
