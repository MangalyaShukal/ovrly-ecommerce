<?php
require_once __DIR__ . '/database.php';

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($name, $email, $phone, $password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }

        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            return ['success' => false, 'message' => 'Phone must be 10 digits'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (name, email, phone, password, status, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([$name, $email, $phone, $hashedPassword, 'blocked', 'user']);
            return ['success' => true, 'message' => 'Registration successful. Admin approval required.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password, status, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        if ($user['status'] === 'blocked') {
            return ['success' => false, 'message' => 'Your account has been blocked or is awaiting administrator approval.'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid password'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_time'] = time();

        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
    }

    public static function isAdmin() {
        return isset($_SESSION['admin_id']) && isset($_SESSION['admin_email']);
    }

    public static function getCurrentUser() {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role']
            ];
        }
        return null;
    }

    public static function logout() {
        session_destroy();
        setcookie('PHPSESSID', '', time() - 3600, '/');
        return true;
    }
}

$auth = new Auth($pdo);
?>