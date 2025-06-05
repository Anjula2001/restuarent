# Grand Restaurant - Complete Full-Stack Application

## 🍽️ MAMP READY - Complete Setup!

This restaurant management system is **fully configured for MAMP** with MySQL database support. 

### ⚡ Quick Start (5 minutes):
1. **Place in MAMP htdocs**: `/Applications/MAMP/htdocs/restuarent/`
2. **Start MAMP servers** (Apache: 8888, MySQL: 8889)  
3. **Create database** in phpMyAdmin: `grand_restaurant`
4. **Import schema**: `database/schema.sql`
5. **Visit Demo**: http://localhost:8888/restuarent/mamp_demo.html

### 🎯 MAMP Quick Links:
- 🚀 **Interactive Demo**: http://localhost:8888/restuarent/mamp_demo.html
- 🔧 **System Test**: http://localhost:8888/restuarent/api/test_mamp.php
- 🏠 **Main Website**: http://localhost:8888/restuarent/
- ⚙️ **Admin Panel**: http://localhost:8888/restuarent/admin/
- 🗄️ **phpMyAdmin**: http://localhost:8888/phpmyadmin/

### ✅ What's Included:
- Complete MySQL database integration
- All APIs working with MAMP
- Interactive demo with live testing
- Admin dashboard
- Comprehensive documentation
- Automated setup verification

## 🎉 SYSTEM STATUS: FULLY OPERATIONAL!

This is a **complete, working restaurant management system** with PHP backend, SQLite database, RESTful APIs, admin dashboard, and dynamic frontend integration.

## ✅ What's Working
- ✅ **Dynamic Menu System** - Real-time loading with categories and search
- ✅ **Order Management** - Complete cart system with checkout
- ✅ **Reservation System** - Time slot management with availability checking  
- ✅ **Review System** - Customer reviews with approval workflow
- ✅ **Admin Dashboard** - Full management interface
- ✅ **RESTful APIs** - All endpoints tested and functional
- ✅ **Database Integration** - SQLite with sample data
- ✅ **Frontend Integration** - Dynamic JavaScript interactions

## 🚀 Quick Start (Ready to Run!)

### Option 1: Instant Demo (Recommended)
```bash
# Navigate to the restaurant directory
cd /path/to/restaurant

# Start PHP development server
php -S localhost:8000

# Open in browser:
http://localhost:8000/demo.html        # System demo
http://localhost:8000/index.html       # Main website  
http://localhost:8000/admin/index.html # Admin dashboard
```

### Option 2: Production Setup
For MySQL instead of SQLite, update the API files to use `database.php` instead of `database_sqlite.php`

## 💾 Database (SQLite - No Setup Required!)
- **Location**: `api/database/grand_restaurant.db` 
- **Auto-created** with sample data on first run
- **6 Tables**: menu_items, reservations, orders, order_items, reviews, admin_users

