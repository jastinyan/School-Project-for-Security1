// Lockout variables
let failedAttempts = localStorage.getItem('failedAttempts') ? parseInt(localStorage.getItem('failedAttempts')) : 0;
let lockoutTime = localStorage.getItem('lockoutTime') ? parseInt(localStorage.getItem('lockoutTime')) : 0;
const lockoutIntervals = [15, 30, 60]; // Time intervals for lockout in seconds

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


// Show/Hide password functionality
showPasswordCheckbox.addEventListener('change', function () {
    passwordInput.type = this.checked ? 'text' : 'password';
});


// Form validation and submission handler
loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    fetch('../phpdb/db_connectLogin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`


    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server error: ' + response.status);
        }
        return response.text();
    })
    .then(data =>{
        console.log("Response from server:", data);
    // Validate inputs
        if (data === "Login successful!") {
            alert("Login successful!");
            window.location.href = '../phpdb/dashboard.php'; // Redirect to dashboard
            resetFailedAttempts();

        } else if (data === "User not found.") {
            alert("Username does not exist.");
            return;
        }
        else if (data === "Invalid password.") {
            alert("Password is incorrect.");
            incrementFailedAttempts();
            return;
        }
        
})
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred during log in process");
    });
});



// Increment failed login attempts
function incrementFailedAttempts() {
    failedAttempts++;

    if (failedAttempts >= 2) {
        showForgotPassword();
    }

    // Lockout logic triggered every 3rd failed attempt
    if (failedAttempts % 3 === 0) {
        const index = Math.floor((failedAttempts / 3) - 1);
        if (index < lockoutIntervals.length) {
            lockoutTime = lockoutIntervals[index]; // Set lockout time
            timerDisplay.style.display = "block";
            updateLockout();
            disableLogin();
            window.addEventListener('pointermove', disableBackButton);
        }
    }

    // Handle maximum failed attempts
    if (failedAttempts >= 10) {
        alert("Maximum login attempts reached. Please try again later.");
        disableLogin(); // Disable login inputs and buttons
        window.addEventListener('pointermove', disableBackButton);
        return; // Prevent further execution
    }

        if (lockoutTime > 0) {
            window.addEventListener('pointermove', disableBackButton);
        }
    

    localStorage.setItem('failedAttempts', failedAttempts);
}

// Reset lockout time without resetting failed attempts
function resetLockout() {
    lockoutTime = 0;
    localStorage.setItem('lockoutTime', lockoutTime);
    timerDisplay.style.display = "none";

    registerButton.disabled = false;
    userInput.disabled = false;
    passwordInput.disabled = false;

    loginButton.disabled = false;
    loginButton.style.cursor = 'pointer';
    loginButton.style.color = '';

    registerLinkB.disabled = false;
    registerLinkB.style.cursor = 'pointer';
    registerLinkB.style.color = '';

    registerlink.disabled = false;  
    registerlink.style.cursor = 'pointer';
    registerlink.style.color = '';

    registerButton.style.cursor = 'pointer';
    registerButton.disabled = false;
     
    HomeLinkB.disabled = false;
    HomeLinkB.style.cursor = 'pointer';
    HomeLinkB.style.color = '';

    HomeButton.disabled = false;
    HomeButton.style.cursor = 'pointer';
    HomeButton.style.color = '';

    enableLinks();
}

// Reset everything on successful login
function resetFailedAttempts() {
    failedAttempts = 0;
    resetLockout();
    forgotPasswordContainer.style.display = "none"; // Hide forgot password link
    localStorage.setItem('failedAttempts', failedAttempts);
}

//Disable login and register during lockout
function disableLogin() {
    loginButton.disabled = true;
    loginButton.style.cursor = 'not-allowed';
    loginButton.style.color = 'gray';

    userInput.disabled = true;
    passwordInput.disabled = true;

    registerButton.disabled = true;
    registerButton.style.cursor = 'not-allowed';

    registerLinkB.disabled = true;
    registerLinkB.style.cursor = 'not-allowed';
    registerLinkB.style.color = 'gray';

    registerlink.disabled = true;
    registerlink.style.cursor = 'not-allowed';
    registerlink.style.color = 'gray';

    HomeLinkB.disabled = true;
    HomeLinkB.style.cursor = 'not-allowed';
    HomeLinkB.style.color = 'gray';

    HomeButton.disabled = true;
    HomeButton.style.cursor = 'not-allowed';
    HomeButton.style.color = 'gray';
    
    disableLinks();
    window.addEventListener('pointermove', disableBackButton);
}

// Update lockout timer display
function updateLockout() {
    if (lockoutTime > 0) {
        lockoutTime--; // Decrease lockout time
        localStorage.setItem('lockoutTime', lockoutTime); // Save updated lockout time
        lockoutTimeDisplay.textContent = `${lockoutTime} `;
        setTimeout(updateLockout, 1000); // Continue countdown every second

    } else {
        resetLockout(); // Reset after lockout expires
        lockoutTimeDisplay.textContent = ""; // Clear the lockout message
    }
}



// Show forgot password link after 2 consecutive failed attempts
function showForgotPassword(includeReset) {
    forgotPasswordContainer.innerHTML = `<p>Forgot Password? <a href="otp.php">Reset Here</a></p>`;
    forgotPasswordContainer.style.display = "block"; // Ensure it's visible
}
if (failedAttempts >= 2) {
    showForgotPassword();
}
// Check lockout status on page load (if page was reloaded during lockout)
if (lockoutTime > 0) {
    timerDisplay.style.display = "block";
    updateLockout();
    disableLogin();
    showForgotPassword();
} else {
    timerDisplay.style.display = "none"; // Hide the timer if not in lockout
}

// Function to disable links
function disableLinks() {
    // Disable the anchor tags
    HomeLinkB.classList.add("disabled");
    HomeLinkB.removeAttribute("href"); // Remove the 'href' to disable the link

    registerLinkB.classList.add("disabled");
    registerLinkB.removeAttribute("href");

    registerlink.classList.add("disabled");
    registerlink.removeAttribute("href");
}

// Function to enable links
function enableLinks() {
    // Re-enable the anchor tags
    registerlink.classList.remove("disabled");
    registerlink.setAttribute("href", "../phpdb/register.php");

    HomeLinkB.classList.remove("disabled");
    HomeLinkB.setAttribute("href", "../phpdb/homepage.php");

    registerLinkB.classList.remove("disabled");
    registerLinkB.setAttribute("href", "../phpdb/register.php");
}

const disableBackButton = () => {
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
};