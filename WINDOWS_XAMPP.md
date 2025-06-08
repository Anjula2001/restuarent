# Grand Restaurant - Windows XAMPP Setup Guide

## 🖥️ Windows XAMPP Deployment

Your Grand Restaurant system is **fully compatible** with Windows XAMPP! This guide will help you deploy it successfully.

## 📋 Pre-Requirements

### XAMPP Components Needed
- ✅ **Apache** (Web Server)
- ✅ **PHP 7.4+** (with SQLite support)
- ❌ **MySQL** (Not needed - we use SQLite)
- ❌ **phpMyAdmin** (Not needed for this project)

### Download XAMPP
- **Download:** https://www.apachefriends.org/download.html
- **Choose:** PHP 7.4 or higher version
- **Install:** Follow standard installation (usually `C:\xampp\`)

## 🚀 Quick Deployment Steps

### Step 1: Copy Project Files
```cmd
# Copy your entire restaurant folder to XAMPP htdocs
# From: Your backup/source location
# To: C:\xampp\htdocs\restaurant\
```

**Important Folder Structure:**
```
C:\xampp\htdocs\restaurant\
├── index.html
├── setup.php
├── database\
│   └── schema.sql
├── api\
├── admin\
├── css\
├── js\
└── ... (all other files)
```

### Step 2: Start XAMPP Services
1. **Open XAMPP Control Panel**
2. **Start Apache** ✅ (Click "Start" button)
3. **MySQL** ❌ (Leave stopped - not needed)

### Step 3: Run Setup Script
**Option 1: Via Browser (Recommended)**
```
http://localhost/restaurant/setup.php
```

**Option 2: Via Command Line**
```cmd
cd C:\xampp\htdocs\restaurant
C:\xampp\php\php.exe setup.php
```

### Step 4: Access Your Restaurant System
- **Main Website:** `http://localhost/restaurant/`
- **Admin Panel:** `http://localhost/restaurant/admin/`
- **API Test:** `http://localhost/restaurant/api/menu.php`

## 🔧 Windows-Specific Configurations

### File Permissions (Windows)
Windows doesn't use chmod, but ensure:
- **Database folder** is writable
- **Uploads folder** is writable
- **All PHP files** are readable

### Path Separators
Your project already uses PHP's `DIRECTORY_SEPARATOR` and `__DIR__` which work on both systems.

### Database Path
The SQLite database path is automatically handled by PHP's `__DIR__` constant.

## 🛠️ Troubleshooting Windows Issues

### Common Windows Problems

#### 1. Apache Won't Start
```
Problem: Port 80 already in use
Solution: 
- Stop IIS if running
- Change Apache port in XAMPP config
- Or stop Skype (uses port 80)
```

#### 2. PHP SQLite Not Enabled
```
Check: C:\xampp\php\php.ini
Ensure: extension=sqlite3 is uncommented
Restart: Apache after changes
```

#### 3. File Upload Issues
```
Create: C:\xampp\htdocs\restaurant\uploads\menu-items\
Permissions: Full control for Apache user
```

#### 4. Database Creation Issues
```cmd
# Manual database creation
cd C:\xampp\htdocs\restaurant
C:\xampp\php\php.exe -r "
try {
    $pdo = new PDO('sqlite:database/grand_restaurant.db');
    echo 'Database connection: OK';
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
"
```

## 📱 Testing Your Installation

### Quick Test Commands (Windows CMD)
```cmd
# Test PHP version
C:\xampp\php\php.exe --version

# Test SQLite support
C:\xampp\php\php.exe -m | findstr sqlite3

# Test project setup
cd C:\xampp\htdocs\restaurant
C:\xampp\php\php.exe setup.php
```

### Browser Tests
1. ✅ **Main site loads:** `http://localhost/restaurant/`
2. ✅ **Menu displays:** Check menu items load
3. ✅ **Admin login works:** Username: admin, Password: admin123
4. ✅ **API responds:** `http://localhost/restaurant/api/menu.php`

## 🔄 Cross-Platform File Sharing

### Creating Portable Package
Your `backup.sh` script works on Mac/Linux. For Windows sharing:

**Option 1: Use WSL (Windows Subsystem for Linux)**
```bash
# If WSL is available
bash backup.sh
```

**Option 2: Manual Windows Backup**
```cmd
# Create backup folder
mkdir restaurant_backup_%date:~10,4%%date:~4,2%%date:~7,2%

# Copy files
xcopy /E /I restaurant restaurant_backup_%date:~10,4%%date:~4,2%%date:~7,2%

# Create zip (using PowerShell)
powershell Compress-Archive restaurant_backup_* restaurant_backup.zip
```

**Option 3: Use the existing backup files**
- Your `.tar.gz` files work with 7-Zip on Windows
- WinRAR also supports `.tar.gz` extraction

## ⚙️ XAMPP Configuration Tips

### Recommended XAMPP Settings
```ini
# In C:\xampp\php\php.ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
extension=sqlite3
extension=gd
```

### Apache Configuration
No special Apache config needed - default XAMPP settings work perfectly.

## 🚀 Deployment Checklist for Windows

- [ ] ✅ XAMPP installed and Apache running
- [ ] ✅ Project copied to `C:\xampp\htdocs\restaurant\`
- [ ] ✅ Setup script executed successfully
- [ ] ✅ Database file created and writable
- [ ] ✅ Upload folders created
- [ ] ✅ Main website accessible
- [ ] ✅ Admin panel accessible
- [ ] ✅ API endpoints responding
- [ ] ✅ Admin login working (change default password!)

## 📞 Windows Support

### Default Credentials
- **Username:** admin
- **Password:** admin123
- **⚠️ Change immediately after deployment**

### File Locations
- **Project:** `C:\xampp\htdocs\restaurant\`
- **Database:** `C:\xampp\htdocs\restaurant\database\grand_restaurant.db`
- **Uploads:** `C:\xampp\htdocs\restaurant\uploads\`
- **Logs:** `C:\xampp\apache\logs\error.log`

### Common Windows Paths
```cmd
# XAMPP Installation
C:\xampp\

# PHP Executable
C:\xampp\php\php.exe

# Apache Document Root
C:\xampp\htdocs\

# Apache Error Logs
C:\xampp\apache\logs\error.log
```

---

## 🎉 Success!

Your Grand Restaurant system should now be running perfectly on Windows XAMPP!

**Quick Access:**
- 🏠 **Restaurant:** http://localhost/restaurant/
- ⚙️ **Admin:** http://localhost/restaurant/admin/
- 🔧 **API:** http://localhost/restaurant/api/menu.php
- 📊 **XAMPP Control:** Start Menu → XAMPP Control Panel

**Cross-Platform Benefits:**
- ✅ Same codebase works on Mac MAMP and Windows XAMPP
- ✅ SQLite database is portable across systems
- ✅ No MySQL dependencies
- ✅ Consistent file structure
- ✅ Automatic setup script handles everything
