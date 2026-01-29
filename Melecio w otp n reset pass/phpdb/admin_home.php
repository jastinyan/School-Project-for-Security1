<?php
session_start();
require 'db_connection.php';

/* 🔐 Protect admin page */
if (!isset($_SESSION['id_no']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_home.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-content container">
        <p class="system-title">Admin Dashboard</p>
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
            <h3>Reports</h3>
            <p>System & usage reports</p>
            <a href="reports.php" class="btn">Open</a>
        </div>

    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Meditation Activity Tracker</p>
</div>

</body>
</html>
