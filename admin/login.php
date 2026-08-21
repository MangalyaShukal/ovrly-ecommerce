<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/admin_auth.php';

if (!AdminAuth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | OVRLY</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>OVRLY Admin</h1>
                <p>Oversized. Unapologetic.</p>
            </div>

            <div class="auth-form">
                <h2>Admin Login</h2>
                <form onsubmit="handleAdminLogin(event)">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" id="adminEmail" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="adminPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary full-width">Admin Login</button>
                    <p class="auth-link" style="text-align: center; margin-top: 15px;">
                        <a href="../login.html">Back to User Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function handleAdminLogin(e) {
            e.preventDefault();
            const email = document.getElementById('adminEmail').value;
            const password = document.getElementById('adminPassword').value;

            try {
                const response = await fetch('../api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password, isAdmin: true })
                });
                const data = await response.json();

                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Login failed');
            }
        }
    </script>
</body>
</html>