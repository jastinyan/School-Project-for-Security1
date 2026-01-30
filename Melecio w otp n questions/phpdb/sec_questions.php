<?php
session_start();
require 'db_connection.php';

// Ensure OTP was verified first
if (!isset($_GET['id_no'])) {
    die("Unauthorized access.");
}

$id_no = $_GET['id_no'];
$error = "";

// Updated fixed questions
$questions = [
    "sec_a1" => "What is your mother's first name?",
    "sec_a2" => "Where did you attend Elementary?",
    "sec_a3" => "Where is your birthplace?"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $a1 = trim($_POST['sec_a1']);
    $a2 = trim($_POST['sec_a2']);
    $a3 = trim($_POST['sec_a3']);

    // Fetch answers from database
    $stmt = $conn->prepare("SELECT sec_a1, sec_a2, sec_a3 FROM registeredacc WHERE id_no = ?");
    $stmt->bind_param("s", $id_no);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();

        // Compare answers
        if ($a1 === $row['sec_a1'] && $a2 === $row['sec_a2'] && $a3 === $row['sec_a3']) {
            // Mark security questions as verified
            $_SESSION['security_verified'] = true;

            // Redirect to reset password page
            header("Location: reset_password.php?id_no=" . urlencode($id_no));
            exit;
        } else {
            $error = "Incorrect answers. Please try again.";
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
    <title>Security Questions Verification</title>
    <link rel="stylesheet" href="../phpdb/script.php?dir=css&file=register.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <p class="system-title container">Meditation Activity Tracker</p>
        <div class="buttons-container">
            <button type="button" class="btn1" id="HomeButton"><a id="HomeLinkB" href="../phpdb/homepage.php">Home</a></button>
        </div>
    </div>

    <div class="register-box container">
        <h2 class="register-title">Answer Security Questions</h2>
        <form method="POST">
            <div class="account-info">
                <div class="input-field">
                    <label><?= $questions['sec_a1'] ?></label>
                    <input type="text" name="sec_a1" required>
                </div>
                <div class="input-field">
                    <label><?= $questions['sec_a2'] ?></label>
                    <input type="text" name="sec_a2" required>
                </div>
                <div class="input-field">
                    <label><?= $questions['sec_a3'] ?></label>
                    <input type="text" name="sec_a3" required>
                </div>
            </div>
            <div class="button">
                <button type="submit" class="btn">Verify Answers</button>
            </div>
            <?php if ($error !== "") { echo "<p style='color:red;text-align:center;margin-top:10px;'>$error</p>"; } ?>
        </form>
    </div>

     <!-- Footer -->
    <div class="footer">
        <p class="all">&copy; 2024 Meditation Activity Tracker. All rights reserved.</p>
    </div>

</body>
</html>
