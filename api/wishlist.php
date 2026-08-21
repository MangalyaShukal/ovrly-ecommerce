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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $productId = $data['productId'] ?? 0;
    
    if ($action === 'add') {
        $stmt = $pdo->prepare('SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$userId, $productId]);
        
        if ($stmt->rowCount() > 0) {
            $response = ['success' => false, 'message' => 'Already in wishlist'];
        } else {
            $stmt = $pdo->prepare('INSERT INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())');
            $stmt->execute([$userId, $productId]);
            $response = ['success' => true, 'message' => 'Added to wishlist'];
        }
    }
    elseif ($action === 'remove') {
        $stmt = $pdo->prepare('DELETE FROM wishlist WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$userId, $productId]);
        $response = ['success' => true, 'message' => 'Removed from wishlist'];
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare(
        'SELECT p.* FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ? AND p.status = "active" ORDER BY w.created_at DESC'
    );
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll();
    $response = ['success' => true, 'data' => $items];
}

echo json_encode($response);
?>