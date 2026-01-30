
// Elements 
const passwordInput = document.getElementById('new_password');
const confirmPasswordInput = document.getElementById('confirm_password');
const form = document.querySelector('form');
const submitButton = document.querySelector('.btn');

// Create feedback elements 
const strengthText = document.createElement('div');
strengthText.id = 'pwStrength';
strengthText.style.fontSize = '14px';
strengthText.style.marginTop = '6px';

const matchText = document.createElement('div');
matchText.id = 'pwMatch';
matchText.style.fontSize = '14px';
matchText.style.marginTop = '6px';

passwordInput.parentElement.appendChild(strengthText);
confirmPasswordInput.parentElement.appendChild(matchText);

// Disable submit by default
submitButton.disabled = true;


// Password strength 
function checkPasswordStrength() {
    const password = passwordInput.value;

    const regexWeak = /^[a-zA-Z0-9]{6,}$/;
    const regexStrong = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

    if (!regexWeak.test(password)) {
        strengthText.innerHTML =
            'Weak password. Must be at least 8 characters long and alphanumeric.';
        strengthText.style.color = 'red';
        return false;
    } else if (regexStrong.test(password)) {
        strengthText.innerHTML =
            'Strong password. Alphanumeric with at least 8 characters.';
        strengthText.style.color = 'green';
        return true;
    } else {
        strengthText.innerHTML =
            'Moderate password. Use more characters for a stronger password.';
        strengthText.style.color = 'orange';
        return true;
    }
}


// Password match 
let passwordMatchTimeout;

function checkPasswordMatch() {
    clearTimeout(passwordMatchTimeout);

    passwordMatchTimeout = setTimeout(() => {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (password !== confirmPassword) {
            matchText.innerHTML = 'Passwords do not match.';
            matchText.style.color = 'red';
            submitButton.disabled = true;
        } else {
            matchText.innerHTML = 'Passwords match!';
            matchText.style.color = 'green';

            // Enable submit only if strength is valid
            submitButton.disabled = !checkPasswordStrength();
        }
    }, 1000);
}

// Event listeners

passwordInput.addEventListener('input', () => {
    checkPasswordStrength();
    submitButton.disabled = true;
});

confirmPasswordInput.addEventListener('input', checkPasswordMatch);

// Final submit protection

form.addEventListener('submit', function (e) {
    if (
        !checkPasswordStrength() ||
        passwordInput.value !== confirmPasswordInput.value
    ) {
        e.preventDefault();
        alert('Please fix the password requirements before submitting.');
    }
});
