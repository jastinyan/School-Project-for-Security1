
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../phpdb/script.php?dir=css&file=login.css">
    <link rel="shortcut icon" href="../img/med-logo.png" type="image/x-icon">
    <title>Login</title>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <p class="system-title container">Meditation Activity Tracker</p>
        <div class="buttons-container">
            <button type="button" class="btn1" id="HomeButton"><a id="HomeLinkB" href="../phpdb/homepage.php">Home</a></button>
            <button type="button" class="btn2" id="registerButton"><a id="registerLinkB" href="../phpdb/register.php">Register</a></button>
        </div>
    </div>

    <!-- Login Box -->
    <div class="login-box container">
        <h2 class="login-title">Log in</h2>

        <form id="loginForm" action="" method="POST" class="form">
            <!-- Username Input -->
            <div class="input-field">
                <label class="label" for="username">Username or Email</label>
                <input class="input" type="text" id="username" name="username" required placeholder="Enter your username or email">
            </div>

            <!-- Password Input -->
            <div class="input-field">
                <label class="label" for="password">Password</label>
                <input class="input" type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <!-- Show Password Checkbox -->
            <div class="show-password-container">
                <input type="checkbox" id="showPassword"> 
                <label for="showPassword" class="label">Show Password</label>
            </div>

            <!-- Login Button -->
            <div class="button">
                <button type="submit" class="btn" id="loginButton">Login</button>
            </div>

            <!-- Register Link -->
            <div class="div-create">
                <p class="create">Don't have an account? <a id="register-link" href="../phpdb/register.php">Register Here</a></p>
            </div>

            <!-- Forgot Password Container (appears after 2 failed attempts) -->
            <div id="forgotPasswordContainer" style="display:none;"></div>

            <!-- Lockout Timer -->
            <div id="timerDisplay" style="display:none;">
                Locked for <span id="lockoutTimeDisplay"></span> seconds
            </div>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p class="all">&copy; 2026 Meditation Activity Tracker. All rights reserved.</p>
    </div>

    <!-- JS -->
    <script src="../phpdb/script.php?dir=script&file=login.js" defer></script>
</body>
</html>
