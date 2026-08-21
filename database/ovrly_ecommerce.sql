CREATE DATABASE IF NOT EXISTS `ovrly_ecommerce` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ovrly_ecommerce`;

-- Users Table
CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(15) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `profile_image` VARCHAR(255),
    `status` ENUM('active', 'blocked') DEFAULT 'blocked',
    `role` ENUM('user', 'admin') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Accounts
CREATE TABLE `admins` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Admin
INSERT INTO `admins` (`name`, `email`, `password`, `status`) VALUES 
('OVRLY Admin', 'admin@ovrly.com', '$2y$10$8c7zKqsqsR9c8r6j9k2j9OuZ5m5m5m5m5m5m5m5m5m5m5m5m', 'active');
-- Password: Admin@123 (hashed)

-- Categories Table
CREATE TABLE `categories` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table
CREATE TABLE `products` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `category_id` INT NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `sku` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT,
    `price` DECIMAL(10, 2) NOT NULL,
    `discount_price` DECIMAL(10, 2),
    `stock` INT DEFAULT 0,
    `rating` DECIMAL(3, 1) DEFAULT 0,
    `review_count` INT DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Images Table
CREATE TABLE `product_images` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `product_id` INT NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `is_primary` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Variants Table
CREATE TABLE `product_variants` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `product_id` INT NOT NULL,
    `size` VARCHAR(10),
    `color` VARCHAR(50),
    `stock` INT DEFAULT 0,
    `sku` VARCHAR(100),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wishlist Table
CREATE TABLE `wishlist` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cart Table
CREATE TABLE `cart` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cart Items Table
CREATE TABLE `cart_items` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `cart_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `variant_id` INT,
    `quantity` INT DEFAULT 1,
    `price` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`cart_id`) REFERENCES `cart`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Coupons Table
CREATE TABLE `coupons` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `discount_type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
    `discount_value` DECIMAL(10, 2) NOT NULL,
    `minimum_order` DECIMAL(10, 2) DEFAULT 0,
    `maximum_discount` DECIMAL(10, 2) DEFAULT 999999,
    `start_date` DATE NOT NULL,
    `expiry_date` DATE NOT NULL,
    `usage_limit` INT DEFAULT 100,
    `used_count` INT DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Addresses Table
CREATE TABLE `addresses` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `address_type` ENUM('billing', 'shipping') DEFAULT 'billing',
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(15) NOT NULL,
    `address` TEXT NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `state` VARCHAR(50) NOT NULL,
    `pincode` VARCHAR(10) NOT NULL,
    `is_default` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders Table
CREATE TABLE `orders` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT NOT NULL,
    `coupon_id` INT,
    `subtotal` DECIMAL(10, 2) NOT NULL,
    `discount` DECIMAL(10, 2) DEFAULT 0,
    `delivery_charge` DECIMAL(10, 2) DEFAULT 100,
    `tax` DECIMAL(10, 2) DEFAULT 0,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `payment_method` ENUM('cod', 'online') DEFAULT 'cod',
    `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    `order_status` ENUM('pending', 'confirmed', 'processing', 'packed', 'dispatched', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
    `billing_address_id` INT,
    `shipping_address_id` INT,
    `dispatch_date` DATETIME,
    `delivery_date` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`coupon_id`) REFERENCES `coupons`(`id`),
    FOREIGN KEY (`billing_address_id`) REFERENCES `addresses`(`id`),
    FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items Table
CREATE TABLE `order_items` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `variant_id` INT,
    `product_name` VARCHAR(150) NOT NULL,
    `size` VARCHAR(10),
    `color` VARCHAR(50),
    `quantity` INT NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `subtotal` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Messages Table
CREATE TABLE `contacts` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(15),
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('new', 'read', 'replied') DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Categories
INSERT INTO `categories` (`name`, `slug`, `description`, `status`) VALUES
('Essential Oversized', 'essential-oversized', 'Premium essential oversized tees', 'active'),
('Graphic Streetwear', 'graphic-streetwear', 'Bold graphic printed tees', 'active'),
('Minimal Collection', 'minimal-collection', 'Clean minimalist designs', 'active'),
('Vintage Wash', 'vintage-wash', 'Vintage washed effects', 'active'),
('Typography Collection', 'typography-collection', 'Type-focused designs', 'active'),
('Drop Shoulder', 'drop-shoulder', 'Extended shoulder oversized fit', 'active'),
('Heavyweight Collection', 'heavyweight-collection', 'Thick 300+ GSM fabric', 'active'),
('Summer Oversized', 'summer-oversized', 'Light summer collection', 'active'),
('Monochrome Collection', 'monochrome-collection', 'Black and white only', 'active'),
('Limited Edition', 'limited-edition', 'Exclusive limited drops', 'active');

