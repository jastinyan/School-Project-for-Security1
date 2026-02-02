<?php
session_start();
require 'db_connection.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id_no = $_POST['id_no'] ?? '';
$resend = isset($_GET['resend']);
if(!$id_no){ echo "Unauthorized access"; exit; }

function sendOTP($email, $otp){
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
        return true;
    } catch(Exception $e){ die("Email failed: {$mail->ErrorInfo}"); }
}

if($resend){
    $stmt = $conn->prepare("SELECT email FROM registeredacc WHERE id_no=?");
    $stmt->bind_param("s",$id_no);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if(!$user){ echo "User not found."; exit; }
    $email = $user['email'];

    $stmt = $conn->prepare("SELECT created_at FROM password_resets WHERE id_no=? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("s",$id_no);
    $stmt->execute();
    $last = $stmt->get_result()->fetch_assoc();
    if($last && (time()-strtotime($last['created_at'])) < 300){ echo "Please wait 5 minutes."; exit; }

    $otp = random_int(100000,999999);
    $hashedOtp = password_hash($otp,PASSWORD_DEFAULT);
    $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));
    $stmt = $conn->prepare("INSERT INTO password_resets(id_no,otp,expires_at,is_verified) VALUES(?,?,?,0)");
    $stmt->bind_param("sss",$id_no,$hashedOtp,$expires);
    $stmt->execute();
    sendOTP($email,$otp);
    echo "A new OTP has been sent.";
    exit;
}

$inputOtp = trim($_POST['otp']);
$stmt = $conn->prepare("SELECT reset_id,otp,expires_at FROM password_resets WHERE id_no=? AND is_verified=0 ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("s",$id_no);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if(!$row){ echo "Code is already used. Please request new one."; exit; }
if(strtotime($row['expires_at'])<time()){ echo "OTP expired. Request new one."; exit; }
if(password_verify($inputOtp,$row['otp'])){
    $stmt = $conn->prepare("UPDATE password_resets SET is_verified=1 WHERE reset_id=?");
    $stmt->bind_param("i",$row['reset_id']);
    $stmt->execute();
    echo "success"; exit;
}else{ echo "Invalid OTP."; exit; }
?>
