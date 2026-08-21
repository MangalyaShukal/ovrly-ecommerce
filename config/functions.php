<?php
require_once __DIR__ . '/database.php';

function getProductById($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ? AND status = "active"');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAllProducts($pdo, $limit = 20, $offset = 0) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE status = "active" LIMIT ? OFFSET ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function searchProducts($pdo, $search) {
    $search = '%' . $search . '%';
    $stmt = $pdo->prepare(
        'SELECT * FROM products WHERE status = "active" AND (name LIKE ? OR sku LIKE ? OR description LIKE ?) LIMIT 20'
    );
    $stmt->execute([$search, $search, $search]);
    return $stmt->fetchAll();
}

function getProductImages($pdo, $productId) {
    $stmt = $pdo->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC');
    $stmt->execute([$productId]);
    return $stmt->fetchAll();
}

function getProductVariants($pdo, $productId) {
    $stmt = $pdo->prepare('SELECT * FROM product_variants WHERE product_id = ?');
    $stmt->execute([$productId]);
    return $stmt->fetchAll();
}

function getCategories($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE status = "active"');
    $stmt->execute();
    return $stmt->fetchAll();
}

function formatPrice($price) {
    return '₹' . number_format($price, 2, '.', ',');
}

function validateCoupon($pdo, $code, $amount) {
    $stmt = $pdo->prepare(
        'SELECT * FROM coupons WHERE code = ? AND status = "active" AND NOW() BETWEEN start_date AND expiry_date AND usage_limit > used_count'
    );
    $stmt->execute([strtoupper($code)]);
    $coupon = $stmt->fetch();

    if (!$coupon) {
        return ['valid' => false, 'message' => 'Invalid or expired coupon'];
    }

    if ($amount < $coupon['minimum_order']) {
        return ['valid' => false, 'message' => 'Minimum order amount of ₹' . $coupon['minimum_order'] . ' required'];
    }

    $discount = ($coupon['discount_type'] === 'percentage')
        ? ($amount * $coupon['discount_value'] / 100)
        : $coupon['discount_value'];

    $discount = min($discount, $coupon['maximum_discount']);

    return ['valid' => true, 'coupon' => $coupon, 'discount' => $discount];
}

function generateOrderNumber($pdo) {
    $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = CURDATE()');
    $stmt->execute();
    $result = $stmt->fetch();
    $count = $result['count'] + 1;
    return 'OVRLY' . date('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT);
}

function sendEmail($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: support@ovrly.com" . "\r\n";
    return mail($to, $subject, $message, $headers);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>