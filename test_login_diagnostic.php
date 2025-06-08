<?php
// Test login functionality
session_start();
require_once 'config/database.php';
require_once 'classes/UserManager.php';

echo "<h2>Login System Diagnostic</h2>";

// Test 1: Database Connection
echo "<h3>1. Testing Database Connection</h3>";
try {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        echo "✅ Database connection successful<br>";
        
        // Check if users table exists
        $stmt = $conn->prepare("SHOW TABLES LIKE 'users'");
        $stmt->execute();
        $table_exists = $stmt->fetch();
        
        if ($table_exists) {
            echo "✅ Users table exists<br>";
            
            // Count users in table
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
            $stmt->execute();
            $count = $stmt->fetch();
            echo "📊 Total users in database: " . $count['count'] . "<br>";
            
            // Show sample users (first 3)
            $stmt = $conn->prepare("SELECT id, first_name, last_name, email, is_active FROM users LIMIT 3");
            $stmt->execute();
            $users = $stmt->fetchAll();
            
            echo "<h4>Sample Users:</h4>";
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Active</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['first_name'] . " " . $user['last_name'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "<td>" . ($user['is_active'] ? 'Yes' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
        } else {
            echo "❌ Users table does not exist<br>";
        }
    } else {
        echo "❌ Database connection failed<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 2: UserManager instantiation
echo "<h3>2. Testing UserManager</h3>";
try {
    $user_manager = new UserManager();
    echo "✅ UserManager created successfully<br>";
} catch (Exception $e) {
    echo "❌ UserManager error: " . $e->getMessage() . "<br>";
}

// Test 3: Test login with a sample user (if exists)
echo "<h3>3. Testing Login Function</h3>";
if (isset($users) && count($users) > 0) {
    $test_email = $users[0]['email'];
    echo "Testing login with email: " . $test_email . "<br>";
    echo "Note: Testing with a dummy password to check function behavior<br>";
    
    try {
        $result = $user_manager->loginUser($test_email, 'wrong_password');
        if ($result['success']) {
            echo "⚠️ Login succeeded with wrong password (unexpected)<br>";
        } else {
            echo "✅ Login correctly failed with wrong password: " . $result['message'] . "<br>";
        }
    } catch (Exception $e) {
        echo "❌ Login function error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "⚠️ No users found to test login function<br>";
}

// Test 4: Check session configuration
echo "<h3>4. Session Configuration</h3>";
echo "Session started: " . (session_status() === PHP_SESSION_ACTIVE ? "✅ Yes" : "❌ No") . "<br>";
echo "Session ID: " . session_id() . "<br>";
echo "Session save path: " . session_save_path() . "<br>";

// Test 5: PHP Configuration
echo "<h3>5. PHP Configuration</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PDO available: " . (extension_loaded('pdo') ? "✅ Yes" : "❌ No") . "<br>";
echo "PDO MySQL available: " . (extension_loaded('pdo_mysql') ? "✅ Yes" : "❌ No") . "<br>";

// Test 6: Create a test user for login testing
echo "<h3>6. Create Test User</h3>";
try {
    $test_result = $user_manager->registerUser(
        'Test',
        'User',
        'test@example.com',
        '1234567890',
        'testpass123',
        'Test Address'
    );
    
    if ($test_result['success']) {
        echo "✅ Test user created successfully<br>";
        
        // Now test login with this user
        $login_result = $user_manager->loginUser('test@example.com', 'testpass123');
        if ($login_result['success']) {
            echo "✅ Login test successful with test user<br>";
            echo "User data: " . json_encode($login_result['user']) . "<br>";
        } else {
            echo "❌ Login test failed: " . $login_result['message'] . "<br>";
        }
    } else {
        echo "ℹ️ Test user creation result: " . $test_result['message'] . "<br>";
        
        // Try to login with existing test user
        $login_result = $user_manager->loginUser('test@example.com', 'testpass123');
        if ($login_result['success']) {
            echo "✅ Login test successful with existing test user<br>";
        } else {
            echo "❌ Login test failed with existing user: " . $login_result['message'] . "<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Test user creation error: " . $e->getMessage() . "<br>";
}

echo "<h3>7. Recommendations</h3>";
echo "<p>If login is still not working, check:</p>";
echo "<ul>";
echo "<li>Database configuration (XAMPP vs MAMP ports)</li>";
echo "<li>Users table structure and data</li>";
echo "<li>Password hashing compatibility</li>";
echo "<li>Session configuration</li>";
echo "<li>Browser console for JavaScript errors</li>";
echo "</ul>";
?>