-- Sample Products (25 products)
INSERT INTO `products` (`category_id`, `name`, `slug`, `sku`, `description`, `price`, `discount_price`, `stock`, `rating`, `status`) VALUES
(1, 'OVRLY Core Black Oversized Tee', 'ovrly-core-black', 'OVRLY-001', 'Essential black oversized tee in premium 100% combed cotton', 899, 799, 50, 4.5, 'active'),
(1, 'OVRLY Essential White Tee', 'ovrly-essential-white', 'OVRLY-002', 'Pure white oversized essential. Perfect everyday staple', 899, 799, 45, 4.7, 'active'),
(2, 'OVRLY Shadow Graphic Tee', 'ovrly-shadow-graphic', 'OVRLY-003', 'Bold shadow graphic with oversized silhouette', 999, 899, 30, 4.3, 'active'),
(4, 'OVRLY Vintage Wash Grey', 'ovrly-vintage-grey', 'OVRLY-004', 'Vintage washed grey with distressed details', 1099, 999, 25, 4.6, 'active'),
(5, 'OVRLY Street Code Typography', 'ovrly-street-code', 'OVRLY-005', 'Street code typography on premium oversized cut', 999, 899, 35, 4.4, 'active'),
(1, 'OVRLY Minimal Cream Tee', 'ovrly-minimal-cream', 'OVRLY-006', 'Minimalist cream with subtle embroidery', 899, 799, 40, 4.5, 'active'),
(6, 'OVRLY Midnight Drop Shoulder', 'ovrly-midnight-drop', 'OVRLY-007', 'Extended drop shoulder in midnight black', 1099, 999, 20, 4.8, 'active'),
(5, 'OVRLY Urban Type Tee', 'ovrly-urban-type', 'OVRLY-008', 'Urban typeface design on oversized fit', 999, 899, 28, 4.4, 'active'),
(7, 'OVRLY Heavyweight Black', 'ovrly-heavyweight-black', 'OVRLY-009', '300 GSM heavyweight black oversized', 1299, 1099, 15, 4.9, 'active'),
(4, 'OVRLY Retro Brown Tee', 'ovrly-retro-brown', 'OVRLY-010', 'Retro brown vintage wash oversized', 1099, 999, 22, 4.5, 'active'),
(1, 'OVRLY Olive Street Tee', 'ovrly-olive-street', 'OVRLY-011', 'Olive green premium oversized essential', 999, 899, 33, 4.3, 'active'),
(9, 'OVRLY Mono Logo Tee', 'ovrly-mono-logo', 'OVRLY-012', 'Monochrome logo print oversized', 899, 799, 44, 4.6, 'active'),
(10, 'OVRLY Limited Drop Navy', 'ovrly-limited-navy', 'OVRLY-013', 'Limited edition navy oversized drop', 1199, 999, 10, 4.7, 'active'),
(1, 'OVRLY College Street Tee', 'ovrly-college-street', 'OVRLY-014', 'College inspired street style oversized', 899, 799, 38, 4.4, 'active'),
(2, 'OVRLY Abstract Graphic', 'ovrly-abstract-graphic', 'OVRLY-015', 'Abstract geometric graphic print', 1099, 999, 26, 4.5, 'active'),
(1, 'OVRLY Basic Beige', 'ovrly-basic-beige', 'OVRLY-016', 'Neutral beige oversized tee', 799, 699, 52, 4.3, 'active'),
(3, 'OVRLY Minimal Black Logo', 'ovrly-minimal-black-logo', 'OVRLY-017', 'Simple minimal black logo print', 899, 799, 41, 4.6, 'active'),
(4, 'OVRLY Faded Blue Wash', 'ovrly-faded-blue-wash', 'OVRLY-018', 'Faded blue vintage wash oversized', 999, 899, 28, 4.4, 'active'),
(6, 'OVRLY Drop Sleeve White', 'ovrly-drop-sleeve-white', 'OVRLY-019', 'White extended drop shoulder tee', 1099, 999, 19, 4.7, 'active'),
(7, 'OVRLY Heavyweight Olive', 'ovrly-heavyweight-olive', 'OVRLY-020', 'Heavy weight olive green oversized', 1299, 1099, 12, 4.8, 'active'),
(2, 'OVRLY Bold Statement Tee', 'ovrly-bold-statement', 'OVRLY-021', 'Bold statement graphic on oversized', 1099, 999, 24, 4.5, 'active'),
(5, 'OVRLY Typography Maroon', 'ovrly-typography-maroon', 'OVRLY-022', 'Maroon typography print oversized', 999, 899, 31, 4.4, 'active'),
(10, 'OVRLY Limited Charcoal', 'ovrly-limited-charcoal', 'OVRLY-023', 'Limited edition charcoal oversized', 1199, 999, 8, 4.9, 'active'),
(1, 'OVRLY Essential Navy', 'ovrly-essential-navy', 'OVRLY-024', 'Navy blue essential oversized', 899, 799, 46, 4.5, 'active'),
(3, 'OVRLY Clean Minimal White', 'ovrly-clean-minimal', 'OVRLY-025', 'Clean minimal white oversized premium', 899, 799, 43, 4.6, 'active');

-- Sample Coupons
INSERT INTO `coupons` (`code`, `discount_type`, `discount_value`, `minimum_order`, `maximum_discount`, `start_date`, `expiry_date`, `usage_limit`, `status`) VALUES
('OVRLY10', 'percentage', 10, 500, 500, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 100, 'active'),
('DROP15', 'percentage', 15, 1000, 1000, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 50, 'active'),
('STREET20', 'percentage', 20, 1500, 2000, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 30, 'active'),
('FLAT100', 'fixed', 100, 2000, 100, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 75, 'active'),
('WELCOME50', 'fixed', 50, 500, 50, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 60 DAY), 200, 'active');

-- Create Indexes
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_product_category ON products(category_id);
CREATE INDEX idx_product_status ON products(status);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_order_status ON orders(order_status);
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_wishlist_user ON wishlist(user_id);
CREATE INDEX idx_wishlist_product ON wishlist(product_id);
CREATE INDEX idx_coupon_code ON coupons(code);
CREATE INDEX idx_contact_email ON contacts(email);