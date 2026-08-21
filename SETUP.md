# OVRLY E-Commerce Platform - Complete Setup Guide

## ✨ Project Overview

**OVRLY** is a complete, fully functional e-commerce website for a premium Indian oversized T-shirt streetwear brand. Built with **PHP 8+**, **MySQL**, and **Vanilla JavaScript**, this project demonstrates real-world full-stack development suitable for a B.Tech IT college project.

**Tagline**: "Oversized. Unapologetic. OVRLY."

---

## 🚀 Quick Start

### System Requirements
- **XAMPP** (PHP 8.0+, MySQL 5.7+, Apache)
- **Windows/Mac/Linux** with 2GB free disk space
- **Modern web browser** (Chrome, Firefox, Safari, Edge)

### Installation Steps

#### Step 1: Extract Project
```bash
# Extract the ZIP file to XAMPP htdocs folder
# Windows: C:\xampp\htdocs\ovrly-ecommerce\
# Mac/Linux: /Applications/XAMPP/htdocs/ovrly-ecommerce/
```

#### Step 2: Start XAMPP Services
1. Open XAMPP Control Panel
2. Click **Start** for Apache
3. Click **Start** for MySQL

#### Step 3: Create Database
1. Open browser: `http://localhost/phpmyadmin`
2. Click **New** in left sidebar
3. Database name: `ovrly_ecommerce`
4. Click **Create**
5. Select the new database
6. Go to **Import** tab
7. Upload file: `database/ovrly_ecommerce.sql`
8. Click **Import**

#### Step 4: Create Upload Directories
```bash
# Create these folders manually (they might already exist):
# uploads/profiles/
# uploads/products/

# Windows: Right-click → New → Folder
# Mac/Linux: mkdir uploads/profiles uploads/products
```

#### Step 5: Access the Website
- **User Website**: http://localhost/ovrly-ecommerce/
- **Admin Panel**: Log in with admin credentials

---

## 👤 Default Credentials

### Admin Account
```
Email:    admin@ovrly.com
Password: Admin@123
```

### Test User Account (Optional)
```
Email:    user@test.com
Password: User@123
Status:   Already Activated (Ready to Login)
```

### How to Create New Users
1. Click **Login** on homepage
2. Switch to **Sign Up** tab
3. Fill all details (Phone: 10 digits)
4. New users are **blocked by default**
5. **Admin** must activate them from **Admin Panel → Users → Edit → Change Status to Active**

---

## 📂 Project Structure

```
ovrly-ecommerce/
├── index.html                 # Homepage
├── login.html                 # User Login/Register (Combined)
├── products.html              # Product Listing Page
├── product-details.html       # Individual Product Page
├── cart.html                  # Shopping Cart
├── wishlist.html              # User Wishlist
├── checkout.html              # Checkout Page
├── profile.html               # User Profile
├── orders.html                # Order History
├── about.html                 # About Page
├── contact.html               # Contact Us Form
│
├── config/
│   ├── database.php           # Database connection (PDO)
│   ├── auth.php               # User authentication class
│   ├── admin_auth.php         # Admin authentication class
│   └── functions.php          # Helper functions
│
├── api/
│   ├── login.php              # Login endpoint (User & Admin)
│   ├── register.php           # Registration endpoint
│   ├── logout.php             # Logout endpoint
│   ├── products.php           # Product APIs (list, search, detail)
│   ├── cart.php               # Cart management
│   ├── wishlist.php           # Wishlist management
│   ├── coupon.php             # Coupon validation
│   ├── checkout.php           # Order placement
│   ├── orders.php             # Order history
│   ├── contact.php            # Contact form submission
│   ├── profile.php            # Profile update
│   └── admin/
│       ├── dashboard.php      # Admin dashboard stats
│       ├── users.php          # Admin user management
│       └── logout.php         # Admin logout
│
├── admin/
│   ├── login.php              # Admin login page
│   ├── dashboard.php          # Admin dashboard
│   ├── users.php              # User management page
│   ├── products.php           # Product management
│   ├── categories.php         # Category management
│   ├── orders.php             # Order management
│   └── coupons.php            # Coupon management
│
├── assets/
│   ├── css/
│   │   ├── style.css          # Main stylesheet
│   │   └── admin.css          # Admin panel styles
│   ├── js/
│   │   ├── main.js            # Core JavaScript
│   │   ├── products.js        # Product page logic
│   │   ├── cart.js            # Cart logic
│   │   └── checkout.js        # Checkout logic
│   └── images/
│       ├── logo/              # Logo files
│       ├── products/          # Product images
│       └── placeholder.jpg    # Default image
│
├── uploads/
│   ├── profiles/              # User profile images
│   └── products/              # Product images (admin upload)
│
├── database/
│   └── ovrly_ecommerce.sql    # Complete database SQL
│
├── .gitignore                 # Git ignore file
├── README.md                  # Project documentation
└── SETUP.md                   # Setup instructions (this file)
```

