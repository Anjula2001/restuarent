#!/bin/bash

# Grand Restaurant Backend Setup Script
echo "🍽️  Grand Restaurant Backend Setup"
echo "=================================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 7.4 or higher."
    exit 1
fi

echo "✅ PHP found: $(php -v | head -n1)"

# Check if MySQL is available
if ! command -v mysql &> /dev/null; then
    echo "⚠️  MySQL command not found. Make sure MySQL is installed and accessible."
fi

# Create database setup file
cat > setup_database.sql << EOF
CREATE DATABASE IF NOT EXISTS grand_restaurant;
USE grand_restaurant;
SOURCE database/schema.sql;
EOF

echo ""
echo "📋 Setup Instructions:"
echo "1. Make sure MySQL server is running"
echo "2. Run: mysql -u root -p < setup_database.sql"
echo "3. Update database credentials in config/database.php"
echo "4. Start your web server (Apache/Nginx)"
echo "5. Access the application at your web server URL"
echo ""
echo "🔧 Admin Dashboard: /admin/index.html"
echo "🔐 Default Admin: username=admin, password=admin123"
echo ""
echo "📚 Check README.md for detailed API documentation"
echo ""

# Set proper permissions
if [[ "$OSTYPE" != "msys" ]]; then
    echo "🔒 Setting file permissions..."
    find . -name "*.php" -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    echo "✅ Permissions set"
fi

echo ""
echo "🚀 Setup complete! Check the README.md for next steps."
