<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/admin_auth.php';

if (!AdminAuth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, name, email, phone, status, created_at FROM users ORDER BY created_at DESC');
$stmt->execute();
$users = $stmt->fetchAll();

echo json_encode(['success' => true, 'data' => $users]);
?>