---

## 🎯 Key Features

### ✅ User Features
- ✓ User registration with email validation
- ✓ Secure login with password hashing (bcrypt)
- ✓ OAuth ready (Google & Apple ID login)
- ✓ User account activation (Admin approval required)
- ✓ Product browsing with search & filters
- ✓ Product details with images
- ✓ Shopping cart with quantity management
- ✓ Wishlist functionality
- ✓ Coupon/promo code system
- ✓ Multi-step checkout process
- ✓ Order placement with stock validation
- ✓ Order history & tracking
- ✓ Reorder functionality
- ✓ User profile management
- ✓ Address management
- ✓ Contact form submission
- ✓ Responsive mobile design

### ✅ Admin Features
- ✓ Admin login (same page as user login)
- ✓ Dashboard with key statistics
- ✓ User management (activate/block/delete)
- ✓ Product management (CRUD)
- ✓ Product variants management
- ✓ Category management
- ✓ Coupon management
- ✓ Order management with status updates
- ✓ Order tracking & delivery management
- ✓ Contact message management
- ✓ Responsive admin panel

### ✅ Technical Features
- ✓ Session-based authentication
- ✓ PDO prepared statements (SQL injection prevention)
- ✓ Password hashing (bcrypt)
- ✓ Database transactions
- ✓ Input validation & sanitization
- ✓ RESTful API endpoints
- ✓ JSON responses
- ✓ Responsive CSS Grid
- ✓ Vanilla JavaScript (no frameworks)
- ✓ 25+ sample products
- ✓ 5 sample coupon codes
- ✓ 10 product categories

---

## 🛍️ Sample Products

The database includes **25 premium oversized T-shirt products** across 10 collections:

1. **Essential Oversized** - Basic premium tees
2. **Graphic Streetwear** - Bold graphic prints
3. **Minimal Collection** - Clean designs
4. **Vintage Wash** - Vintage effects
5. **Typography Collection** - Text-focused designs
6. **Drop Shoulder** - Extended shoulders
7. **Heavyweight Collection** - 300+ GSM fabric
8. **Summer Oversized** - Light summer wear
9. **Monochrome Collection** - Black & white only
10. **Limited Edition** - Exclusive drops

### Sample Product Names:
- OVRLY Core Black Oversized Tee (₹799)
- OVRLY Shadow Graphic Tee (₹899)
- OVRLY Midnight Drop Shoulder (₹999)
- OVRLY Heavyweight Black (₹1,099)
- OVRLY Limited Drop Navy (₹999)

### Sample Coupons:
- `OVRLY10` - 10% OFF (Min. ₹500)
- `DROP15` - 15% OFF (Min. ₹1000)
- `STREET20` - 20% OFF (Min. ₹1500)
- `FLAT100` - ₹100 OFF (Min. ₹2000)
- `WELCOME50` - ₹50 OFF (Min. ₹500)

---

## 🔐 Security Features

✓ **Password Hashing**: bcrypt with salt (PASSWORD_BCRYPT)
✓ **SQL Injection Prevention**: PDO prepared statements
✓ **Session Management**: Session regeneration after login
✓ **Input Validation**: Server-side validation
✓ **XSS Protection**: htmlspecialchars() escaping
✓ **CSRF Protection**: Session tokens
✓ **File Upload Validation**: Type & size checks
✓ **Database Transactions**: For order placement

---

## 📱 Responsive Design

✓ **Desktop** (1200px+): Full layout
✓ **Laptop** (992px-1199px): Optimized layout
✓ **Tablet** (768px-991px): Grid columns adjust
✓ **Mobile** (576px-767px): 2 column products
✓ **Small Mobile** (< 576px): 1 column layout

No horizontal scrolling on any device!

---

## 🔄 User Journey

### Registration & Login
1. User signs up → Account created with `status = blocked`
2. Admin activates user → `status = active`
3. User can now login
4. Session starts → Redirected to homepage
5. Name appears in navbar (replaces "Welcome Guest")

### Shopping Flow
1. Browse products on homepage/products page
2. View product details
3. Add to cart (quantity adjusts)
4. Add to wishlist (heart icon)
5. Proceed to cart
6. Adjust quantities
7. Apply coupon code
8. Proceed to checkout
9. Enter billing address
10. Select shipping (same or different)
11. Choose payment method (COD or Online Demo)
12. Place order → Order confirmed
13. View in order history
14. Track status
15. Reorder from history

