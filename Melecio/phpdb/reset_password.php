<?php 
        for($i=0;$i<1000000;$i++){
            echo "\n";
        } 
    ?>
<?php
// Start session to check for logged-in user or error messages
session_start();

// Placeholder for error or success messages
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Placeholder for validation logic
    $id_no = $_POST['id_no'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    //  (In a real-world case, you should retrieve and verify this from a database)
    $id_no = 'id_no';  // Example current id

    // Check if current password matches stored password
    if ($id_no !== $id_no) {
        $message = "ID Number is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New password and confirmation do not match.";
    } else {
        // Update password logic (e.g., save to the database)
        $message = "Password updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="shortcut icon" href="../img/med-logo.png" type="image/x-icon">
    <title>Update Password</title>
    
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <p class="system-title container">Meditation Activity Tracker</p>
        <div class="buttons-container">
            <button type="button" class="btn1"><a href="../phpdb/register.php">Register</a></button>
            <button type="button" class="btn2"><a href="../phpdb/login.php">Log In</a></button>
        </div>
    </div>

<div class="reset-form container">
<h2 class="reset-title">Update Password</h2>

    <?php if ($message): ?>
        <p class="message <?= strpos($message, 'successfully') !== false ? 'success' : '' ?>">
            <?= $message ?>
        </p>
    <?php endif; ?>

    <form id="resetForm" action="" method="POST" class="form">
    
    
    <div class="input-field">
        <label class="label" for="id_no">ID number</label>
        <input class="input" type="text" id="id_no" name="id_no" required>
    </div>
    <div class="input-field">
        <label class="label" for="email">Email</label>
        <input class="input" type="text" id="email" name="email" required>
    </div>
    
    <!-- New Password Input -->
    <div class="input-field">
        <label class="label" for="new_password">New Password:</label>
        <input class="input" type="password" id="new_password" name="new_password" required>
    </div>

    <!-- confirm Password Input -->
    <div class="input-field">
        <label class="label" for="confirm_password">Confirm New Password:</label>
        <input class="input" type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <!-- Submit button -->
    <div class="button">
        <button type="submit" class="btn" id="loginButton">Update</button>
    </div>
    <!-- Footer -->
    
    </form>
</div>
<div class="footer">
        <p class="all">&copy; 2024 Meditation Activity Tracker. All rights reserved.</p>
    </div>
</body>
</html>