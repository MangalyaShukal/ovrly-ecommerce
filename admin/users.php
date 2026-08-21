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
    <title>User Management | OVRLY Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <nav class="admin-navbar">
        <div class="navbar-left"><h1>OVRLY Admin</h1></div>
        <div class="navbar-right">
            <a href="#" onclick="adminLogout()" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="admin-container">
        <aside class="admin-sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tag"></i> Categories</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="coupons.php"><i class="fas fa-ticket-alt"></i> Coupons</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="section-header">
                <h1>User Management</h1>
                <button class="btn btn-primary" onclick="openUserForm()">Add New User</button>
            </div>

            <div class="search-bar">
                <input type="text" id="searchUsers" placeholder="Search users..." onkeyup="searchUsers()">
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTable">
                    <tr><td colspan="6" style="text-align: center;">Loading...</td></tr>
                </tbody>
            </table>
        </main>
    </div>

    <!-- User Form Modal -->
    <div id="userModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeUserForm()">&times;</span>
            <h2>User Management</h2>
            <form onsubmit="saveUser(event)">
                <input type="hidden" id="userId">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="userName" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="userEmail" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" id="userPhone" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="userStatus">
                        <option value="blocked">Blocked</option>
                        <option value="active">Active</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save User</button>
            </form>
        </div>
    </div>

    <script>
        async function loadUsers() {
            try {
                const response = await fetch('../api/admin/users.php');
                const data = await response.json();
                
                if (data.success) {
                    displayUsers(data.data);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function displayUsers(users) {
            const tbody = document.getElementById('usersTable');
            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone}</td>
                    <td><span class="badge badge-${user.status}">${user.status}</span></td>
                    <td>${new Date(user.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="action-btn" onclick="editUser(${user.id})">Edit</button>
                        <button class="action-btn delete" onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        function openUserForm() {
            document.getElementById('userModal').style.display = 'block';
            document.getElementById('userId').value = '';
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userPhone').value = '';
            document.getElementById('userStatus').value = 'blocked';
        }

        function closeUserForm() {
            document.getElementById('userModal').style.display = 'none';
        }

        function searchUsers() {
            const query = document.getElementById('searchUsers').value;
            loadUsers();
        }

        function adminLogout() {
            fetch('../api/admin/logout.php', { method: 'POST' })
                .then(() => window.location.href = 'login.php');
        }

        document.addEventListener('DOMContentLoaded', loadUsers);
    </script>
</body>
</html>