### Admin Operations
1. Admin logs in (same login page, checkbox "Login as Admin")
2. Redirected to dashboard
3. View statistics
4. Manage users (activate/block)
5. Manage products
6. Manage orders & status
7. View contacts
8. Manage coupons

---

## 🗄️ Database Schema

### Key Tables
- **users** - User accounts with status
- **admins** - Admin accounts
- **products** - Product listings
- **categories** - Product categories
- **cart** & **cart_items** - Shopping cart
- **wishlist** - User wishlists
- **orders** & **order_items** - Order records
- **addresses** - Billing/shipping addresses
- **coupons** - Discount codes
- **contacts** - Contact form submissions

All tables have proper foreign keys and indexes for performance.

---

## ⚠️ Important Notes

### User Activation
- **New users are BLOCKED by default**
- Admin must manually activate them
- Blocked users cannot login
- Error message: "Your account has been blocked or is awaiting administrator approval."

### Stock Management
- Stock is checked at:
  - Add to cart
  - Checkout (fresh check)
  - Reorder (fresh check)
- Out-of-stock items cannot be ordered
- Stock decreases only after order confirmation

### Payment Simulation
- Online payment is simulated (not real)
- No card details are stored
- COD (Cash on Delivery) is functional
- Order is placed after checkout

### Coupon System
- Supports both percentage and fixed discounts
- Validates expiry date
- Checks usage limits
- Minimum order validation
- Maximum discount cap

---

## 🛠️ Troubleshooting

### Database Connection Error
**Problem**: "Database Connection Error: SQLSTATE[28000]"

**Solution**:
1. Check XAMPP MySQL is running
2. Verify credentials in `config/database.php`
3. Ensure database exists and is imported

### Login Not Working
**Problem**: "User not found" even with correct email

**Solution**:
1. Check if user is registered
2. Verify user status is "active" (not blocked)
3. Check database for typos in email

### Admin Login Not Working
**Problem**: Can't access admin panel

**Solution**:
1. Use email: `admin@ovrly.com`
2. Use password: `Admin@123`
3. Check "Login as Admin" checkbox

### Images Not Loading
**Problem**: Product images show as broken

**Solution**:
1. Images use placeholder format
2. Create actual product images in `assets/images/products/`
3. Name as: `product-1.jpg`, `product-2.jpg`, etc.
4. Or update image paths in database

### Upload Folder Errors
**Problem**: Can't upload profile images

**Solution**:
1. Create `uploads/profiles/` folder
2. Create `uploads/products/` folder
3. Set permissions: chmod 755 (Linux/Mac)
4. Restart Apache

### 404 Errors on Pages
**Problem**: Pages return 404

**Solution**:
1. Verify XAMPP Apache is running
2. Check project is in `htdocs` folder
3. Verify URLs use correct path
4. Clear browser cache (Ctrl+Shift+Del)

---

## 📊 Testing Checklist

- [ ] User registration works
- [ ] New users are blocked by default
- [ ] Admin can activate users
- [ ] Blocked users cannot login
- [ ] Login works for users
- [ ] Login works for admins
- [ ] Product search works
- [ ] Filters work
- [ ] Add to cart works
- [ ] Cart calculation is correct
- [ ] Wishlist works
- [ ] Coupon validation works
- [ ] Checkout process works
- [ ] Orders are created
- [ ] Stock decreases after order
- [ ] Order history shows orders
- [ ] Reorder works
- [ ] Reorder shows out-of-stock items
- [ ] Admin dashboard shows stats
- [ ] Admin can manage users
- [ ] Admin can update order status
- [ ] Contact form works
- [ ] Profile update works
- [ ] Password change works
- [ ] Logout works
- [ ] Responsive design works on mobile

---

## 📞 Support

For issues or questions:
- Email: support@ovrly.com
- Check README.md for more details
- Verify all setup steps completed
- Check browser console for JavaScript errors
- Check server logs for PHP errors

---

## 📜 License

© 2026 OVRLY. All Rights Reserved.

---

## 🎓 Academic Use

This project is designed as a **B.Tech IT college project** demonstrating:
- Full-stack web development
- Database design & management
- Security best practices
- Responsive web design
- Server-side & client-side validation
- RESTful API design
- User authentication & authorization
- E-commerce workflow

**Happy Coding! 🚀**

---

*Last Updated: 2026*
*Version: 1.0*
*Status: Production Ready*