<?php
// This file can be used to test if everything is working
// Access it: http://localhost/ovrly-ecommerce/test.php

require_once __DIR__ . '/config/database.php';

echo '<h1>OVRLY E-Commerce - System Check</h1>';
echo '<hr>';

// Check PHP Version
echo '<h3>✓ PHP Version</h3>';
echo 'Current: ' . phpversion() . ' (Required: 8.0+)<br>';

// Check Database Connection
echo '<h3>✓ Database Connection</h3>';
try {
    $pdo->query('SELECT 1');
    echo 'Status: <span style="color: green;">Connected to ' . DB_NAME . '</span><br>';
} catch (Exception $e) {
    echo 'Status: <span style="color: red;">Failed - ' . $e->getMessage() . '</span><br>';
}

// Check Database Tables
echo '<h3>✓ Database Tables</h3>';
$tables = [
    'users', 'admins', 'categories', 'products', 'product_images',
    'product_variants', 'wishlist', 'cart', 'cart_items', 'coupons',
    'addresses', 'orders', 'order_items', 'contacts'
];

foreach ($tables as $table) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    $exists = $stmt->rowCount() > 0;
    $status = $exists ? '<span style="color: green;">✓</span>' : '<span style="color: red;">✗</span>';
    echo "$status $table<br>";
}

// Check Upload Folders
echo '<h3>✓ Upload Directories</h3>';
$folders = ['uploads/profiles', 'uploads/products', 'assets/images/products'];
foreach ($folders as $folder) {
    $exists = is_dir(__DIR__ . '/' . $folder);
    $status = $exists ? '<span style="color: green;">✓</span>' : '<span style="color: red;">✗</span>';
    echo "$status $folder<br>";
}

// Check Admin Account
echo '<h3>✓ Admin Account</h3>';
$stmt = $pdo->prepare('SELECT id, email FROM admins WHERE email = ?');
$stmt->execute(['admin@ovrly.com']);
$admin = $stmt->fetch();
if ($admin) {
    echo '<span style="color: green;">✓</span> Admin account exists<br>';
    echo 'Email: admin@ovrly.com<br>';
    echo 'Password: Admin@123<br>';
} else {
    echo '<span style="color: red;">✗</span> Admin account not found<br>';
}

// Check Sample Products
echo '<h3>✓ Sample Data</h3>';
$stmt = $pdo->query('SELECT COUNT(*) as count FROM products');
$productCount = $stmt->fetch()['count'];
echo "Products: $productCount<br>";

$stmt = $pdo->query('SELECT COUNT(*) as count FROM categories');
$categoryCount = $stmt->fetch()['count'];
echo "Categories: $categoryCount<br>";

$stmt = $pdo->query('SELECT COUNT(*) as count FROM coupons');
$couponCount = $stmt->fetch()['count'];
echo "Coupons: $couponCount<br>";

echo '<hr>';
echo '<h3>✓ Setup Complete!</h3>';
echo '<p>Access website: <a href="index.html">http://localhost/ovrly-ecommerce/</a></p>';
echo '<p>Admin login: <a href="admin/login.php">http://localhost/ovrly-ecommerce/admin/login.php</a></p>';
?>