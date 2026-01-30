<?php
session_start();
require 'db_connection.php';
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['security_verified']) || $_SESSION['security_verified'] !== true) {
    die("Unauthorized access.");
}

if (!isset($_GET['id_no'])) {
    die("Unauthorized access");
}

$id_no = $_GET['id_no'];

/* 🔒 CHECK OTP VERIFIED */
$stmt = $conn->prepare(
    "SELECT reset_id FROM password_resets
     WHERE id_no = ? AND is_verified = 1
     ORDER BY reset_id DESC LIMIT 1"
);
$stmt->bind_param("s", $id_no);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    die("OTP verification required.");
}

/* 🔁 HANDLE PASSWORD RESET */
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "UPDATE registeredacc SET password = ? WHERE id_no = ?"
        );
        $stmt->bind_param("ss", $hashed, $id_no);
        $stmt->execute();

        /* 🧹 CLEAN OTP */
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE id_no = ?");
        $stmt->bind_param("s", $id_no);
        $stmt->execute();

        /* ✅ FLASH MESSAGE + REDIRECT */
        $_SESSION['success_message'] = "You can now log in with your new password.";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/reset.css">
<title>Update Password</title>
</head>
<body>

<!-- Navbar -->
    <div class="navbar">
        <p class="system-title container">Meditation Activity Tracker</p>
        <div class="buttons-container">
            <button type="button" class="btn1" id="HomeButton"><a id="HomeLinkB" href="../phpdb/homepage.php">Home</a></button>
        </div>
    </div>
<div class="reset-form container">
    <h2 class="reset-title">Update Password</h2>

    <?php if (!empty($error)): ?>
        <p class="message" style="color:red; margin-bottom:15px;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success-message" style="color:green; margin-bottom:15px;">
            <?= $_SESSION['success_message']; ?>
        </p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form method="POST">
        <div class="input-field">
            <label class="label" for="new_password">New Password</label>
            <input class="input" type="password" name="new_password" id="new_password" required>
        </div>

        <div class="input-field">
            <label class="label" for="confirm_password">Confirm Password</label>
            <input class="input" type="password" name="confirm_password" id="confirm_password" required>
        </div>

        <button type="submit" class="btn">Update</button>
    </form>
</div>

 <!-- Footer -->
    <div class="footer">
        <p class="all">&copy; 2024 Meditation Activity Tracker. All rights reserved.</p>
    </div>


<script src="../phpdb/script.php?dir=script&file=reset_password.js" defer></script>
</body>
</html>
