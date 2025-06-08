#!/bin/bash

# Grand Restaurant MAMP Setup Verification Script
echo "🍽️  Grand Restaurant - MAMP Setup Verification"
echo "================================================"

# Check if we're in the correct directory
if [[ ! -f "README.md" ]] || [[ ! -d "api" ]]; then
    echo "❌ Error: Please run this script from the restaurant project root directory"
    exit 1
fi

echo "✅ Project directory structure confirmed"

# Check PHP version
echo ""
echo "📋 System Requirements Check:"
echo "-----------------------------"
PHP_VERSION=$(php -v | head -n 1 | cut -d ' ' -f 2)
echo "🐘 PHP Version: $PHP_VERSION"

# Check if required PHP extensions are available
if php -m | grep -q "pdo"; then
    echo "✅ PDO extension: Available"
else
    echo "❌ PDO extension: Missing"
fi

if php -m | grep -q "pdo_mysql"; then
    echo "✅ PDO MySQL extension: Available"
else
    echo "❌ PDO MySQL extension: Missing"
fi

# Check file permissions
echo ""
echo "📁 File Permissions Check:"
echo "--------------------------"
chmod -R 755 .
echo "✅ File permissions set to 755"

# Check API files syntax
echo ""
echo "🔍 PHP Syntax Check:"
echo "--------------------"
for file in api/*.php; do
    if php -l "$file" > /dev/null 2>&1; then
        echo "✅ $(basename "$file"): Syntax OK"
    else
        echo "❌ $(basename "$file"): Syntax Error"
        php -l "$file"
    fi
done

# Check class files syntax
echo ""
echo "🏗️  Class Files Check:"
echo "----------------------"
for file in classes/*.php; do
    if php -l "$file" > /dev/null 2>&1; then
        echo "✅ $(basename "$file"): Syntax OK"
    else
        echo "❌ $(basename "$file"): Syntax Error"
        php -l "$file"
    fi
done

# Check configuration files
echo ""
echo "⚙️  Configuration Check:"
echo "------------------------"
if [[ -f "config/database.php" ]]; then
    echo "✅ Database configuration: Found"
    if php -l "config/database.php" > /dev/null 2>&1; then
        echo "✅ Database configuration: Valid syntax"
    else
        echo "❌ Database configuration: Syntax error"
    fi
else
    echo "❌ Database configuration: Missing"
fi

# Check database schema
echo ""
echo "🗄️  Database Schema Check:"
echo "--------------------------"
if [[ -f "database/schema.sql" ]]; then
    echo "✅ Database schema: Found"
    TABLES=$(grep -c "CREATE TABLE" database/schema.sql)
    echo "📊 Tables in schema: $TABLES"
else
    echo "❌ Database schema: Missing"
fi

# Check required frontend files
echo ""
echo "🌐 Frontend Files Check:"
echo "------------------------"
FRONTEND_FILES=("index.html" "menu.html" "reservation.html" "order.html" "contact.html")
for file in "${FRONTEND_FILES[@]}"; do
    if [[ -f "$file" ]]; then
        echo "✅ $file: Found"
    else
        echo "❌ $file: Missing"
    fi
done

# Check admin panel
echo ""
echo "👑 Admin Panel Check:"
echo "--------------------"
if [[ -d "admin" ]] && [[ -f "admin/index.html" ]]; then
    echo "✅ Admin panel: Found"
else
    echo "❌ Admin panel: Missing"
fi

# Check demo and documentation files
echo ""
echo "📚 Documentation Check:"
echo "-----------------------"
DOC_FILES=("README.md" "MAMP_SETUP.md" "mamp_demo.html")
for file in "${DOC_FILES[@]}"; do
    if [[ -f "$file" ]]; then
        echo "✅ $file: Found"
    else
        echo "❌ $file: Missing"
    fi
done

# Generate MAMP instructions
echo ""
echo "🚀 MAMP Setup Instructions:"
echo "============================="
echo ""
echo "1. 📁 Project Placement:"
echo "   Copy this folder to: /Applications/MAMP/htdocs/restuarent/"
echo ""
echo "2. 🚀 Start MAMP:"
echo "   - Open MAMP application"
echo "   - Click 'Start Servers'"
echo "   - Apache should run on port 8888"
echo "   - MySQL should run on port 8889"
echo ""
echo "3. 🗄️  Database Setup:"
echo "   - Open phpMyAdmin: http://localhost:8888/phpmyadmin/"
echo "   - Create database: 'grand_restaurant'"
echo "   - Import file: database/schema.sql"
echo ""
echo "4. 🌐 Access URLs:"
echo "   - Demo Page: http://localhost:8888/restuarent/mamp_demo.html"
echo "   - Main Site: http://localhost:8888/restuarent/"
echo "   - Test API: http://localhost:8888/restuarent/api/test_mamp.php"
echo "   - Admin Panel: http://localhost:8888/restuarent/admin/"
echo ""
echo "5. 🔧 Testing:"
echo "   - Visit the demo page for interactive testing"
echo "   - Use the test_mamp.php script to verify database connection"
echo "   - Check all API endpoints work correctly"
echo ""
echo "✨ Setup verification complete!"
echo "📖 For detailed instructions, see: MAMP_SETUP.md"
echo ""
