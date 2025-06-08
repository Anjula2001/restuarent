<?php
echo "<h1>🔐 Restaurant Login System - Final Test Report</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .error { color: red; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .info { color: blue; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .test-section { border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
    h2 { color: #333; border-bottom: 2px solid #d4af37; padding-bottom: 5px; }
    h3 { color: #666; }
    pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
    .navigation { background: #f0f0f0; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    .navigation a { margin-right: 15px; padding: 8px 12px; background: #d4af37; color: white; text-decoration: none; border-radius: 3px; }
    .navigation a:hover { background: #b8941f; }
</style>";

echo '<div class="navigation">
    <h3>Quick Navigation</h3>
    <a href="login.html">Login Form</a>
    <a href="test_login_diagnostic.php">System Diagnostics</a>
    <a href="dashboard.html">Dashboard</a>
    <a href="index.html">Home</a>
</div>';

// Test credentials
$test_email = "admin@restaurant.com";
$test_password = "admin123";

echo '<div class="test-section">';
echo "<h2>1. Database Connection Test</h2>";

try {
    require_once 'config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo '<div class="success">✅ Database connection successful</div>';
        
        // Test if users table exists and has data
        $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo '<div class="info">📊 Users table has ' . $result['count'] . ' records</div>';
        
        // Check if test user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$test_email]);
        $test_user = $stmt->fetch();
        
        if ($test_user) {
            echo '<div class="success">✅ Test user found: ' . $test_user['first_name'] . ' ' . $test_user['last_name'] . '</div>';
            echo '<div class="info">📅 Created: ' . $test_user['created_at'] . '</div>';
            echo '<div class="info">🔒 Password hash exists: ' . (empty($test_user['password_hash']) ? 'No' : 'Yes') . '</div>';
        } else {
            echo '<div class="error">❌ Test user not found</div>';
        }
        
    } else {
        echo '<div class="error">❌ Database connection failed</div>';
    }
} catch (Exception $e) {
    echo '<div class="error">❌ Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
echo '</div>';

echo '<div class="test-section">';
echo "<h2>2. UserManager Class Test</h2>";

try {
    require_once 'classes/UserManager.php';
    $user_manager = new UserManager();
    echo '<div class="success">✅ UserManager class loaded successfully</div>';
    
    echo "<h3>Testing Login Functionality</h3>";
    $result = $user_manager->loginUser($test_email, $test_password);
    
    if ($result['success']) {
        echo '<div class="success">✅ UserManager login successful!</div>';
        echo '<div class="info">👤 User ID: ' . $result['user']['id'] . '</div>';
        echo '<div class="info">📧 Email: ' . $result['user']['email'] . '</div>';
        echo '<div class="info">👤 Name: ' . $result['user']['first_name'] . ' ' . $result['user']['last_name'] . '</div>';
    } else {
        echo '<div class="error">❌ UserManager login failed: ' . htmlspecialchars($result['message']) . '</div>';
    }
    
} catch (Exception $e) {
    echo '<div class="error">❌ UserManager error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
echo '</div>';

echo '<div class="test-section">';
echo "<h2>3. API Endpoint Test</h2>";

// Test the API directly
$post_data = json_encode([
    'action' => 'login',
    'email' => $test_email,
    'password' => $test_password
]);

$ch = curl_init('http://localhost/restuarent/api/auth.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($post_data)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h3>API Response Details</h3>";
echo '<div class="info">🌐 HTTP Status Code: ' . $http_code . '</div>';

$response_data = json_decode($response, true);
if ($response_data) {
    if (isset($response_data['success']) && $response_data['success']) {
        echo '<div class="success">✅ API login successful!</div>';
        echo '<div class="info">📝 Response message: ' . htmlspecialchars($response_data['message']) . '</div>';
        
        if (isset($response_data['user'])) {
            echo "<h4>User Data Returned:</h4>";
            echo "<ul>";
            foreach ($response_data['user'] as $key => $value) {
                if ($key !== 'password_hash') {
                    echo "<li><strong>$key:</strong> " . htmlspecialchars($value) . "</li>";
                }
            }
            echo "</ul>";
        }
    } else {
        echo '<div class="error">❌ API login failed!</div>';
        if (isset($response_data['error'])) {
            echo '<div class="error">Error: ' . htmlspecialchars($response_data['error']) . '</div>';
        }
        if (isset($response_data['message'])) {
            echo '<div class="error">Message: ' . htmlspecialchars($response_data['message']) . '</div>';
        }
    }
} else {
    echo '<div class="error">❌ Could not parse API response</div>';
    echo '<div class="info">Raw response: <pre>' . htmlspecialchars($response) . '</pre></div>';
}
echo '</div>';

echo '<div class="test-section">';
echo "<h2>4. Session Management Test</h2>";

// Test session check
$ch = curl_init('http://localhost/restuarent/api/auth.php?action=check_session');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Include cookies if any were set
curl_setopt($ch, CURLOPT_COOKIEFILE, '');
curl_setopt($ch, CURLOPT_COOKIEJAR, '');

$session_response = curl_exec($ch);
curl_close($ch);

$session_data = json_decode($session_response, true);
if ($session_data) {
    if (isset($session_data['logged_in']) && $session_data['logged_in']) {
        echo '<div class="success">✅ Session is active</div>';
        echo '<div class="info">👤 User: ' . htmlspecialchars($session_data['user_name'] ?? 'Unknown') . '</div>';
    } else {
        echo '<div class="info">ℹ️ No active session (this is normal for curl requests)</div>';
    }
} else {
    echo '<div class="error">❌ Could not check session</div>';
}
echo '</div>';

echo '<div class="test-section">';
echo "<h2>5. Frontend Integration Check</h2>";

echo '<div class="info">🌐 Frontend login form available at: <a href="login.html" target="_blank">login.html</a></div>';
echo '<div class="info">📊 User dashboard available at: <a href="dashboard.html" target="_blank">dashboard.html</a></div>';

echo "<h3>Test Credentials for Manual Testing:</h3>";
echo '<div class="info">';
echo "<strong>Email:</strong> $test_email<br>";
echo "<strong>Password:</strong> $test_password";
echo '</div>';
echo '</div>';

echo '<div class="test-section">';
echo "<h2>6. System Status Summary</h2>";

$all_tests_passed = true;

// Check database
try {
    $database = new Database();
    $conn = $database->getConnection();
    if (!$conn) $all_tests_passed = false;
} catch (Exception $e) {
    $all_tests_passed = false;
}

// Check UserManager
try {
    $user_manager = new UserManager();
    $result = $user_manager->loginUser($test_email, $test_password);
    if (!$result['success']) $all_tests_passed = false;
} catch (Exception $e) {
    $all_tests_passed = false;
}

// Check API
if (!$response_data || !isset($response_data['success']) || !$response_data['success']) {
    $all_tests_passed = false;
}

if ($all_tests_passed) {
    echo '<div class="success">';
    echo '<h3>🎉 All Tests Passed!</h3>';
    echo '<p>The restaurant login system is fully functional:</p>';
    echo '<ul>';
    echo '<li>✅ Database connection is working</li>';
    echo '<li>✅ UserManager class is working</li>';
    echo '<li>✅ API endpoints are responding correctly</li>';
    echo '<li>✅ Test user credentials are valid</li>';
    echo '<li>✅ Login functionality is ready for use</li>';
    echo '</ul>';
    echo '<p><strong>Users can now successfully log in to the restaurant management system!</strong></p>';
    echo '</div>';
} else {
    echo '<div class="error">';
    echo '<h3>⚠️ Some Issues Found</h3>';
    echo '<p>Please review the test results above and fix any issues before deploying to production.</p>';
    echo '</div>';
}
echo '</div>';

echo '<div class="navigation">';
echo '<h3>Next Steps</h3>';
echo '<p>Try logging in with the test credentials:</p>';
echo '<a href="login.html">Test Login Form</a>';
echo '<a href="register.html">Test Registration</a>';
echo '<a href="dashboard.html">Access Dashboard</a>';
echo '</div>';
?>
