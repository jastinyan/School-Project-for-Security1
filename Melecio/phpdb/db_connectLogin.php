<?php
$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "mysystem";

// Create connection
$conn = mysqli_connect($db_servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind the SQL query
    $stmt = $conn->prepare("SELECT password FROM registeredacc WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start the session
            session_start();
            $_SESSION['username'] = $username; // Store the username in session
            echo "Login successful!";
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}
