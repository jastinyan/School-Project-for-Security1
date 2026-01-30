<?php
require 'db_connection.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['id_no'])) {
    die("Unauthorized access.");
}

$id_no = $_GET['id_no'];
$error = "";
$success = "";

// ===== Helper function to send OTP =====
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'meditationtracker2026@gmail.com';
        $mail->Password   = 'futsmhiqpvyxphsq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('meditationtracker2026@gmail.com', 'Meditation Activity Tracker');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "
            <h2>Password Reset OTP</h2>
            <p>Your OTP code is:</p>
            <h1>$otp</h1>
            <p>This code expires in 5 minutes.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        die("Email failed: {$mail->ErrorInfo}");
    }
}

// ===== VERIFY OTP =====
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['verify'])) {

    $inputOtp = trim($_POST['otp']);

    $sql = "SELECT reset_id, otp, expires_at FROM password_resets
            WHERE id_no = ? AND is_verified = 0
            ORDER BY created_at DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // Check expiration
        if (strtotime($row['expires_at']) < time()) {
            $error = "OTP has expired. Please request a new one.";
        }
        // Check OTP
        elseif (password_verify($inputOtp, $row['otp'])) {
            // Mark OTP as verified
            $stmt = $conn->prepare("UPDATE password_resets SET is_verified = 1 WHERE reset_id = ?");
            $stmt->bind_param("i", $row['reset_id']);
            $stmt->execute();

            // Redirect to security questions page
            header("Location: sec_questions.php?id_no=" . urlencode($id_no));
            exit;
        } else {
            $error = "Invalid OTP. Try again.";
        }
    } else {
        $error = "No OTP found. Please request a new one.";
    }
}

// ===== RESEND OTP =====
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['resend'])) {

    // Get user's email
    $stmt = $conn->prepare("SELECT email FROM registeredacc WHERE id_no = ?");
    $stmt->bind_param("s", $id_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $email = $user['email'];

        // Check last OTP time
        $stmt = $conn->prepare("SELECT created_at FROM password_resets WHERE id_no = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("s", $id_no);
        $stmt->execute();
        $last = $stmt->get_result()->fetch_assoc();

        if ($last && (time() - strtotime($last['created_at'])) < 300) {
            $error = "Please wait 5 minutes before requesting a new OTP.";
        } else {
            $otp = random_int(100000, 999999);
            $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
            $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            // Save OTP in DB
            $stmt = $conn->prepare("INSERT INTO password_resets (id_no, otp, expires_at, is_verified) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $id_no, $hashedOtp, $expires);
            $stmt->execute();

            sendOTP($email, $otp);

            $success = "A new OTP has been sent to your email.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/forgot_password.css">
<link rel="shortcut icon" href="../img/med-logo.png" type="image/x-icon">
<title>OTP VERIFICATION</title>
</head>
<body>
<div class="navbar">
    <p class="system-title container">Meditation Activity Tracker</p>
    <div class="buttons-container">
        <button type="button" class="btn1" id="HomeButton">
            <a id="HomeLinkB" href="../phpdb/homepage.php">Home</a>
        </button>
    </div>
</div>

<div class="otp-box container">
    <h2 class="otp-title">OTP Verification</h2>
    <form method="POST">
        <div class="input-field">
           <label class="label">OTP CODE</label>
           <input class="input" type="text" name="otp" placeholder="Enter OTP">
        </div>

        <button type="submit" name="verify" class="btn">Verify OTP</button>
        <button type="submit" name="resend" class="btn">Resend OTP</button>
    </form>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green"><?= $success ?></p>
    <?php endif; ?>
</div>

<div class="footer">
    <p class="all">&copy; 2024 Meditation Activity Tracker. All rights reserved.</p>
</div>
</body>
</html>
