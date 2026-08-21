<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

$response = ['success' => false, 'message' => 'Unauthorized'];

if (!Auth::isLoggedIn()) {
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'list') {
        $stmt = $pdo->prepare(
            'SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();
        $response = ['success' => true, 'data' => $orders];
    }
    elseif ($action === 'detail') {
        $orderId = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch();
        
        if ($order) {
            $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
            $stmt->execute([$orderId]);
            $order['items'] = $stmt->fetchAll();
            
            $stmt = $pdo->prepare('SELECT * FROM addresses WHERE id = ?');
            $stmt->execute([$order['billing_address_id']]);
            $order['billing'] = $stmt->fetch();
            
            $stmt = $pdo->prepare('SELECT * FROM addresses WHERE id = ?');
            $stmt->execute([$order['shipping_address_id']]);
            $order['shipping'] = $stmt->fetch();
            
            $response = ['success' => true, 'data' => $order];
        }
    }
}

echo json_encode($response);
?>