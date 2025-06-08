# Grand Restaurant Management System

A complete restaurant management system built with PHP and SQLite, featuring menu management, online ordering, reservations, and customer reviews.

## Features

- 🍽️ **Menu Management** - Add, edit, and manage restaurant menu items
- 📅 **Reservation System** - Online table booking with date/time selection
- 🛒 **Online Ordering** - Customer ordering system with delivery/pickup options
- ⭐ **Review System** - Customer reviews and ratings with admin approval
- 👥 **User Management** - Customer accounts and admin panel
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile devices

## Quick Setup

### Option 1: Using Setup Script (Recommended)

1. **Download/Clone the project** to your web server directory
2. **Run the setup script** in your browser or command line:
   ```bash
   # Fresh installation with sample data
   php setup.php
   
   # Restore with existing data (orders, reviews, users)
   php setup.php --restore
   ```
   OR visit: `http://localhost/restaurant/setup.php`

3. **Start using the system!**

### Option 2: Quick Database Restore

If you want to restore the database with all existing data quickly:
```bash
php restore_database.php
```

### Option 3: Manual Setup

1. **Create database** using the schema file:
   ```bash
   # Fresh database
   sqlite3 database/grand_restaurant.db < database/schema.sql
   
   # OR restore complete database with existing data
   sqlite3 database/grand_restaurant.db < database/grand_restaurant_complete.sql
   ```

2. **Set permissions**:
   ```bash
   chmod 666 database/grand_restaurant.db
   chmod 755 database/
   mkdir -p uploads/menu-items
   chmod 755 uploads/
   ```

## System Requirements

- **PHP 7.4 or higher**
- **SQLite3 support** (usually included with PHP)
- **Web server** (Apache, Nginx, or built-in PHP server)
- **GD extension** for image handling

## Default Admin Access

- **Username:** `admin`
- **Password:** `admin123`
- **Admin Panel:** `http://localhost/restaurant/admin/`

## Project Structure

```
restuarent/
├── database/
│   ├── grand_restaurant.db           # SQLite database file
│   ├── schema.sql                   # Database schema (fresh install)
│   └── grand_restaurant_complete.sql # Complete database with existing data
├── admin/                           # Admin panel files
├── api/                             # API endpoints
├── classes/                         # PHP classes
├── config/                          # Configuration files
├── css/                             # Stylesheets
├── js/                              # JavaScript files
├── images/                          # Static images
├── uploads/                         # User uploaded files
├── setup.php                        # Setup script
└── restore_database.php             # Quick restore script
```

## Configuration

Database configuration is in `config/database_sqlite.php`. The default setup uses SQLite for portability.

## Deployment to Other Computers

### Quick Transfer Method
1. **Copy the entire project folder**
2. **Choose your setup method**:
   - **Fresh start**: `php setup.php`
   - **With existing data**: `php setup.php --restore` or `php restore_database.php`
3. **Configure web server** to point to the project directory
4. **Access the system** via browser

### Database File Options
- `schema.sql` - Clean database with sample data
- `grand_restaurant_complete.sql` - Complete database with all existing data
- Use the restore script for quickest deployment with existing data

## Development

### Adding New Features

- **Database changes**: Update `database/schema.sql`
- **API endpoints**: Add files to `api/` directory
- **Classes**: Add to `classes/` directory
- **Frontend**: Update HTML/CSS/JS files

### Database Management

- **View data**: Use SQLite browser or command line
- **Backup**: Copy `database/grand_restaurant.db`
- **Reset**: Delete database file and run setup again

## Troubleshooting

### Common Issues

1. **Database permission errors**:
   ```bash
   chmod 666 database/grand_restaurant.db
   chmod 755 database/
   ```

2. **Image upload issues**:
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/menu-items/
   ```

3. **PHP SQLite not enabled**:
   - Ensure `php-sqlite3` extension is installed
   - Check `phpinfo()` for SQLite support

### File Permissions

```bash
# Set correct permissions
find . -type f -name "*.php" -exec chmod 644 {} \;
find . -type f -name "*.html" -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 666 database/grand_restaurant.db
```

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Verify system requirements
3. Ensure proper file permissions
4. Check web server error logs

## License

This project is for educational and commercial use. Modify as needed for your requirements.

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
