<?php
require_once '../config/database_sqlite.php';
require_once '../classes/ReservationManager.php';

setCorsHeaders();

$reservation_manager = new ReservationManager();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get specific reservation
            $reservation = $reservation_manager->getReservationById($_GET['id']);
            if ($reservation) {
                sendJsonResponse($reservation);
            } else {
                sendJsonResponse(['error' => 'Reservation not found'], 404);
            }
        } elseif (isset($_GET['email'])) {
            // Get reservations by email
            $reservations = $reservation_manager->getReservationsByEmail($_GET['email']);
            sendJsonResponse($reservations);
        } elseif (isset($_GET['available_slots'])) {
            // Get available time slots for a date
            if (!isset($_GET['date'])) {
                sendJsonResponse(['error' => 'Date is required'], 400);
            }
            $slots = $reservation_manager->getAvailableTimeSlots($_GET['date']);
            sendJsonResponse($slots);
        } else {
            // Get all reservations (admin only)
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $reservations = $reservation_manager->getAllReservations($status);
            sendJsonResponse($reservations);
        }
        break;

    case 'POST':
        // Create new reservation
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        $required_fields = ['name', 'email', 'phone', 'date', 'time', 'guests'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                sendJsonResponse(['error' => "Field '$field' is required"], 400);
            }
        }

        // Validate date format
        $date = DateTime::createFromFormat('Y-m-d', $input['date']);
        if (!$date || $date->format('Y-m-d') !== $input['date']) {
            sendJsonResponse(['error' => 'Invalid date format. Use YYYY-MM-DD'], 400);
        }

        // Validate time format
        $time = DateTime::createFromFormat('H:i', $input['time']);
        if (!$time || $time->format('H:i') !== $input['time']) {
            sendJsonResponse(['error' => 'Invalid time format. Use HH:MM'], 400);
        }

        // Check if date is not in the past
        $today = new DateTime();
        if ($date < $today->setTime(0, 0, 0)) {
            sendJsonResponse(['error' => 'Cannot make reservations for past dates'], 400);
        }

        // Validate number of guests
        if ($input['guests'] < 1 || $input['guests'] > 12) {
            sendJsonResponse(['error' => 'Number of guests must be between 1 and 12'], 400);
        }

        $result = $reservation_manager->createReservation(
            sanitizeInput($input['name']),
            sanitizeInput($input['email']),
            sanitizeInput($input['phone']),
            $input['date'],
            $input['time'] . ':00', // Add seconds
            $input['guests'],
            sanitizeInput($input['special_requests'] ?? '')
        );

        if ($result['success']) {
            sendJsonResponse($result, 201);
        } else {
            sendJsonResponse(['error' => $result['message']], 400);
        }
        break;

    case 'PUT':
        // Update reservation status (admin only)
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id']) || !isset($input['status'])) {
            sendJsonResponse(['error' => 'Reservation ID and status are required'], 400);
        }

        $valid_statuses = ['pending', 'confirmed', 'cancelled'];
        if (!in_array($input['status'], $valid_statuses)) {
            sendJsonResponse(['error' => 'Invalid status'], 400);
        }

        $result = $reservation_manager->updateReservationStatus($input['id'], $input['status']);

        if ($result) {
            sendJsonResponse(['message' => 'Reservation status updated successfully']);
        } else {
            sendJsonResponse(['error' => 'Failed to update reservation status'], 500);
        }
        break;

    case 'DELETE':
        // Check if this is a bulk clear operation
        if (isset($_GET['clear_processed']) && $_GET['clear_processed'] === 'true') {
            // Clear all cancelled and confirmed reservations (admin only)
            $counts = $reservation_manager->getReservationCountByStatus();
            $toDelete = ($counts['cancelled'] ?? 0) + ($counts['confirmed'] ?? 0);
            
            if ($toDelete === 0) {
                sendJsonResponse(['message' => 'No cancelled or confirmed reservations to clear']);
                break;
            }
            
            $result = $reservation_manager->clearProcessedReservations();

            if ($result) {
                sendJsonResponse([
                    'message' => "Successfully cleared {$toDelete} processed reservations",
                    'cleared_count' => $toDelete
                ]);
            } else {
                sendJsonResponse(['error' => 'Failed to clear reservations'], 500);
            }
        } else {
            // Cancel single reservation
            if (!isset($_GET['id'])) {
                sendJsonResponse(['error' => 'Reservation ID is required'], 400);
            }

            $result = $reservation_manager->cancelReservation($_GET['id']);

            if ($result) {
                sendJsonResponse(['message' => 'Reservation cancelled successfully']);
            } else {
                sendJsonResponse(['error' => 'Failed to cancel reservation'], 500);
            }
        }
        break;

    default:
        sendJsonResponse(['error' => 'Method not allowed'], 405);
        break;
}
?>