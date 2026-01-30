<?php
session_start();
require 'db_connection.php';

header("Content-Type: text/plain");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit;
}

$input = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

$stmt = $conn->prepare("
    SELECT id_no, username, password, role
    FROM registeredacc
    WHERE username = ? OR email = ?
");
$stmt->bind_param("ss", $input, $input);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {

    if (password_verify($password, $user['password'])) {

        // ✅ Store COMPLETE session info
        $_SESSION['id_no'] = $user['id_no'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        echo "SUCCESS|" . $user['role'];
    } else {
        echo "Invalid password.";
    }

} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
exit;
