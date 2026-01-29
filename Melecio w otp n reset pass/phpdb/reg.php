<?php
include "db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_no = $_POST['id_no'];
    $f_name = $_POST['f_name'];
    $m_initial = $_POST['m_initial'];
    $l_name = $_POST['l_name'];
    $extension = $_POST['extension'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];  
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $purok = $_POST['purok'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $country = $_POST['country'];
    $zipcode = $_POST['zipcode'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query using prepared statements to prevent SQL injection
    $sql = "INSERT INTO `registeredacc` (id_no, f_name, m_initial, l_name, extension, birthday, age, sex, username, password, email, purok, barangay, city, province, country, zipcode) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $id_no, $f_name, $m_initial, $l_name, $extension, $birthday, $age, $sex, $username, $hashed_password, $email, $purok, $barangay, $city, $province, $country, $zipcode);

        // Execute the statement
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Redirect to login page
            header("Location: ../phpdb/login.php?msg=New record created successfully");
            exit(); // Ensure no further code is executed
        } else {
            echo "Error during insertion: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare the statement: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
