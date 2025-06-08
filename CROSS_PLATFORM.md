# 🌐 Cross-Platform Compatibility Guide

## ✅ **YES! Your project works on Windows XAMPP!**

Your Grand Restaurant system is designed to be **fully cross-platform compatible**. Here's everything you need to know:

## 🖥️ **Supported Platforms**

| Platform | Web Server | Status | Port | Setup |
|----------|------------|--------|------|-------|
| **Windows** | XAMPP | ✅ Full Support | 80 | `http://localhost/restaurant/` |
| **macOS** | MAMP | ✅ Full Support | 8888 | `http://localhost:8888/restaurant/` |
| **Linux** | LAMP | ✅ Full Support | 80 | `http://localhost/restaurant/` |

## 📦 **Sharing Between Platforms**

### Method 1: Use Backup Scripts

**On Mac/Linux:**
```bash
./backup.sh
# Creates: grand_restaurant_backup_YYYYMMDD_HHMMSS.tar.gz
```

**On Windows:**
```cmd
backup_windows.bat
# Creates: grand_restaurant_backup_YYYYMMDD_HHMMSS.zip
```

### Method 2: Simple File Copy
Just copy the entire project folder - it works everywhere!

## 🚀 **Windows XAMPP Deployment**

### Quick Setup (5 minutes):

1. **Download XAMPP:** https://www.apachefriends.org/
2. **Install XAMPP** (usually to `C:\xampp\`)
3. **Copy project** to `C:\xampp\htdocs\restaurant\`
4. **Start Apache** in XAMPP Control Panel
5. **Run setup:** http://localhost/restaurant/setup.php
6. **Done!** Access at http://localhost/restaurant/

### Windows-Specific Files:
- ✅ `backup_windows.bat` - Windows backup script
- ✅ `WINDOWS_XAMPP.md` - Detailed Windows guide
- ✅ `setup.php` - Auto-detects Windows and provides specific instructions

## 🔄 **Platform Migration Examples**

### Mac MAMP → Windows XAMPP
```bash
# On Mac
./backup.sh

# Transfer the .tar.gz file to Windows
# On Windows, extract with 7-Zip or WinRAR
# Copy to C:\xampp\htdocs\restaurant\
# Run: http://localhost/restaurant/setup.php
```

### Windows XAMPP → Mac MAMP
```cmd
# On Windows
backup_windows.bat

# Transfer the .zip file to Mac
# On Mac, extract and copy to /Applications/MAMP/htdocs/restaurant/
# Run: http://localhost:8888/restaurant/setup.php
```

## 🛠️ **Cross-Platform Features**

### What Makes It Compatible:

1. **SQLite Database** - Works on all platforms
2. **PHP Path Handling** - Uses `DIRECTORY_SEPARATOR` and `__DIR__`
3. **No MySQL Dependencies** - Portable database file
4. **Standard PHP Code** - No platform-specific extensions
5. **Relative Paths** - All paths are relative to project root

### Automatic Platform Detection:
```php
// setup.php automatically detects:
$os = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : 'Unix';

// Provides platform-specific instructions
if ($is_windows) {
    echo "Windows XAMPP: http://localhost/restaurant/";
} else {
    echo "Mac MAMP: http://localhost:8888/restaurant/";
}
```

## 📁 **File Structure (Same on All Platforms)**

```
restaurant/
├── setup.php              ← Universal setup script
├── backup.sh               ← Mac/Linux backup
├── backup_windows.bat      ← Windows backup
├── WINDOWS_XAMPP.md        ← Windows-specific guide
├── database/
│   ├── schema.sql          ← Universal SQLite schema
│   └── grand_restaurant.db ← Portable database file
├── api/                    ← Cross-platform APIs
├── admin/                  ← Universal admin panel
└── ... (all other files)
```

## 🎯 **Quick Reference**

### Windows XAMPP Users:
- **Extract to:** `C:\xampp\htdocs\restaurant\`
- **Start:** Apache in XAMPP Control Panel
- **Access:** http://localhost/restaurant/
- **Setup:** http://localhost/restaurant/setup.php

### Mac MAMP Users:
- **Extract to:** `/Applications/MAMP/htdocs/restaurant/`
- **Start:** MAMP servers
- **Access:** http://localhost:8888/restaurant/
- **Setup:** http://localhost:8888/restaurant/setup.php

### Linux LAMP Users:
- **Extract to:** `/var/www/html/restaurant/`
- **Start:** Apache service
- **Access:** http://localhost/restaurant/
- **Setup:** http://localhost/restaurant/setup.php

## 🔧 **Troubleshooting by Platform**

### Windows Issues:
- **Port 80 busy:** Stop IIS or Skype
- **SQLite error:** Uncomment `extension=sqlite3` in `php.ini`
- **Permission error:** Run XAMPP as Administrator

### Mac Issues:
- **Port conflict:** Change MAMP ports or stop other services
- **Permission error:** `chmod 755 database/`
- **Database locked:** Stop other MAMP instances

### Linux Issues:
- **Apache not starting:** `sudo systemctl start apache2`
- **Permission error:** `sudo chown -R www-data:www-data /var/www/html/restaurant/`
- **SQLite missing:** `sudo apt-get install php-sqlite3`

## 🎉 **Success Indicators**

✅ **Working correctly when:**
- Main website loads: Restaurant homepage appears
- Menu displays: Food items load dynamically
- Admin login works: Username `admin`, Password `admin123`
- API responds: `/api/menu.php` returns JSON data
- Database works: Can add menu items via admin panel

## 📞 **Support**

**Universal Admin Credentials:**
- Username: `admin`
- Password: `admin123` (change after setup!)

**Universal API Test:**
- Mac MAMP: http://localhost:8888/restaurant/api/menu.php
- Windows XAMPP: http://localhost/restaurant/api/menu.php
- Linux LAMP: http://localhost/restaurant/api/menu.php

---

## 🌟 **Bottom Line**

**Your Grand Restaurant project is 100% compatible with Windows XAMPP!** 

The combination of SQLite database, PHP path handling, and cross-platform setup scripts makes it seamlessly portable between Mac MAMP, Windows XAMPP, and Linux LAMP environments.

Just copy, run setup, and enjoy! 🍽️
