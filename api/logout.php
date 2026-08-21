<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/admin_auth.php';

if (isset($_SESSION['user_id'])) {
    $auth->logout();
}
if (isset($_SESSION['admin_id'])) {
    $adminAuth->logout();
}

echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
?>