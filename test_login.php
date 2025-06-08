<?php
echo "<h2>Testing Login Functionality</h2>";

// Test credentials
$test_email = "admin@restaurant.com";
$test_password = "admin123";

echo "<h3>Testing Login API</h3>";
echo "<p>Testing with credentials: $test_email / $test_password</p>";

// Prepare POST data
$post_data = json_encode([
    'action' => 'login',
    'email' => $test_email,
    'password' => $test_password
]);

// Initialize cURL
$ch = curl_init('http://localhost/restuarent/api/auth.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($post_data)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h4>API Response:</h4>";
echo "<p><strong>HTTP Code:</strong> $http_code</p>";
echo "<p><strong>Response:</strong></p>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Try to decode JSON response
$response_data = json_decode($response, true);
if ($response_data) {
    echo "<h4>Parsed Response:</h4>";
    if (isset($response_data['success']) && $response_data['success']) {
        echo "<p style='color: green;'>✅ Login successful!</p>";
        if (isset($response_data['user'])) {
            echo "<p><strong>User details:</strong></p>";
            echo "<ul>";
            foreach ($response_data['user'] as $key => $value) {
                if ($key !== 'password') { // Don't display password
                    echo "<li><strong>$key:</strong> " . htmlspecialchars($value) . "</li>";
                }
            }
            echo "</ul>";
        }
    } else {
        echo "<p style='color: red;'>❌ Login failed!</p>";
        if (isset($response_data['error'])) {
            echo "<p><strong>Error:</strong> " . htmlspecialchars($response_data['error']) . "</p>";
        }
    }
} else {
    echo "<p style='color: orange;'>⚠️ Could not parse JSON response</p>";
}

echo "<h3>Testing Direct UserManager</h3>";

try {
    require_once 'classes/UserManager.php';
    $user_manager = new UserManager();
    
    echo "<p>Testing UserManager login directly...</p>";
    $result = $user_manager->loginUser($test_email, $test_password);
    
    echo "<pre>" . print_r($result, true) . "</pre>";
    
    if ($result['success']) {
        echo "<p style='color: green;'>✅ Direct UserManager login successful!</p>";
    } else {
        echo "<p style='color: red;'>❌ Direct UserManager login failed!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><a href='login.html'>Go to Login Form</a> | <a href='test_login_diagnostic.php'>Run Diagnostics</a></p>";
?>