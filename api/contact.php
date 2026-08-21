<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $subject = $data['subject'] ?? '';
    $message = $data['message'] ?? '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response = ['success' => false, 'message' => 'All fields are required'];
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO contacts (name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, $email, $phone, $subject, $message, 'new']);
            $response = ['success' => true, 'message' => 'Message sent successfully'];
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'Error sending message'];
        }
    }
}

echo json_encode($response);
?>