<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/admin_auth.php';

if (!AdminAuth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$stmt = $pdo->prepare(
    'SELECT COUNT(*) as count FROM users'
);
$stmt->execute();
$totalUsers = $stmt->fetch()['count'];

$stmt = $pdo->prepare(
    'SELECT COUNT(*) as count FROM users WHERE status = "active"'
);
$stmt->execute();
$activeUsers = $stmt->fetch()['count'];

$stmt = $pdo->prepare(
    'SELECT COUNT(*) as count FROM users WHERE status = "blocked"'
);
$stmt->execute();
$blockedUsers = $stmt->fetch()['count'];

$stmt = $pdo->prepare(
    'SELECT COUNT(*) as count FROM products WHERE status = "active"'
);
$stmt->execute();
$totalProducts = $stmt->fetch()['count'];

$stmt = $pdo->prepare(
    'SELECT COUNT(*) as count FROM orders'
);
$stmt->execute();
$totalOrders = $stmt->fetch()['count'];

$stmt = $pdo->prepare(
    'SELECT COUNT(*) as count FROM orders WHERE order_status = "pending"'
);
$stmt->execute();
$pendingOrders = $stmt->fetch()['count'];

// Get recent orders
$stmt = $pdo->prepare(
    'SELECT id, order_number, user_id, total_amount, order_status, created_at FROM orders ORDER BY created_at DESC LIMIT 10'
);
$stmt->execute();
$recentOrders = $stmt->fetchAll();

$stats = [
    'totalUsers' => $totalUsers,
    'activeUsers' => $activeUsers,
    'blockedUsers' => $blockedUsers,
    'totalProducts' => $totalProducts,
    'totalOrders' => $totalOrders,
    'pendingOrders' => $pendingOrders
];

echo json_encode(['success' => true, 'stats' => $stats, 'recentOrders' => $recentOrders]);
?>