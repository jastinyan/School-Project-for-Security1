<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

$id_no = $_POST['id_no'] ?? '';
if(!$id_no){
    echo json_encode(["status"=>"error","message"=>"Unauthorized access"]);
    exit;
}

/* ---------------- ATTEMPT TRACKING ---------------- */
if(!isset($_SESSION['sec_attempts'][$id_no])){
    $_SESSION['sec_attempts'][$id_no] = [
        'count' => 0,
        'locked_until' => null
    ];
}

$attemptData = &$_SESSION['sec_attempts'][$id_no];
$now = time();

/* 🔒 CHECK LOCK */
if($attemptData['locked_until'] && $now < $attemptData['locked_until']){
    $remaining = ceil(($attemptData['locked_until'] - $now) / 60);
    echo json_encode([
        "status"=>"locked",
        "message"=>"Too many attempts. Try again in {$remaining} minutes."
    ]);
    exit;
}

/* ---------------- INPUT ---------------- */
$a1 = trim($_POST['sec_a1'] ?? '');
$a2 = trim($_POST['sec_a2'] ?? '');
$a3 = trim($_POST['sec_a3'] ?? '');

/* ---------------- FETCH ANSWERS ---------------- */
$stmt = $conn->prepare("SELECT sec_a1,sec_a2,sec_a3 FROM registeredacc WHERE id_no=?");
$stmt->bind_param("s",$id_no);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if(!$row){
    echo json_encode(["status"=>"error","message"=>"User not found"]);
    exit;
}

/* ---------------- VALIDATION ---------------- */
$errors = [];

if($a1 !== $row['sec_a1']) $errors[] = "Mother's first name is incorrect.";
if($a2 !== $row['sec_a2']) $errors[] = "Elementary school answer is incorrect.";
if($a3 !== $row['sec_a3']) $errors[] = "Birthplace answer is incorrect.";

if(empty($errors)){
    $_SESSION['security_verified'] = true;
    unset($_SESSION['sec_attempts'][$id_no]); // reset attempts
    echo json_encode(["status"=>"success"]);
    exit;
}

/* ---------------- FAILED ATTEMPT ---------------- */
$attemptData['count']++;

/* 🔁 REDIRECT AFTER 6 TOTAL ATTEMPTS */
if($attemptData['count'] >= 6){
    unset($_SESSION['sec_attempts'][$id_no]);
    echo json_encode([
        "status"=>"redirect",
        "message"=>"Too many failed attempts. Redirecting to OTP.",
        "redirect"=>"forgot_password_flow.php?id_no=".$id_no
    ]);
    exit;
}

/* 🔒 LOCK AFTER 3 (per cycle) */
if($attemptData['count'] % 3 === 0){
    $attemptData['locked_until'] = $now + (2 * 60);
    echo json_encode([
        "status" => "locked",
        "message" => "Too many attempts. Try again in 2 minutes."
    ]);
    exit;
}

/* 🔴 FAILED - ATTEMPTS LEFT (per cycle) */
$attempts_left = 3 - ($attemptData['count'] % 3);
if($attempts_left === 0) $attempts_left = 3; // resets for next cycle

echo json_encode([
    "status"=>"failed",
    "errors"=>$errors,
    "attempts_left"=> $attempts_left
]);
