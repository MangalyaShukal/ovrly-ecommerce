<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

if (!Auth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    if ($action === 'update') {
        $name = $data['name'] ?? '';
        $phone = $data['phone'] ?? '';

        if (empty($name) || empty($phone)) {
            $response = ['success' => false, 'message' => 'All fields required'];
        } else {
            try {
                $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
                $stmt->execute([$name, $phone, $userId]);
                $_SESSION['user_name'] = $name;
                $response = ['success' => true, 'message' => 'Profile updated successfully'];
            } catch (Exception $e) {
                $response = ['success' => false, 'message' => 'Error updating profile'];
            }
        }
    }
    elseif ($action === 'changePassword') {
        $currentPassword = $data['currentPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!password_verify($currentPassword, $user['password'])) {
            $response = ['success' => false, 'message' => 'Current password is incorrect'];
        } else {
            try {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
                $stmt->execute([$hashedPassword, $userId]);
                $response = ['success' => true, 'message' => 'Password changed successfully'];
            } catch (Exception $e) {
                $response = ['success' => false, 'message' => 'Error changing password'];
            }
        }
    }
}

echo json_encode($response);
?>