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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['user_id'];
    $billingAddress = $data['billingAddress'] ?? [];
    $shippingAddress = $data['shippingAddress'] ?? [];
    $paymentMethod = $data['paymentMethod'] ?? 'cod';
    $couponCode = $data['couponCode'] ?? '';
    
    try {
        $pdo->beginTransaction();
        
        // Get cart
        $stmt = $pdo->prepare('SELECT id FROM cart WHERE user_id = ?');
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();
        
        if (!$cart) {
            throw new Exception('Cart not found');
        }
        
        // Get cart items
        $stmt = $pdo->prepare(
            'SELECT ci.*, p.price, p.stock FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?'
        );
        $stmt->execute([$cart['id']]);
        $items = $stmt->fetchAll();
        
        if (empty($items)) {
            throw new Exception('Cart is empty');
        }
        
        // Calculate subtotal and validate stock
        $subtotal = 0;
        foreach ($items as $item) {
            if ($item['quantity'] > $item['stock']) {
                throw new Exception('Product " ' . $item['product_id'] . '" is out of stock');
            }
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Apply coupon
        $discount = 0;
        $couponId = null;
        if (!empty($couponCode)) {
            $couponResult = validateCoupon($pdo, $couponCode, $subtotal);
            if ($couponResult['valid']) {
                $discount = $couponResult['discount'];
                $couponId = $couponResult['coupon']['id'];
            }
        }
        
        // Calculate total
        $deliveryCharge = 100;
        $tax = ($subtotal - $discount) * 0.18;
        $totalAmount = $subtotal - $discount + $deliveryCharge + $tax;
        
        // Save billing address
        $stmt = $pdo->prepare(
            'INSERT INTO addresses (user_id, address_type, full_name, phone, address, city, state, pincode, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $userId,
            'billing',
            $billingAddress['fullName'] ?? '',
            $billingAddress['phone'] ?? '',
            $billingAddress['address'] ?? '',
            $billingAddress['city'] ?? '',
            $billingAddress['state'] ?? '',
            $billingAddress['pincode'] ?? ''
        ]);
        $billingAddressId = $pdo->lastInsertId();
        
        // Save shipping address
        $shippingAddressId = $billingAddressId;
        if (!empty($shippingAddress['fullName'])) {
            $stmt = $pdo->prepare(
                'INSERT INTO addresses (user_id, address_type, full_name, phone, address, city, state, pincode, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([
                $userId,
                'shipping',
                $shippingAddress['fullName'] ?? '',
                $shippingAddress['phone'] ?? '',
                $shippingAddress['address'] ?? '',
                $shippingAddress['city'] ?? '',
                $shippingAddress['state'] ?? '',
                $shippingAddress['pincode'] ?? ''
            ]);
            $shippingAddressId = $pdo->lastInsertId();
        }
        
        // Create order
        $orderNumber = generateOrderNumber($pdo);
        $stmt = $pdo->prepare(
            'INSERT INTO orders (order_number, user_id, coupon_id, subtotal, discount, delivery_charge, tax, total_amount, payment_method, payment_status, order_status, billing_address_id, shipping_address_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $orderNumber,
            $userId,
            $couponId,
            $subtotal,
            $discount,
            $deliveryCharge,
            $tax,
            $totalAmount,
            $paymentMethod,
            'pending',
            'pending',
            $billingAddressId,
            $shippingAddressId
        ]);
        $orderId = $pdo->lastInsertId();
        
        // Create order items and reduce stock
        foreach ($items as $item) {
            $stmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, variant_id, product_name, size, color, quantity, price, subtotal, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $item['variant_id'],
                $item['product_id'],
                '',
                '',
                $item['quantity'],
                $item['price'],
                $item['price'] * $item['quantity']
            ]);
            
            // Reduce stock
            $stmt = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }
        
        // Update coupon usage
        if ($couponId) {
            $stmt = $pdo->prepare('UPDATE coupons SET used_count = used_count + 1 WHERE id = ?');
            $stmt->execute([$couponId]);
        }
        
        // Clear cart
        $stmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = ?');
        $stmt->execute([$cart['id']]);
        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
        $stmt->execute([$userId]);
        
        $pdo->commit();
        
        $response = ['success' => true, 'message' => 'Order placed successfully', 'orderNumber' => $orderNumber, 'orderId' => $orderId];
    } catch (Exception $e) {
        $pdo->rollBack();
        $response = ['success' => false, 'message' => $e->getMessage()];
    }
}

echo json_encode($response);
?>