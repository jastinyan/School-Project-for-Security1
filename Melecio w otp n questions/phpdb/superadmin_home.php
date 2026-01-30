<?php
session_start();
require 'db_connection.php';

/* 🔐 Simple login check */
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
    <link rel="stylesheet" href="../css/admin_home.css"> <!-- reuse your CSS -->
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-content container">
        <p class="system-title">Super Admin Dashboard</p>
        <div class="buttons-container">
            <span class="welcome">Welcome, <?= htmlspecialchars($username) ?></span>
            <a href="homepage.php" class="btn-logout">Logout</a>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="dashboard container">

    <h2>System Overview</h2>

    <div class="cards">

        <div class="card">
            <h3>Manage Admins</h3>
            <p>View, add, or remove admins</p>
            <a href="manage_admins.php" class="btn">Open</a>
        </div>

        <div class="card">
            <h3>Manage Users</h3>
            <p>View, block, or promote users</p>
            <a href="manage_users.php" class="btn">Open</a>
        </div>

        <div class="card">
            <h3>Meditation Records</h3>
            <p>View all user activity logs</p>
            <a href="view_records.php" class="btn">Open</a>
        </div>

        <div class="card">
            <h3>System Reports</h3>
            <p>Generate and review system & usage reports</p>
            <a href="reports.php" class="btn">Open</a>
        </div>

        <div class="card">
            <h3>Site Settings</h3>
            <p>Manage global system settings</p>
            <a href="site_settings.php" class="btn">Open</a>
        </div>

    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Meditation Activity Tracker</p>
</div>

</body>
</html>
