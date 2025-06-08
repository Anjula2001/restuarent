<?php
// Test endpoint that simulates an empty database
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Always return empty array to simulate empty database
echo json_encode([]);
?>
