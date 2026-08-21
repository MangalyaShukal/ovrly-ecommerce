<?php
require_once __DIR__ . '/database.php';

class AdminAuth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password, status FROM admins WHERE email = ?');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin) {
            return ['success' => false, 'message' => 'Admin not found'];
        }

        if ($admin['status'] !== 'active') {
            return ['success' => false, 'message' => 'Admin account is inactive'];
        }

        if (!password_verify($password, $admin['password'])) {
            return ['success' => false, 'message' => 'Invalid password'];
        }

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_login_time'] = time();
        $_SESSION['is_admin'] = true;

        return ['success' => true, 'message' => 'Admin login successful', 'admin' => $admin];
    }

    public static function isLoggedIn() {
        return isset($_SESSION['admin_id']) && isset($_SESSION['admin_email']) && $_SESSION['is_admin'] === true;
    }

    public static function getCurrentAdmin() {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['admin_id'],
                'name' => $_SESSION['admin_name'],
                'email' => $_SESSION['admin_email']
            ];
        }
        return null;
    }

    public static function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['is_admin']);
        return true;
    }
}

$adminAuth = new AdminAuth($pdo);
?>