<?php
session_start();
require_once '../config/database.php';
require_once '../classes/UserManager.php';

setCorsHeaders();

$user_manager = new UserManager();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            sendJsonResponse(['error' => 'Invalid JSON input'], 400);
        }

        $action = isset($input['action']) ? $input['action'] : '';

        switch ($action) {
            case 'register':
                $required_fields = ['first_name', 'last_name', 'email', 'password'];
                $missing_fields = validateRequiredFields($input, $required_fields);
                
                if (!empty($missing_fields)) {
                    sendJsonResponse(['error' => 'Missing required fields: ' . implode(', ', $missing_fields)], 400);
                }

                $result = $user_manager->registerUser(
                    sanitizeInput($input['first_name']),
                    sanitizeInput($input['last_name']),
                    sanitizeInput($input['email']),
                    isset($input['phone']) ? sanitizeInput($input['phone']) : '',
                    $input['password'],
                    isset($input['address']) ? sanitizeInput($input['address']) : '',
                    isset($input['date_of_birth']) ? $input['date_of_birth'] : null
                );

                if ($result['success']) {
                    // Auto-login after successful registration
                    $login_result = $user_manager->loginUser($input['email'], $input['password']);
                    if ($login_result['success']) {
                        $_SESSION['user_id'] = $login_result['user']['id'];
                        $_SESSION['user_email'] = $login_result['user']['email'];
                        $_SESSION['user_name'] = $login_result['user']['first_name'] . ' ' . $login_result['user']['last_name'];
                        $result['user'] = $login_result['user'];
                    }
                }

                sendJsonResponse($result);
                break;

            case 'login':
                $required_fields = ['email', 'password'];
                $missing_fields = validateRequiredFields($input, $required_fields);
                
                if (!empty($missing_fields)) {
                    sendJsonResponse(['error' => 'Missing required fields: ' . implode(', ', $missing_fields)], 400);
                }

                $result = $user_manager->loginUser(
                    sanitizeInput($input['email']),
                    $input['password']
                );

                if ($result['success']) {
                    $_SESSION['user_id'] = $result['user']['id'];
                    $_SESSION['user_email'] = $result['user']['email'];
                    $_SESSION['user_name'] = $result['user']['first_name'] . ' ' . $result['user']['last_name'];
                }

                sendJsonResponse($result);
                break;

            case 'logout':
                session_destroy();
                sendJsonResponse(['success' => true, 'message' => 'Logged out successfully']);
                break;

            case 'update_profile':
                if (!isset($_SESSION['user_id'])) {
                    sendJsonResponse(['error' => 'User not logged in'], 401);
                }

                $required_fields = ['first_name', 'last_name'];
                $missing_fields = validateRequiredFields($input, $required_fields);
                
                if (!empty($missing_fields)) {
                    sendJsonResponse(['error' => 'Missing required fields: ' . implode(', ', $missing_fields)], 400);
                }

                $result = $user_manager->updateProfile(
                    $_SESSION['user_id'],
                    sanitizeInput($input['first_name']),
                    sanitizeInput($input['last_name']),
                    isset($input['phone']) ? sanitizeInput($input['phone']) : '',
                    isset($input['address']) ? sanitizeInput($input['address']) : '',
                    isset($input['date_of_birth']) ? $input['date_of_birth'] : null
                );

                if ($result['success']) {
                    $_SESSION['user_name'] = $input['first_name'] . ' ' . $input['last_name'];
                }

                sendJsonResponse($result);
                break;

            case 'change_password':
                if (!isset($_SESSION['user_id'])) {
                    sendJsonResponse(['error' => 'User not logged in'], 401);
                }

                $required_fields = ['current_password', 'new_password'];
                $missing_fields = validateRequiredFields($input, $required_fields);
                
                if (!empty($missing_fields)) {
                    sendJsonResponse(['error' => 'Missing required fields: ' . implode(', ', $missing_fields)], 400);
                }

                $result = $user_manager->changePassword(
                    $_SESSION['user_id'],
                    $input['current_password'],
                    $input['new_password']
                );

                sendJsonResponse($result);
                break;

            default:
                sendJsonResponse(['error' => 'Invalid action'], 400);
        }
        break;

    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'profile':
                    if (!isset($_SESSION['user_id'])) {
                        sendJsonResponse(['error' => 'User not logged in'], 401);
                    }

                    $user = $user_manager->getUserById($_SESSION['user_id']);
                    
                    if ($user) {
                        sendJsonResponse(['success' => true, 'user' => $user]);
                    } else {
                        sendJsonResponse(['error' => 'User not found'], 404);
                    }
                    break;

                case 'orders':
                    if (!isset($_SESSION['user_id'])) {
                        sendJsonResponse(['error' => 'User not logged in'], 401);
                    }

                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
                    $orders = $user_manager->getUserOrders($_SESSION['user_id'], $limit);
                    sendJsonResponse(['success' => true, 'orders' => $orders]);
                    break;

                case 'reservations':
                    if (!isset($_SESSION['user_id'])) {
                        sendJsonResponse(['error' => 'User not logged in'], 401);
                    }

                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
                    $reservations = $user_manager->getUserReservations($_SESSION['user_id'], $limit);
                    sendJsonResponse(['success' => true, 'reservations' => $reservations]);
                    break;

                case 'check_session':
                    if (isset($_SESSION['user_id'])) {
                        sendJsonResponse([
                            'logged_in' => true,
                            'user_id' => $_SESSION['user_id'],
                            'user_email' => $_SESSION['user_email'],
                            'user_name' => $_SESSION['user_name']
                        ]);
                    } else {
                        sendJsonResponse(['logged_in' => false]);
                    }
                    break;

                default:
                    sendJsonResponse(['error' => 'Invalid action'], 400);
            }
        } else {
            sendJsonResponse(['error' => 'Action parameter required'], 400);
        }
        break;

    default:
        sendJsonResponse(['error' => 'Method not allowed'], 405);
}
?>
