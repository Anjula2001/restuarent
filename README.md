# Grand Restaurant Management System

A complete restaurant management system built with PHP, MySQL, and modern web technologies.

## 🎉 Project Cleanup Completed

✅ **CLEANUP STATUS**: All unnecessary files have been successfully removed while preserving 100% of core functionality.

**Files Removed:**
- 100+ test and debug HTML files
- 30+ completion report markdown files  
- Debug JavaScript and PHP scripts
- Temporary setup and verification scripts
- Old backup files and archives

**Core Files Preserved:**
- 10 main HTML pages (index, menu, order, etc.)
- 7 API endpoints (auth, menu, orders, etc.)
- 5 PHP manager classes
- 1 consolidated database file
- Complete admin panel
- All assets (CSS, JS, images)

## Quick Setup

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

### Installation
1. Copy project to web server directory
2. Import `database/grand_restaurant.sql` to MySQL
3. Update `config/database.php` with your MySQL credentials
4. Access via browser

### Default Admin Access
- URL: `/admin/login.html`
- Username: `admin`
- Password: `admin123`

## Features

### Customer Features
- 🍽️ Menu browsing with categories
- 🛒 Shopping cart functionality
- 📝 Online ordering (delivery/pickup)
- ⭐ Review and rating system
- 📅 Table reservation system
- 👤 User registration and login

### Admin Features
- 📊 Dashboard with statistics
- 🍽️ Menu management (CRUD operations)
- 📋 Order management and status updates
- 👥 User account management
- ⭐ Review moderation
- 📅 Reservation management

## Project Structure

```
restuarent/
├── database/
│   └── grand_restaurant.sql    # Complete database file
├── admin/                      # Admin panel
├── api/                        # REST API endpoints
├── classes/                    # PHP classes
├── config/                     # Configuration files
├── css/                        # Stylesheets
├── js/                         # JavaScript files
├── images/                     # Static images
├── uploads/                    # User uploaded files
└── [HTML files]               # Frontend pages
```

## Database

The system uses a single consolidated MySQL database file:
- **File**: `database/grand_restaurant.sql`
- **Tables**: 7 tables with complete relationships
- **Data**: Includes sample menu items, users, and reviews

## API Endpoints

- `api/menu.php` - Menu management
- `api/orders.php` - Order processing
- `api/reservations.php` - Reservation system
- `api/reviews.php` - Review management
- `api/auth.php` - User authentication

## Security

- Input sanitization and validation
- Prepared statements for SQL injection prevention
- Password hashing for user accounts
- CORS headers for API security

## Browser Support

Modern browsers including Chrome, Firefox, Safari, and Edge.

## License

This project is for educational/commercial use.
