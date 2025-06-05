# Grand Restaurant - MAMP Setup Guide

## Prerequisites
- MAMP installed on your Mac
- Project folder placed in MAMP's `htdocs` directory

## MAMP Configuration

### 1. Install and Start MAMP
1. Download and install MAMP from https://www.mamp.info/
2. Start MAMP application
3. Click "Start Servers" to start Apache and MySQL

### 2. MAMP Settings
- **Apache Port**: 8888 (default)
- **MySQL Port**: 8889 (default)
- **Document Root**: Should point to MAMP's `htdocs` folder

### 3. Project Placement
Copy the `restuarent` folder to your MAMP htdocs directory:
```
/Applications/MAMP/htdocs/restuarent/
```

## Database Setup

### 1. Access phpMyAdmin
1. Open MAMP
2. Click "WebStart" or go to: http://localhost:8888/MAMP/
3. Click on "phpMyAdmin"
4. Login with:
   - Username: `root`
   - Password: `root`

### 2. Create Database
1. In phpMyAdmin, click "New" to create a new database
2. Name it: `grand_restaurant`
3. Set Collation to: `utf8mb4_general_ci`
4. Click "Create"

### 3. Import Database Schema
1. Select the `grand_restaurant` database
2. Click on "Import" tab
3. Choose file: `/database/schema.sql`
4. Click "Go" to execute

## Configuration Files

### Database Connection (config/database.php)
The project is pre-configured for MAMP with these settings:
- Host: `localhost`
- Port: `8889`
- Username: `root`
- Password: `root`
- Database: `grand_restaurant`

## Access URLs

Once setup is complete, access the project at:

### Frontend
- **Main Website**: http://localhost:8888/restuarent/
- **Menu Page**: http://localhost:8888/restuarent/menu.html
- **Reservations**: http://localhost:8888/restuarent/reservation.html
- **Orders**: http://localhost:8888/restuarent/order.html

### Backend APIs
- **Menu API**: http://localhost:8888/restuarent/api/menu.php
- **Reservations API**: http://localhost:8888/restuarent/api/reservations.php
- **Orders API**: http://localhost:8888/restuarent/api/orders.php
- **Reviews API**: http://localhost:8888/restuarent/api/reviews.php

### Admin Panel
- **Admin Dashboard**: http://localhost:8888/restuarent/admin/

## Testing the Setup

### Quick Test
Visit: http://localhost:8888/restuarent/api/test.php
This will test the database connection and show sample data.

### API Testing
You can test the APIs using curl or a tool like Postman:

```bash
# Test menu API
curl http://localhost:8888/restuarent/api/menu.php

# Test creating a reservation
curl -X POST http://localhost:8888/restuarent/api/reservations.php \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "555-0123",
    "date": "2025-06-15",
    "time": "19:00",
    "guests": 4,
    "special_requests": "Window table please"
  }'
```

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check MAMP MySQL is running
   - Verify port 8889 in database.php
   - Ensure username/password is root/root

2. **404 Error on APIs**
   - Check project is in htdocs folder
   - Verify MAMP Apache is running on port 8888
   - Check file permissions

3. **CORS Errors**
   - APIs include CORS headers
   - If issues persist, check Apache configuration

### File Permissions
If you encounter permission issues:
```bash
chmod -R 755 /Applications/MAMP/htdocs/restuarent/
```

## Features Available

- ✅ Dynamic menu loading from database
- ✅ Online reservation system
- ✅ Order management system
- ✅ Customer review system
- ✅ Admin dashboard for management
- ✅ RESTful API endpoints
- ✅ Responsive design
- ✅ Search functionality
- ✅ Shopping cart system

## Admin Access

Default admin credentials (can be changed in database):
- **Username**: admin
- **Password**: password (hashed in database)

## Support

For issues or questions:
1. Check MAMP logs in MAMP application
2. Check browser console for JavaScript errors
3. Verify database connection in phpMyAdmin
4. Test API endpoints individually
