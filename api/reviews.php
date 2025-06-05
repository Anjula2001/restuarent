<?php
require_once '../config/database_sqlite.php';
require_once '../classes/ReviewManager.php';

setCorsHeaders();

$review_manager = new ReviewManager();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get specific review
            $review = $review_manager->getReviewById($_GET['id']);
            if ($review) {
                sendJsonResponse($review);
            } else {
                sendJsonResponse(['error' => 'Review not found'], 404);
            }
        } elseif (isset($_GET['email'])) {
            // Get reviews by email
            $reviews = $review_manager->getReviewsByEmail($_GET['email']);
            sendJsonResponse($reviews);
        } elseif (isset($_GET['statistics'])) {
            // Get review statistics
            $stats = $review_manager->getReviewStatistics();
            sendJsonResponse($stats);
        } elseif (isset($_GET['admin'])) {
            // Get all reviews for admin
            $approved = isset($_GET['approved']) ? (bool)$_GET['approved'] : null;
            $reviews = $review_manager->getAllReviews($approved);
            sendJsonResponse($reviews);
        } else {
            // Get approved reviews for public display
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
            $reviews = $review_manager->getApprovedReviews($limit);
            sendJsonResponse($reviews);
        }
        break;

    case 'POST':
        // Add new review
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        $required_fields = ['customer_name', 'rating', 'review_text'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                sendJsonResponse(['error' => "Field '$field' is required"], 400);
            }
        }

        // Validate rating
        if ($input['rating'] < 1 || $input['rating'] > 5) {
            sendJsonResponse(['error' => 'Rating must be between 1 and 5'], 400);
        }

        // Validate review text length
        if (strlen($input['review_text']) < 10) {
            sendJsonResponse(['error' => 'Review text must be at least 10 characters long'], 400);
        }

        $result = $review_manager->addReview(
            sanitizeInput($input['customer_name']),
            sanitizeInput($input['customer_email'] ?? ''),
            $input['rating'],
            sanitizeInput($input['review_text'])
        );

        if ($result['success']) {
            sendJsonResponse($result, 201);
        } else {
            sendJsonResponse(['error' => $result['message']], 400);
        }
        break;

    case 'PUT':
        // Update review approval status (admin only)
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id']) || !isset($input['action'])) {
            sendJsonResponse(['error' => 'Review ID and action are required'], 400);
        }

        $result = false;
        switch ($input['action']) {
            case 'approve':
                $result = $review_manager->approveReview($input['id']);
                $message = 'Review approved successfully';
                break;
            case 'reject':
                $result = $review_manager->rejectReview($input['id']);
                $message = 'Review rejected successfully';
                break;
            default:
                sendJsonResponse(['error' => 'Invalid action. Use "approve" or "reject"'], 400);
        }

        if ($result) {
            sendJsonResponse(['message' => $message]);
        } else {
            sendJsonResponse(['error' => 'Failed to update review status'], 500);
        }
        break;

    case 'DELETE':
        // Delete review (admin only)
        if (!isset($_GET['id'])) {
            sendJsonResponse(['error' => 'Review ID is required'], 400);
        }

        $result = $review_manager->deleteReview($_GET['id']);

        if ($result) {
            sendJsonResponse(['message' => 'Review deleted successfully']);
        } else {
            sendJsonResponse(['error' => 'Failed to delete review'], 500);
        }
        break;

    default:
        sendJsonResponse(['error' => 'Method not allowed'], 405);
        break;
}
?>