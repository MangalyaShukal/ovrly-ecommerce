<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/admin_auth.php';

if (!AdminAuth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

AdminAuth::logout();
echo json_encode(['success' => true, 'message' => 'Logout successful']);
?>