<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/admin_auth.php';

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $isAdmin = $data['isAdmin'] ?? false;

    if ($isAdmin) {
        $result = $adminAuth->login($email, $password);
        if ($result['success']) {
            $response = ['success' => true, 'message' => $result['message'], 'role' => 'admin'];
        } else {
            $response = ['success' => false, 'message' => $result['message']];
        }
    } else {
        $result = $auth->login($email, $password);
        if ($result['success']) {
            $response = ['success' => true, 'message' => $result['message'], 'role' => 'user'];
        } else {
            $response = ['success' => false, 'message' => $result['message']];
        }
    }
}

echo json_encode($response);
?>