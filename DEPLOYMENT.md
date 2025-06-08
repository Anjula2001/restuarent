# Grand Restaurant - Deployment Checklist

## 📋 Pre-Deployment Checklist

### System Requirements ✅
- [ ] PHP 7.4 or higher installed
- [ ] SQLite3 extension enabled
- [ ] Web server (Apache/Nginx) running
- [ ] GD extension for image uploads
- [ ] Write permissions available

### Files to Include 📁
- [ ] All PHP files (*.php)
- [ ] HTML files (*.html)
- [ ] CSS/JS files
- [ ] Images directory
- [ ] Database schema file (`database/schema.sql`)
- [ ] Setup script (`setup.php`)
- [ ] Configuration files (`config/`)
- [ ] Classes directory (`classes/`)
- [ ] API directory (`api/`)

### Files to EXCLUDE 🚫
- [ ] ❌ Existing database file (`database/grand_restaurant.db`)
- [ ] ❌ Upload directories with user content
- [ ] ❌ Log files
- [ ] ❌ System-specific config files

## 🚀 Deployment Steps

### Step 1: Copy Files
```bash
# Copy all project files except database
rsync -av --exclude='database/grand_restaurant.db' --exclude='uploads/*' /source/path/ /destination/path/
```

### Step 2: Set Permissions
```bash
cd /destination/path/
chmod 755 database/
chmod 755 uploads/
chmod 755 uploads/menu-items/
chmod 644 *.php *.html
chmod 644 api/*.php
chmod 644 classes/*.php
chmod 644 config/*.php
```

### Step 3: Initialize Database
```bash
# Option 1: Use setup script
php setup.php

# Option 2: Manual setup
sqlite3 database/grand_restaurant.db < database/schema.sql
chmod 666 database/grand_restaurant.db
```

### Step 4: Test Installation
- [ ] Visit main website: `http://localhost/restaurant/`
- [ ] Test admin login: `http://localhost/restaurant/admin/`
- [ ] Check API endpoints: `http://localhost/restaurant/api/menu.php`
- [ ] Test file uploads (admin panel)
- [ ] Verify database operations

## 🔧 Configuration Updates

### Database Path (if needed)
Update `config/database_sqlite.php` if database path changes:
```php
$database_path = __DIR__ . '/../database/grand_restaurant.db';
```

### Upload Directory
Update upload paths in `api/upload_image.php` if needed:
```php
$upload_dir = '../uploads/menu-items/';
```

### Base URL (for production)
Update any hardcoded localhost URLs in:
- JavaScript files (`js/script.js`)
- Configuration files
- HTML files

## 🔍 Troubleshooting

### Database Issues
```bash
# Check SQLite support
php -m | grep sqlite3

# Check database permissions
ls -la database/
# Should show: -rw-rw-rw- for .db file
```

### Permission Issues
```bash
# Fix common permission issues
sudo chown -R www-data:www-data /path/to/restaurant/
sudo chmod -R 755 /path/to/restaurant/
sudo chmod 666 /path/to/restaurant/database/grand_restaurant.db
```

### Web Server Issues
```bash
# Test with PHP built-in server
cd /path/to/restaurant/
php -S localhost:8000

# Check Apache error logs
tail -f /var/log/apache2/error.log
```

## 📱 Quick Verification Commands

```bash
# Check PHP version
php --version

# Test database connection
php -r "
try {
    \$pdo = new PDO('sqlite:database/grand_restaurant.db');
    echo 'Database connection: OK\n';
} catch(Exception \$e) {
    echo 'Database error: ' . \$e->getMessage() . '\n';
}
"

# Test API endpoint
curl -s http://localhost/restaurant/api/menu.php | head -20
```

## 🎯 Post-Deployment Tasks

### Security
- [ ] Change default admin password
- [ ] Update database path in config if needed
- [ ] Set up proper file permissions
- [ ] Configure web server security headers

### Customization
- [ ] Update restaurant name/branding
- [ ] Add/modify menu items
- [ ] Configure email settings (if applicable)
- [ ] Set up backup procedures

### Testing
- [ ] Test all user workflows
- [ ] Verify admin panel functionality
- [ ] Test API endpoints
- [ ] Check responsive design on mobile
- [ ] Verify image upload functionality

## 📞 Support Information

### Default Admin Credentials
- **Username:** admin
- **Password:** admin123
- ⚠️ **Change immediately after deployment**

### Key Files to Check
- `config/database_sqlite.php` - Database connection
- `api/upload_image.php` - File upload handling
- `setup.php` - Initial setup script
- `database/schema.sql` - Database structure

### Common Error Solutions
1. **500 Error:** Check PHP error logs and file permissions
2. **Database Error:** Verify SQLite extension and file permissions
3. **Upload Error:** Check uploads directory permissions
4. **API Error:** Verify web server URL rewriting

---

✅ **Deployment Complete!** Your Grand Restaurant system should now be running on the new server.

🔗 **Quick Links After Deployment:**
- Main Site: `http://your-domain/restaurant/`
- Admin Panel: `http://your-domain/restaurant/admin/`
- API Test: `http://your-domain/restaurant/api/menu.php`
