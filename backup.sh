#!/bin/bash

# Grand Restaurant Backup Script
# Creates a complete backup of the restaurant system

echo "🍽️ Grand Restaurant Backup Tool"
echo "================================="

# Get current timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="backup_${TIMESTAMP}"

# Create backup directory
mkdir -p "$BACKUP_DIR"

echo "📦 Creating backup in: $BACKUP_DIR"

# Copy essential files
echo "📁 Copying core files..."
cp -r *.html *.php "$BACKUP_DIR/" 2>/dev/null
cp -r api/ "$BACKUP_DIR/" 2>/dev/null
cp -r classes/ "$BACKUP_DIR/" 2>/dev/null
cp -r config/ "$BACKUP_DIR/" 2>/dev/null
cp -r css/ "$BACKUP_DIR/" 2>/dev/null
cp -r js/ "$BACKUP_DIR/" 2>/dev/null
cp -r images/ "$BACKUP_DIR/" 2>/dev/null
cp -r admin/ "$BACKUP_DIR/" 2>/dev/null

# Copy database and schema
echo "🗄️ Backing up database..."
mkdir -p "$BACKUP_DIR/database"
cp database/schema.sql "$BACKUP_DIR/database/" 2>/dev/null
cp database/grand_restaurant_complete.sql "$BACKUP_DIR/database/" 2>/dev/null
cp database/complete_database.sql "$BACKUP_DIR/database/" 2>/dev/null
cp database/grand_restaurant.db "$BACKUP_DIR/database/" 2>/dev/null

# Copy uploads if they exist
echo "📷 Backing up uploads..."
if [ -d "uploads" ]; then
    cp -r uploads/ "$BACKUP_DIR/"
fi

# Copy documentation
echo "📚 Copying documentation..."
cp README.md "$BACKUP_DIR/" 2>/dev/null
cp DEPLOYMENT.md "$BACKUP_DIR/" 2>/dev/null

# Create backup info file
echo "📝 Creating backup info..."
cat > "$BACKUP_DIR/BACKUP_INFO.txt" << EOF
Grand Restaurant System Backup
==============================

Backup Date: $(date)
Backup Time: $(date +"%H:%M:%S")
System: $(uname -s)
User: $(whoami)

Contents:
- Core PHP files
- HTML templates
- CSS/JS assets
- Database and schema
- Upload files
- Documentation

Restore Instructions:
1. Copy all files to web server directory
2. Run: php setup.php (if database needs recreation)
3. Set proper permissions:
   chmod 666 database/grand_restaurant.db
   chmod 755 uploads/
4. Access via web browser

Admin Credentials:
Username: admin
Password: admin123 (change after restore)
EOF

# Create archive
echo "🗜️ Creating archive..."
tar -czf "grand_restaurant_backup_${TIMESTAMP}.tar.gz" "$BACKUP_DIR"

# Clean up
rm -rf "$BACKUP_DIR"

echo "✅ Backup completed successfully!"
echo "📦 Archive: grand_restaurant_backup_${TIMESTAMP}.tar.gz"
echo ""
echo "To restore:"
echo "1. Extract: tar -xzf grand_restaurant_backup_${TIMESTAMP}.tar.gz"
echo "2. Copy contents to web server directory"
echo "3. Run setup if needed: php setup.php"
echo "4. Set permissions as shown in DEPLOYMENT.md"
