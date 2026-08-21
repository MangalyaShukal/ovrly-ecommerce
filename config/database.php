<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ovrly_ecommerce');

// Create connection using PDO
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
    );
} catch (PDOException $e) {
    die('Database Connection Error: ' . $e->getMessage());
}

// Set session configuration
ini_set('session.gc_maxlifetime', 86400); // 24 hours
ini_set('session.cookie_lifetime', 86400); // 24 hours
session_set_cookie_params(['lifetime' => 86400, 'path' => '/', 'samesite' => 'Lax']);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session security
session_regenerate_id(true);
?>