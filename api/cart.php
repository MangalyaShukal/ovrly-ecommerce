<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

$response = ['success' => false, 'message' => 'Unauthorized'];

if (!Auth::isLoggedIn()) {
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    
    if ($action === 'add') {
        $productId = $data['productId'] ?? 0;
        $variantId = $data['variantId'] ?? 0;
        $quantity = $data['quantity'] ?? 1;
        
        $stmt = $pdo->prepare('SELECT price FROM products WHERE id = ?');
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }
        
        $stmt = $pdo->prepare('SELECT id FROM cart WHERE user_id = ?');
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();
        
        if (!$cart) {
            $stmt = $pdo->prepare('INSERT INTO cart (user_id, created_at) VALUES (?, NOW())');
            $stmt->execute([$userId]);
            $cartId = $pdo->lastInsertId();
        } else {
            $cartId = $cart['id'];
        }
        
        $stmt = $pdo->prepare(
            'INSERT INTO cart_items (cart_id, product_id, variant_id, quantity, price) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)'
        );
        $stmt->execute([$cartId, $productId, $variantId, $quantity, $product['price']]);
        
        $response = ['success' => true, 'message' => 'Product added to cart'];
    }
    elseif ($action === 'remove') {
        $itemId = $data['itemId'] ?? 0;
        $stmt = $pdo->prepare('DELETE FROM cart_items WHERE id = ?');
        $stmt->execute([$itemId]);
        $response = ['success' => true, 'message' => 'Item removed from cart'];
    }
    elseif ($action === 'update') {
        $itemId = $data['itemId'] ?? 0;
        $quantity = $data['quantity'] ?? 1;
        
        if ($quantity < 1) $quantity = 1;
        
        $stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
        $stmt->execute([$quantity, $itemId]);
        $response = ['success' => true, 'message' => 'Cart updated'];
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare(
        'SELECT ci.*, p.name, p.stock FROM cart_items ci JOIN cart c ON ci.cart_id = c.id JOIN products p ON ci.product_id = p.id WHERE c.user_id = ?'
    );
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll();
    $response = ['success' => true, 'data' => $items];
}

echo json_encode($response);
?>