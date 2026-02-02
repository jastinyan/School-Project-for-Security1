<?php
session_start();
require 'db_connection.php';

// If user was logged in, log the logout

if (isset($_SESSION['id_no']) && isset($_SESSION['username'])) {
    $id_no = $_SESSION['id_no'];
    $username = $_SESSION['username'];
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare(
        "INSERT INTO system_logs (id_no, username, action, browser, ip_address) VALUES (?, ?, 'logout', ?, ?)"
    );
    $stmt->bind_param("ssss", $id_no, $username, $browser, $ip);
    $stmt->execute();
    $stmt->close();
}

// Destroy session regardless
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="shortcut icon" href="../img/med-logo.png" type="image/x-icon">
    <title>Homepage</title>
</head>
<body>
    <div class="navbar">
        <p class="system-title container">Meditation Activity Tracker</p>
        <div class="buttons-container">
            <button type="button" class="btn1"><a href="../phpdb/register.php">Register</a></button>
            <button type="button" class="btn2"><a href="../phpdb/login.php">Log In</a></button>
        </div>
    </div>

    <div class="login-box container">
        <h2 class="login-title">WELCOME TO MAT</h2>
        <p>Where we track your meditation activities and help you achieve your desired peacefulness.</p>
        <p>Live, Love, Leisure</p>
    </div>

    <div class="footer">
        <p class="all">&copy; 2026 Meditation Activity Tracker. All rights reserved.</p>
    </div>
</body>
</html>
