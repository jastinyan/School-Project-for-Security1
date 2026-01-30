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
        <!-- Navbar -->
        <div class="navbar">
            <p class="system-title container">Meditation Activity Tracker</p>
            <div class="buttons-container">
                <button type="button" class="btn1" id="HomeButton">
                    <a id="HomeLinkB" href="../phpdb/homepage.php">Home</a>
                </button>
            </div>
        </div>

        <!-- OTP Box -->
        <div class="otp-box container">
            <h2 class="otp-title">OTP Verification</h2>
            <form method="POST" action="">
                <!-- Username or ID Input -->
                <div class="input-field">
                    <label class="label" for="user_input">Username or ID Number</label>
                    <input class="input" type="text" id="user_input" name="user_input" required placeholder="Enter username or ID number">
                </div>

                <!-- Send OTP Button -->
                <div class="button">
                    <button type="submit" class="btn" id="otpButton">Send OTP</button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="all">&copy; 2024 Meditation Activity Tracker. All rights reserved.</p>
        </div>
    </body>
    </html>

    <?php
    require 'db_connection.php';

    /* ===== PHPMailer setup ===== */
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . '/PHPMailer/src/Exception.php';
    require __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require __DIR__ . '/PHPMailer/src/SMTP.php';

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

            $mail->setFrom('meditationtracker2026@gmail.com', 'Meditation Tracker');
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

    /* ===== Handle form submit ===== */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $input = trim($_POST['user_input']);

        // Fetch user by username or ID number
        $sql = "SELECT id_no, email FROM registeredacc WHERE username = ? OR id_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $input, $input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {

            $id_no = $user['id_no'];
            $email = $user['email'];

            // ===== Delete any previous unverified OTPs =====
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE id_no = ? AND is_verified = 0");
            $stmt->bind_param("s", $id_no);
            $stmt->execute();

            // Generate new OTP
            $otp = random_int(100000, 999999);
            $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
            $expiresAt = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            // Save new OTP
            $stmt = $conn->prepare(
                "INSERT INTO password_resets (id_no, otp, expires_at)
                VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $id_no, $hashedOtp, $expiresAt);
            $stmt->execute();

            // Send OTP email
            sendOTP($email, $otp);

            // Redirect to OTP verification
            header("Location: verify_otp.php?id_no=$id_no");
            exit;
        }

        // Generic message for security
        echo "<p style='text-align:center;color:green;'>If the account exists, an OTP has been sent to the registered email.</p>";
    }
    ?>
