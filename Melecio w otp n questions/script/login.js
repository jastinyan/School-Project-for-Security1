// =====================
// Lockout variables
// =====================
let failedAttempts = localStorage.getItem('failedAttempts')
    ? parseInt(localStorage.getItem('failedAttempts'))
    : 0;

let lockoutTime = localStorage.getItem('lockoutTime')
    ? parseInt(localStorage.getItem('lockoutTime'))
    : 0;

const lockoutIntervals = [15, 30, 60]; // seconds

// =====================
// Elements
// =====================
const loginForm = document.getElementById("loginForm");
const userInput = document.getElementById("username");
const passwordInput = document.getElementById("password");
const showPasswordCheckbox = document.getElementById("showPassword");
const timerDisplay = document.getElementById("timerDisplay");
const lockoutTimeDisplay = document.getElementById("lockoutTimeDisplay");
const loginButton = document.getElementById("loginButton");
const registerButton = document.getElementById("registerButton");
const registerLinkB = document.getElementById('registerLinkB');
const HomeButton = document.getElementById("HomeButton");
const HomeLinkB = document.getElementById('HomeLinkB');
const forgotPasswordContainer = document.getElementById("forgotPasswordContainer");
const registerlink = document.getElementById('register-link');

// =====================
// Show / Hide password
// =====================
if (showPasswordCheckbox) {
    showPasswordCheckbox.addEventListener('change', function () {
        if (passwordInput) {
            passwordInput.type = this.checked ? 'text' : 'password';
        }
    });
}

// =====================
// Login submit
// =====================
if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const username = userInput?.value.trim();
        const password = passwordInput?.value.trim();

        if (!username || !password) {
            alert("Enter both username and password");
            return;
        }

        fetch('../phpdb/db_connectLogin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        })
        .then(response => response.text())
        .then(data => {
            data = data.trim();

            if (data.startsWith("SUCCESS|")) {
                alert("Login successful!");
                resetFailedAttempts();

                const role = data.split("|")[1].toLowerCase();
                if (role === "user") window.location.href = "../phpdb/user_home.php";
                else if (role === "admin") window.location.href = "../phpdb/admin_home.php";
                else if (role === "super_admin") window.location.href = "../phpdb/superadmin_home.php";
                else alert("Unknown role.");
            }
            else if (data === "User not found.") {
                alert("Username does not exist.");
                incrementFailedAttempts();
            }
            else if (data === "Invalid password.") {
                alert("Password is incorrect.");
                incrementFailedAttempts();
            }
            else {
                console.error("Server response:", data);
                alert("Unexpected server response.");
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            alert("Login error occurred.");
        });
    });
}

// =====================
// Failed attempts logic
// =====================
function incrementFailedAttempts() {
    failedAttempts++;

    if (failedAttempts >= 2 && forgotPasswordContainer) {
        showForgotPassword();
    }

    if (
        failedAttempts % 3 === 0 &&
        lockoutIntervals[Math.floor((failedAttempts / 3) - 1)] !== undefined
    ) {
        lockoutTime = lockoutIntervals[Math.floor((failedAttempts / 3) - 1)];
        const lockoutEnd = Date.now() + lockoutTime * 1000;

        localStorage.setItem('lockoutEnd', lockoutEnd);
        localStorage.setItem('lockoutTime', lockoutTime);

        disableLogin();
        updateLockout();
    }

    if (failedAttempts >= 10) {
        disableLogin();
        alert("Maximum login attempts reached.");
    }

    localStorage.setItem('failedAttempts', failedAttempts);
}

function resetFailedAttempts() {
    failedAttempts = 0;
    localStorage.setItem('failedAttempts', failedAttempts);
    resetLockout();

    if (forgotPasswordContainer) {
        forgotPasswordContainer.style.display = "none";
    }
}

// =====================
// Lockout controls
// =====================
function disableLogin() {
    if (loginButton) loginButton.disabled = true;
    if (userInput) userInput.disabled = true;
    if (passwordInput) passwordInput.disabled = true;
    if (registerButton) registerButton.disabled = true;
    if (registerLinkB) registerLinkB.style.pointerEvents = "none";
    if (registerlink) registerlink.style.pointerEvents = "none";
    if (HomeButton) HomeButton.disabled = true;
    if (HomeLinkB) HomeLinkB.style.pointerEvents = "none";
}

function enableLogin() {
    if (loginButton) loginButton.disabled = false;
    if (userInput) userInput.disabled = false;
    if (passwordInput) passwordInput.disabled = false;
    if (registerButton) registerButton.disabled = false;
    if (registerLinkB) registerLinkB.style.pointerEvents = "auto";
    if (registerlink) registerlink.style.pointerEvents = "auto";
    if (HomeButton) HomeButton.disabled = false;
    if (HomeLinkB) HomeLinkB.style.pointerEvents = "auto";
}

function resetLockout() {
    localStorage.removeItem('lockoutEnd');
    localStorage.removeItem('lockoutTime');

    if (timerDisplay) timerDisplay.style.display = "none";
    if (lockoutTimeDisplay) lockoutTimeDisplay.textContent = "";

    enableLogin();
}

// =====================
// Timer
// =====================
function updateLockout() {
    const lockoutEnd = parseInt(localStorage.getItem('lockoutEnd'));

    if (!lockoutEnd) return;

    const remaining = Math.ceil((lockoutEnd - Date.now()) / 1000);

    if (remaining > 0) {
        if (timerDisplay) timerDisplay.style.display = "block";
        if (lockoutTimeDisplay) lockoutTimeDisplay.textContent = remaining;

        disableLogin();
        setTimeout(updateLockout, 1000);
    } else {
        resetLockout();
    }
}

// =====================
// Forgot password
// =====================
function showForgotPassword() {
    if (forgotPasswordContainer) {
        forgotPasswordContainer.innerHTML =
            `<p>Forgot Password? <a href="forgot_password.php">Reset Here</a></p>`;
        forgotPasswordContainer.style.display = "block";
    }
}

// =====================
// Restore lockout on refresh
// =====================
document.addEventListener("DOMContentLoaded", () => {
    const lockoutEnd = parseInt(localStorage.getItem('lockoutEnd'));

    if (lockoutEnd && Date.now() < lockoutEnd) {
        disableLogin();
        updateLockout();
    } else {
        resetLockout();
    }
});
