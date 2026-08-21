# OVRLY E-Commerce Platform

**Oversized. Unapologetic. OVRLY.**

A complete full-stack e-commerce website for a premium Indian oversized T-shirt streetwear brand.

## Features

✅ User Registration & Login (with Google & Apple OAuth)  
✅ Admin Dashboard (Separate login on same page)  
✅ Product Management (25+ oversized T-shirt products)  
✅ Dynamic Product Listing & Search  
✅ Advanced Filtering & Sorting  
✅ Wishlist System  
✅ Shopping Cart with Stock Validation  
✅ Coupon/Promo Code System  
✅ Real Payment Gateway Integration  
✅ Order Management & Tracking  
✅ Reorder Functionality  
✅ User Profile Management  
✅ Order Status Timeline  
✅ Delivery/Dispatch Management  
✅ Contact Us & FAQ Pages  
✅ Responsive Design (Mobile, Tablet, Desktop)  
✅ Security: Password Hashing, PDO Prepared Statements, Session Management  

## Technology Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: PHP 8+
- **Database**: MySQL
- **Server**: Apache (XAMPP)
- **Authentication**: Session-based + OAuth (Google, Apple)
- **Payment**: Real Payment Gateway (Razorpay/Stripe)

## Installation

### Prerequisites
- XAMPP installed
- PHP 8.0+
- MySQL 5.7+

### Setup Steps

1. **Extract the project** to `C:\xampp\htdocs\ovrly-ecommerce\`

2. **Start XAMPP**
   - Start Apache Server
   - Start MySQL Database

3. **Create Database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Import `database/ovrly_ecommerce.sql`

4. **Configure Database** (if needed)
   - Edit `config/database.php`
   - Update DB_HOST, DB_USER, DB_PASS, DB_NAME

5. **Create Upload Directories**
   ```bash
   mkdir uploads/profiles
   mkdir uploads/products
   ```

6. **Access the Website**
   - User Website: http://localhost/ovrly-ecommerce/
   - Admin Login: Same login page (use admin credentials)

## Admin Credentials

**Email**: admin@ovrly.com  
**Password**: Admin@123

## Default User Account (for testing)

**Email**: user@test.com  
**Password**: User@123

*Note: New users are blocked by default. Admin must activate them.*

## User Login (OAuth)

- Google Login: [Configured]
- Apple ID Login: [Configured]
- Email/Password: Standard login

## Project Structure

```
ovrly-ecommerce/
├── config/
│   ├── database.php
│   ├── auth.php
│   └── admin_auth.php
├── api/
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── products.php
│   ├── cart.php
│   ├── wishlist.php
│   ├── coupon.php
│   ├── checkout.php
│   └── orders.php
├── admin/
│   ├── login.php
│   ├── dashboard.php
│   ├── users.php
│   ├── products.php
│   ├── categories.php
│   ├── orders.php
│   └── coupons.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/
│   ├── profiles/
│   └── products/
├── database/
│   └── ovrly_ecommerce.sql
├── index.html
├── about.html
├── products.html
├── product-details.html
├── login.html
├── register.html
├── profile.html
├── wishlist.html
├── cart.html
├── checkout.html
├── orders.html
└── contact.html
```

## Database Schema

### Tables
- `users` - User accounts
- `admins` - Admin accounts
- `categories` - Product categories
- `products` - Product listings
- `product_images` - Multiple images per product
- `product_variants` - Size/Color variants
- `wishlist` - User wishlist
- `cart` - Shopping cart
- `cart_items` - Cart items
- `coupons` - Coupon codes
- `addresses` - Billing/Shipping addresses
- `orders` - Order records
- `order_items` - Items in orders
- `contacts` - Contact form submissions

## Key Features Details

### Authentication
- User registration with email verification
- Admin login with elevated privileges
- Session-based authentication
- Password hashing with bcrypt
- OAuth integration (Google, Apple)
- Account blocking/activation by admin

### Products
- 25-30 premium oversized T-shirt products
- Multiple product images
- Size variants (S, M, L, XL, XXL)
- Color variants (Black, White, Beige, Grey, Navy, etc.)
- Dynamic stock management
- Product ratings and reviews

### Shopping
- Advanced product filtering (price, size, color, category)
- Sorting options (price, popularity, newest)
- Global search functionality
- Wishlist management
- Cart with quantity validation
- Real-time stock checking

### Checkout & Payment
- Multi-step checkout process
- Billing and shipping address management
- Real payment gateway integration
- Order confirmation and tracking
- Invoice generation

### Admin Panel
- User management (activate/block/delete)
- Product CRUD operations
- Inventory management
- Order management and status updates
- Coupon management
- Delivery tracking
- Dashboard analytics

## Security Features

✅ Prepared SQL statements (PDO)  
✅ Password hashing with bcrypt  
✅ Session regeneration  
✅ CSRF token validation  
✅ Input sanitization  
✅ File upload validation  
✅ SQL injection prevention  
✅ XSS protection  

## Troubleshooting

**Database connection error?**
- Check XAMPP MySQL is running
- Verify config/database.php credentials
- Ensure database is imported

**404 errors on pages?**
- Ensure project is in htdocs folder
- Apache should be running
- Check .htaccess file

**Can't upload files?**
- Create uploads/profiles and uploads/products folders
- Check folder permissions (chmod 755)

## Support

For issues or questions, please refer to the documentation or contact admin@ovrly.com

## License

© 2026 OVRLY. All Rights Reserved.
