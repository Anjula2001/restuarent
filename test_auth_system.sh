#!/bin/bash

# Grand Restaurant Authentication System Test Script
# This script tests all authentication functionality

echo "🍽️  Grand Restaurant - Authentication System Test"
echo "=================================================="
echo ""

# Test 1: Database Connection
echo "📊 Test 1: Database Connection"
echo "------------------------------"
response=$(curl -s http://localhost:8000/api/test_sqlite.php)
if [[ $response == *"✅"* ]]; then
    echo "✅ Database connection: PASSED"
else
    echo "❌ Database connection: FAILED"
    echo "Response: $response"
fi
echo ""

# Test 2: User Registration
echo "👤 Test 2: User Registration"
echo "-----------------------------"
registration_response=$(curl -s -X POST http://localhost:8000/api/auth.php \
    -H "Content-Type: application/json" \
    -d '{
        "action": "register",
        "first_name": "Test",
        "last_name": "User",
        "email": "testuser'$(date +%s)'@example.com",
        "password": "password123",
        "phone": "1234567890"
    }')

if [[ $registration_response == *"success\":true"* ]]; then
    echo "✅ User registration: PASSED"
    # Extract email from response for login test
    test_email=$(echo $registration_response | grep -o '"email":"[^"]*"' | cut -d'"' -f4)
else
    echo "❌ User registration: FAILED"
    echo "Response: $registration_response"
    test_email="testuser@example.com"
fi
echo ""

# Test 3: User Login
echo "🔐 Test 3: User Login"
echo "----------------------"
login_response=$(curl -s -X POST http://localhost:8000/api/auth.php \
    -H "Content-Type: application/json" \
    -c cookies.txt \
    -d '{
        "action": "login",
        "email": "'$test_email'",
        "password": "password123"
    }')

if [[ $login_response == *"success\":true"* ]]; then
    echo "✅ User login: PASSED"
else
    echo "❌ User login: FAILED"
    echo "Response: $login_response"
fi
echo ""

# Test 4: Session Check
echo "📋 Test 4: Session Check"
echo "-------------------------"
session_response=$(curl -s http://localhost:8000/api/auth.php?action=check_session -b cookies.txt)

if [[ $session_response == *"logged_in\":true"* ]]; then
    echo "✅ Session check: PASSED"
else
    echo "❌ Session check: FAILED"
    echo "Response: $session_response"
fi
echo ""

# Test 5: Profile Retrieval
echo "👤 Test 5: Profile Retrieval"
echo "-----------------------------"
profile_response=$(curl -s http://localhost:8000/api/auth.php?action=profile -b cookies.txt)

if [[ $profile_response == *"success\":true"* ]] && [[ $profile_response == *"first_name"* ]]; then
    echo "✅ Profile retrieval: PASSED"
else
    echo "❌ Profile retrieval: FAILED"
    echo "Response: $profile_response"
fi
echo ""

# Test 6: Profile Update
echo "✏️  Test 6: Profile Update"
echo "---------------------------"
update_response=$(curl -s -X POST http://localhost:8000/api/auth.php \
    -H "Content-Type: application/json" \
    -b cookies.txt \
    -d '{
        "action": "update_profile",
        "first_name": "Updated",
        "last_name": "User",
        "phone": "9876543210"
    }')

if [[ $update_response == *"success\":true"* ]]; then
    echo "✅ Profile update: PASSED"
else
    echo "❌ Profile update: FAILED"
    echo "Response: $update_response"
fi
echo ""

# Test 7: Password Change
echo "🔑 Test 7: Password Change"
echo "---------------------------"
password_response=$(curl -s -X POST http://localhost:8000/api/auth.php \
    -H "Content-Type: application/json" \
    -b cookies.txt \
    -d '{
        "action": "change_password",
        "current_password": "password123",
        "new_password": "newpassword123"
    }')

if [[ $password_response == *"success\":true"* ]]; then
    echo "✅ Password change: PASSED"
else
    echo "❌ Password change: FAILED"
    echo "Response: $password_response"
fi
echo ""

# Test 8: Logout
echo "🚪 Test 8: Logout"
echo "------------------"
logout_response=$(curl -s -X POST http://localhost:8000/api/auth.php \
    -H "Content-Type: application/json" \
    -b cookies.txt \
    -d '{"action": "logout"}')

if [[ $logout_response == *"success\":true"* ]]; then
    echo "✅ Logout: PASSED"
else
    echo "❌ Logout: FAILED"
    echo "Response: $logout_response"
fi
echo ""

# Test 9: Post-logout Session Check
echo "📋 Test 9: Post-logout Session Check"
echo "-------------------------------------"
post_logout_session=$(curl -s http://localhost:8000/api/auth.php?action=check_session -b cookies.txt)

if [[ $post_logout_session == *"logged_in\":false"* ]]; then
    echo "✅ Post-logout session check: PASSED"
else
    echo "❌ Post-logout session check: FAILED"
    echo "Response: $post_logout_session"
fi
echo ""

# Test 10: Frontend Pages Accessibility
echo "🌐 Test 10: Frontend Pages"
echo "---------------------------"
pages=("index.html" "login.html" "register.html" "dashboard.html" "profile.html" "menu.html" "order.html" "reservation.html" "contact.html")

for page in "${pages[@]}"; do
    response_code=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/$page)
    if [[ $response_code == "200" ]]; then
        echo "✅ $page: ACCESSIBLE"
    else
        echo "❌ $page: NOT ACCESSIBLE (HTTP $response_code)"
    fi
done

echo ""
echo "🎯 Authentication System Test Complete!"
echo "========================================"

# Cleanup
rm -f cookies.txt

echo ""
echo "📝 Manual Testing Recommendations:"
echo "1. Visit http://localhost:8000/test_auth.html for interactive testing"
echo "2. Test user registration at http://localhost:8000/register.html" 
echo "3. Test user login at http://localhost:8000/login.html"
echo "4. Verify dashboard access at http://localhost:8000/dashboard.html"
echo "5. Test profile management at http://localhost:8000/profile.html"
echo ""
echo "🔗 Quick Links:"
echo "- Home: http://localhost:8000/"
echo "- Login: http://localhost:8000/login.html"
echo "- Register: http://localhost:8000/register.html"
echo "- Dashboard: http://localhost:8000/dashboard.html"
echo "- Profile: http://localhost:8000/profile.html"
echo "- Test Suite: http://localhost:8000/test_auth.html"