For Apache, add this to your `.htaccess` file:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^api/(.*)$ api/$1.php [QSA,L]
```

## API Endpoints

### Menu API (`/api/menu.php`)

#### GET - Retrieve Menu Items
- `GET /api/menu.php` - Get all menu items
- `GET /api/menu.php?category=Traditional` - Get items by category
- `GET /api/menu.php?search=kottu` - Search menu items
- `GET /api/menu.php?id=1` - Get specific menu item
- `GET /api/menu.php?categories=1` - Get all categories

#### POST - Add Menu Item (Admin)
```json
{
  "name": "New Dish",
  "description": "Delicious new dish",
  "price": 750.00,
  "category": "Traditional",
  "image_url": "images/new-dish.jpg"
}
```

#### PUT - Update Menu Item (Admin)
```json
{
  "id": 1,
  "name": "Updated Dish",
  "description": "Updated description",
  "price": 800.00,
  "category": "Traditional",
  "image_url": "images/updated-dish.jpg",
  "is_available": 1
}
```

#### DELETE - Remove Menu Item (Admin)
- `DELETE /api/menu.php?id=1`

### Reservations API (`/api/reservations.php`)

#### GET - Retrieve Reservations
- `GET /api/reservations.php` - Get all reservations (Admin)
- `GET /api/reservations.php?email=user@email.com` - Get reservations by email
- `GET /api/reservations.php?id=1` - Get specific reservation
- `GET /api/reservations.php?available_slots=1&date=2025-06-15` - Get available time slots

#### POST - Create Reservation
```json
{
  "name": "John Doe",
  "email": "john@email.com",
  "phone": "+1234567890",
  "date": "2025-06-15",
  "time": "19:00",
  "guests": 4,
  "special_requests": "Window table preferred"
}
```

#### PUT - Update Reservation Status (Admin)
```json
{
  "id": 1,
  "status": "confirmed"
}
```

#### DELETE - Cancel Reservation
- `DELETE /api/reservations.php?id=1`

### Orders API (`/api/orders.php`)

#### GET - Retrieve Orders
- `GET /api/orders.php` - Get all orders (Admin)
- `GET /api/orders.php?email=user@email.com` - Get orders by email
- `GET /api/orders.php?id=1` - Get specific order
- `GET /api/orders.php?statistics=1` - Get order statistics (Admin)

#### POST - Create Order
```json
{
  "customer_name": "Jane Smith",
  "customer_email": "jane@email.com",
  "customer_phone": "+1234567890",
  "delivery_address": "123 Main St, City",
  "order_type": "delivery",
  "items": [
    {
      "menu_item_id": 1,
      "quantity": 2,
      "price": 450.00
    },
    {
      "menu_item_id": 2,
      "quantity": 1,
      "price": 650.00
    }
  ]
}
```

#### PUT - Update Order Status (Admin)
```json
{
  "id": 1,
  "status": "preparing"
}
```

### Reviews API (`/api/reviews.php`)

#### GET - Retrieve Reviews
- `GET /api/reviews.php` - Get approved reviews (public)
- `GET /api/reviews.php?limit=5` - Get limited number of reviews
- `GET /api/reviews.php?admin=1` - Get all reviews (Admin)
- `GET /api/reviews.php?admin=1&approved=0` - Get pending reviews (Admin)
- `GET /api/reviews.php?statistics=1` - Get review statistics
- `GET /api/reviews.php?email=user@email.com` - Get reviews by email

#### POST - Submit Review
```json
{
  "customer_name": "Bob Johnson",
  "customer_email": "bob@email.com",
  "rating": 5,
  "review_text": "Excellent food and service!"
}
```

#### PUT - Update Review Status (Admin)
```json
{
  "id": 1,
  "action": "approve"
}
```

#### DELETE - Delete Review (Admin)
- `DELETE /api/reviews.php?id=1`

## Database Schema

### Tables

#### `menu_items`
- `id` (INT, Primary Key)
- `name` (VARCHAR 255)
- `description` (TEXT)
- `price` (DECIMAL 10,2)
- `category` (VARCHAR 100)
- `image_url` (VARCHAR 255)
- `is_available` (BOOLEAN)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

#### `reservations`
- `id` (INT, Primary Key)
- `name` (VARCHAR 255)
- `email` (VARCHAR 255)
- `phone` (VARCHAR 20)
- `date` (DATE)
- `time` (TIME)
- `guests` (INT)
- `special_requests` (TEXT)
- `status` (ENUM: pending, confirmed, cancelled)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

#### `orders`
- `id` (INT, Primary Key)
- `customer_name` (VARCHAR 255)
- `customer_email` (VARCHAR 255)
- `customer_phone` (VARCHAR 20)
- `delivery_address` (TEXT)
- `order_type` (ENUM: delivery, pickup)
- `total_amount` (DECIMAL 10,2)
- `status` (ENUM: pending, preparing, ready, delivered, cancelled)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

#### `order_items`
- `id` (INT, Primary Key)
- `order_id` (INT, Foreign Key)
- `menu_item_id` (INT, Foreign Key)
- `quantity` (INT)
- `price` (DECIMAL 10,2)

#### `reviews`
- `id` (INT, Primary Key)
- `customer_name` (VARCHAR 255)
- `customer_email` (VARCHAR 255)
- `rating` (INT, 1-5)
- `review_text` (TEXT)
- `is_approved` (BOOLEAN)
- `created_at` (TIMESTAMP)

#### `admin_users`
- `id` (INT, Primary Key)
- `username` (VARCHAR 100)
- `email` (VARCHAR 255)
- `password_hash` (VARCHAR 255)
- `role` (ENUM: admin, manager)
- `created_at` (TIMESTAMP)

## Classes

### MenuManager
Handles all menu-related operations including CRUD operations, searching, and category management.

### ReservationManager
Manages restaurant reservations, time slot availability, and reservation status updates.

### OrderManager
Processes customer orders, manages order status, and provides order statistics.

### ReviewManager
Handles customer reviews, approval workflow, and review statistics.

## Admin Dashboard
Access the admin dashboard at `/admin/index.html` to:
- View order statistics
- Manage orders and update status
- Handle reservations
- Approve/reject reviews
- View menu items

Default admin credentials:
- Username: admin
- Password: admin123

## Security Features
- Input sanitization and validation
- Prepared statements to prevent SQL injection
- CORS headers for API access
- Password hashing for admin users

## Frontend Integration
The frontend JavaScript automatically integrates with the backend APIs to:
- Load menu items dynamically
- Display customer reviews
- Handle search functionality
- Process reservations and orders
- Show real-time notifications

## Error Handling
All APIs return appropriate HTTP status codes and JSON error messages:
- 200: Success
- 201: Created
- 400: Bad Request
- 404: Not Found
- 405: Method Not Allowed
- 500: Internal Server Error

## Sample Data
The schema includes sample data:
- 6 menu items including Sri Lankan specialties
- 3 approved customer reviews
- 1 admin user account

## Testing the APIs
You can test the APIs using tools like:
- Postman
- cURL
- Browser developer tools
- The provided admin dashboard

Example cURL command:
```bash
curl -X GET "http://localhost/restuarent/api/menu.php"
```

## Troubleshooting

### Common Issues
1. **Database Connection Error**: Check database credentials in `config/database.php`
2. **CORS Issues**: Ensure CORS headers are properly set
3. **404 Errors**: Check web server URL rewriting configuration
4. **Permission Errors**: Ensure proper file permissions for PHP files

### Debug Mode
Enable error reporting by adding to the top of your PHP files:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Future Enhancements
- JWT authentication for admin panel
- Email notifications for reservations and orders
- Payment gateway integration
- Customer loyalty program
- Advanced reporting and analytics
- Multi-language support
- Mobile app API extensions
