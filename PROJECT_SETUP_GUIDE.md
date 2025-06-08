# 🍽️ Grand Restaurant - Complete Setup Guide

## 📁 PROJECT OVERVIEW
This is a complete restaurant management system with:
- **Frontend**: Modern responsive website with menu, ordering, reviews, reservations
- **Backend**: PHP APIs with MySQL database
- **Admin Panel**: Complete dashboard for managing menu, orders, reviews, users
- **Database**: Single consolidated MySQL database file

---

## 🚀 QUICK SETUP FOR NEW DEVICES

### 📋 REQUIREMENTS
- **Web Server**: MAMP (macOS), XAMPP (Windows), or LAMP (Linux)
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher
- **Browser**: Modern browser (Chrome, Firefox, Safari, Edge)

---

## 🔧 PLATFORM-SPECIFIC SETUP

### 🍎 **macOS (MAMP)**
```bash
1. Download and install MAMP
2. Start MAMP (Apache + MySQL)
3. Copy 'restuarent' folder to /Applications/MAMP/htdocs/
4. Open phpMyAdmin: http://localhost:8888/phpMyAdmin/
5. Create database: CREATE DATABASE grand_restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
6. Import: database/grand_restaurant.sql
7. Test: http://localhost:8888/restuarent/
```

### 🪟 **Windows (XAMPP)**
```bash
1. Download and install XAMPP
2. Start XAMPP (Apache + MySQL)
3. Copy 'restuarent' folder to C:\xampp\htdocs\
4. Open phpMyAdmin: http://localhost/phpMyAdmin/
5. Create database: CREATE DATABASE grand_restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
6. Import: database/grand_restaurant.sql
7. Update config/database.php (port: 3306, password: '')
8. Test: http://localhost/restuarent/
```

### 🐧 **Linux (LAMP)**
```bash
1. Install LAMP stack: sudo apt install apache2 mysql-server php
2. Copy 'restuarent' folder to /var/www/html/
3. Create database: mysql -u root -p
   CREATE DATABASE grand_restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
4. Import: mysql -u root -p grand_restaurant < database/grand_restaurant.sql
5. Update config/database.php with your credentials
6. Test: http://localhost/restuarent/
```

---

## 📂 DATABASE SETUP

### **Single File Solution**
- **File**: `database/grand_restaurant.sql` 
- **Contains**: Complete database structure + sample data
- **Size**: ~15KB
- **Tables**: 7 tables with relationships
- **Data**: Menu items, users, admin, reviews, orders

### **Import Instructions**
1. **Via phpMyAdmin**: 
   - Create database `grand_restaurant`
   - Go to Import tab
   - Choose `database/grand_restaurant.sql`
   - Click "Go"

2. **Via Command Line**:
   ```bash
   mysql -u root -p grand_restaurant < database/grand_restaurant.sql
   ```

---

## ⚙️ CONFIGURATION

### **Database Configuration**
Edit `config/database.php`:

```php
// MAMP (macOS)
$host = 'localhost';
$port = '8889';
$username = 'root';
$password = 'root';

// XAMPP (Windows)
$host = 'localhost';
$port = '3306';
$username = 'root';
$password = '';

// LAMP (Linux)
$host = 'localhost';
$port = '3306';
$username = 'root';
$password = 'your_password';
```

---

## 🔐 DEFAULT CREDENTIALS

### **Admin Access**
- **URL**: http://localhost:XXXX/restuarent/admin/login.html
- **Username**: `admin`
- **Password**: `admin123`

### **MySQL Database**
- **Database**: `grand_restaurant`
- **Tables**: 7 tables
- **Sample Data**: 13+ menu items, 6 users, 10+ reviews

---

## 🧪 VERIFICATION STEPS

### **1. Test Website**
```
http://localhost:XXXX/restuarent/
✅ Homepage loads
✅ Menu displays with images
✅ Cart functionality works
✅ Order placement works
✅ Reviews display
```

### **2. Test Admin Panel**
```
http://localhost:XXXX/restuarent/admin/login.html
✅ Login with admin/admin123
✅ Dashboard displays data
✅ Menu management works
✅ Orders list displays
✅ User management works
```

### **3. Test Database**
```
phpMyAdmin → grand_restaurant
✅ 7 tables exist
✅ menu_items has data
✅ users table has accounts
✅ reviews table has entries
```

---

## 📱 FEATURES INCLUDED

### **Customer Features**
- 🍽️ **Menu Browsing**: Categories, search, filtering
- 🛒 **Shopping Cart**: Add items, adjust quantities
- 📝 **Order Placement**: Delivery/pickup options
- ⭐ **Reviews System**: Rate and review dishes
- 📅 **Reservations**: Table booking system
- 👤 **User Accounts**: Registration and login

### **Admin Features**
- 📊 **Dashboard**: Overview of orders, users, reviews
- 🍽️ **Menu Management**: Add/edit/delete menu items
- 📋 **Order Management**: View and update order status
- 👥 **User Management**: View customer accounts
- ⭐ **Review Moderation**: Approve/reject reviews
- 📅 **Reservation Management**: Handle table bookings

---

## 📦 PROJECT STRUCTURE

```
restuarent/
├── database/
│   └── grand_restaurant.sql     # Single consolidated database
├── admin/                       # Admin panel files
├── api/                         # Backend API endpoints
├── classes/                     # PHP classes
├── config/
│   └── database.php            # MySQL configuration
├── css/                         # Stylesheets
├── js/                          # JavaScript files
├── images/                      # Static images
├── uploads/                     # User uploaded files
└── [HTML files]                # Frontend pages
```

---

## 🔄 SHARING THIS PROJECT

### **What to Share**
```
✅ Entire 'restuarent' folder
✅ database/grand_restaurant.sql file
✅ This setup guide
```

### **What NOT to Share**
```
❌ .db files (obsolete SQLite files)
❌ Individual SQL files (consolidated into one)
❌ Local configuration files with passwords
```

---

## 🛠️ TROUBLESHOOTING

### **Common Issues**

**1. Database Connection Error**
```
Solution: Check MySQL is running, verify credentials in config/database.php
```

**2. Menu Items Not Loading**
```
Solution: Ensure database is imported, check API endpoints work
```

**3. Admin Login Not Working**
```
Solution: Verify database import, use admin/admin123 credentials
```

**4. Images Not Displaying**
```
Solution: Check uploads/ folder permissions, verify image paths
```

**5. Port Issues**
```
MAMP: Use port 8888 for web, 8889 for MySQL
XAMPP: Use port 80 for web, 3306 for MySQL
```

---

## ✅ SUCCESS INDICATORS

When setup is complete, you should see:
- ✅ Restaurant website loads with menu
- ✅ Admin panel accessible with login
- ✅ Database visible in phpMyAdmin
- ✅ Menu items display with images
- ✅ Order system functional
- ✅ Reviews system working

---

## 🎉 PROJECT STATUS

**✅ PRODUCTION READY**
- Complete restaurant management system
- Single database file for easy deployment
- Cross-platform compatible
- Full documentation included
- No external dependencies

**🔧 MAINTENANCE**
- Regular database backups recommended
- Update PHP/MySQL as needed
- Monitor uploads/ folder size
- Review admin access regularly

---

*Last Updated: June 8, 2025*
*Project: Grand Restaurant Management System*
*Status: Complete and Ready for Deployment*
