<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/admin_auth.php';

if (!AdminAuth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$adminUser = AdminAuth::getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | OVRLY</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <!-- Admin Navbar -->
    <nav class="admin-navbar">
        <div class="navbar-left">
            <h1>OVRLY Admin</h1>
        </div>
        <div class="navbar-right">
            <span>Welcome, <?php echo $adminUser['name']; ?></span>
            <a href="#" onclick="adminLogout()" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tag"></i> Categories</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="coupons.php"><i class="fas fa-ticket-alt"></i> Coupons</a></li>
                <li><a href="contacts.php"><i class="fas fa-envelope"></i> Messages</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <h1>Dashboard</h1>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div>
                        <h3 id="totalUsers">0</h3>
                        <p>Total Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-check"></i>
                    <div>
                        <h3 id="activeUsers">0</h3>
                        <p>Active Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-slash"></i>
                    <div>
                        <h3 id="blockedUsers">0</h3>
                        <p>Blocked Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-box"></i>
                    <div>
                        <h3 id="totalProducts">0</h3>
                        <p>Total Products</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div>
                        <h3 id="totalOrders">0</h3>
                        <p>Total Orders</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-hourglass-end"></i>
                    <div>
                        <h3 id="pendingOrders">0</h3>
                        <p>Pending Orders</p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-section">
                <h2>Recent Orders</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="recentOrders">
                        <tr><td colspan="5" style="text-align: center;">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        async function loadDashboard() {
            try {
                // Load stats
                const usersRes = await fetch('../api/admin/dashboard.php');
                const data = await usersRes.json();
                
                if (data.success) {
                    document.getElementById('totalUsers').textContent = data.stats.totalUsers;
                    document.getElementById('activeUsers').textContent = data.stats.activeUsers;
                    document.getElementById('blockedUsers').textContent = data.stats.blockedUsers;
                    document.getElementById('totalProducts').textContent = data.stats.totalProducts;
                    document.getElementById('totalOrders').textContent = data.stats.totalOrders;
                    document.getElementById('pendingOrders').textContent = data.stats.pendingOrders;
                    
                    // Display recent orders
                    const tbody = document.getElementById('recentOrders');
                    tbody.innerHTML = data.recentOrders.map(order => `
                        <tr>
                            <td>${order.order_number}</td>
                            <td>${order.user_id}</td>
                            <td>₹${order.total_amount}</td>
                            <td><span class="badge badge-${order.order_status}">${order.order_status}</span></td>
                            <td><a href="orders.php?id=${order.id}">View</a></td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }

        function adminLogout() {
            fetch('../api/admin/logout.php', { method: 'POST' })
                .then(() => window.location.href = 'login.php')
                .catch(err => console.error(err));
        }

        document.addEventListener('DOMContentLoaded', loadDashboard);
    </script>
</body>
</html>