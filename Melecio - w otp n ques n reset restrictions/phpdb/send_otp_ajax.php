<?php
session_start();
require 'db_connection.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$input = trim($_POST['user_input'] ?? '');
if(!$input){ echo "Please enter your username or ID."; exit; }

$stmt = $conn->prepare("SELECT id_no,email FROM registeredacc WHERE username=? OR id_no=?");
$stmt->bind_param("ss",$input,$input);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if(!$user){ echo json_encode("Account does not exist."); exit; }

$id_no = $user['id_no'];
$email = $user['email'];

// Delete old unverified OTPs
$stmt = $conn->prepare("DELETE FROM password_resets WHERE id_no=? AND is_verified=0");
$stmt->bind_param("s",$id_no);
$stmt->execute();

// Generate new OTP
$otp = random_int(100000,999999);
$hashedOtp = password_hash($otp,PASSWORD_DEFAULT);
$expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));
$stmt = $conn->prepare("INSERT INTO password_resets(id_no,otp,expires_at) VALUES(?,?,?)");
$stmt->bind_param("sss",$id_no,$hashedOtp,$expires);
$stmt->execute();

// Send OTP via PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'meditationtracker2026@gmail.com';
    $mail->Password   = 'futsmhiqpvyxphsq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom('meditationtracker2026@gmail.com','Meditation Tracker');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "<h2>Password Reset OTP</h2><p>Your OTP code is:</p><h1>$otp</h1><p>This code expires in 5 minutes.</p>";
    $mail->send();
    echo json_encode(['status'=>'success','id_no'=>$id_no]);
} catch(Exception $e){
    echo "Failed to send OTP: {$mail->ErrorInfo}";
}
?>
