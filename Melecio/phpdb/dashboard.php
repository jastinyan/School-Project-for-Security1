<?php
session_start();

// Prevent caching of the page
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Pragma: no-cache");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Handle logout request
if (isset($_GET['button1'])) {
    // Destroy all session data
    session_destroy();
    // Redirect to login page after logout
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../img/med-logo.png" type="image/x-icon">
    <title>Dashboard</title>
</head>
<body>
    <div class="navbar ">
        <p class="system-title container">Meditation Activity Tracker</p>
        <div class="button1"></div>
            <button type="button" class="btn1"><a href="dashboard.php?button1=true">Log Out</a></button>
        </div>
    </div>

    <div class="login-box container">
        <h2 class="login-title">WELCOME TO YOUR MAT ACCOUNT</h2>
        <p >You successfully logged in. </p>
        <p>Live, Love, Leisure </p>
    </div>

    <div class="footer">
        <p class="all">&copy; 2024 Meditation Activity Tracker. All rights reserved.</p>
    </div>
</body>
</html>