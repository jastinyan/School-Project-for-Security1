<?php
session_start();
require 'db_connection.php';

/* Simple login check */
if (!isset($_SESSION['id_no'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="../css/superadmin_home.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-content container">
        <p class="system-title">Super Admin Dashboard</p>
    </div>
</div>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">Welcome <?= htmlspecialchars($username) ?></div>
        <h3>Management</h3>

        <a href="manage_admins.php">Admin Management</a>
        <a href="manage_users.php">User Management</a>
        <a href="view_records.php">Meditation Records</a>
        <a href="reports.php">System Reports</a>
        <a href="#">System Logs</a>
        <a href="site_settings.php">Account Settings</a>
        <a href="homepage.php" class="btn-logout">Logout</a>
    </aside>
<!-- MAIN CONTENT -->
<main class="dashboard-main container">

    <h1>System Overview</h1>

    <!-- DASHBOARD CARDS -->
    <div class="cards">
        <div class="card">
            <h3>Total User</h3>
            <p>---</p>
            <a href="#" class="btn">Action</a>
        </div>

        <div class="card">
            <h3>Total Admins</h3>
            <p>---</p>
            <a href="#" class="btn">Action</a>
        </div>

        <div class="card">
            <h3>Total Sessions</h3>
            <p>---</p>
            <a href="#" class="btn">Action</a>
        </div>

        <div class="card">
            <h3>Total Minutes</h3>
            <p>---</p>
            <a href="#" class="btn">Action</a>
        </div>
    </div>

</main>

</div>


<!-- Footer -->
<div class="footer">
    <p>&copy; 2026 Meditation Activity Tracker</p>
</div>

</body>
</html>
