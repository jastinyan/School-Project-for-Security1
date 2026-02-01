
<?php
session_start();
require 'db_connection.php';

// Security questions
$questions = [
    "sec_a1" => "What is your mother's first name?",
    "sec_a2" => "Where did you attend Elementary?",
    "sec_a3" => "Where is your birthplace?"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Password Reset Wizard</title>
<link rel="stylesheet" href="../css/forgot_password_flow.css">

</head>
<body>
<div class="page-wrapper">

    <div class="navbar">
        <p class="system-title">Meditation Activity Tracker</p>
        <div class="nav-links">
            <a href="../phpdb/homepage.php">Home</a>
        </div>
    </div>

      <div class="navbar-spacer"></div>
    <div class="wizard-container container">

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="step active">1</div>
            <div class="step">2</div>
            <div class="step">3</div>
            <div class="step">4</div>
        </div>

        <!-- STEP 1: Enter ID -->
        <div class="wizard-step active" id="step1">
            <h3>Enter Username or ID</h3>
            <form id="sendOtpForm">
                <input type="text" name="user_input" placeholder="Username or ID" required>
                <div class="buttons">
                    <div></div>
                    <button type="submit">Send OTP</button>
                </div>
                <p class="error" id="sendOtpError"></p>
                <p class="success" id="sendOtpSuccess"></p>
            </form>
        </div>

        <!-- STEP 2: OTP -->
        <div class="wizard-step" id="step2">
            <h3>OTP Verification</h3>
            <form id="otpForm">
                <input type="hidden" name="id_no" id="hidden_id_no">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <div class="buttons">
                    <button type="button" onclick="prevStep(1)">Back</button>
                    <button type="submit">Verify</button>
                    <button type="button" id="resendBtn">Resend</button>
                </div>
                <p class="error" id="otpError"></p>
                <p class="success" id="otpSuccess"></p>
            </form>
        </div>

        <!-- STEP 3: Security Questions -->
        <div class="wizard-step" id="step3">
            <h3>Security Questions</h3>
            <form id="questionForm">
                <input type="hidden" name="id_no" id="hidden_id_no2">
                <label><?= $questions['sec_a1'] ?></label>
                <input type="text" name="sec_a1" placeholder="Answer" required>
                <label><?= $questions['sec_a2'] ?></label>
                <input type="text" name="sec_a2" placeholder="Answer" required>
                <label><?= $questions['sec_a3'] ?></label>
                <input type="text" name="sec_a3" placeholder="Answer" required>
                <div class="buttons">
                    <button type="button" onclick="prevStep(2)">Back</button>
                    <button type="submit">Next</button>
                </div>
                <div id="sec-msg" class="error"></div>
            </form>
        </div>

        <!-- STEP 4: Reset Password -->
            <div class="wizard-step" id="step4">
                <h3>Reset Password</h3>
                <form id="resetForm">
                    <input type="hidden" name="id_no" id="hidden_id_no3">

                    <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                    <p id="pwStrength"></p>

                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <p id="pwMatch"></p>

                    <div class="buttons">
                        <button type="button" onclick="prevStep(3)">Back</button>
                        <button type="submit">Reset</button>
                    </div>

                    <p class="error" id="resetError"></p>
                    <p class="success" id="resetSuccess"></p>
                </form>
            </div>



    </div>

<script>
// Wizard Navigation
let currentStep = 1;
const showStep = step=>{
    document.querySelectorAll('.wizard-step').forEach(el=>el.classList.remove('active'));
    document.getElementById('step'+step).classList.add('active');
    currentStep = step;
    document.querySelectorAll('.progress-bar .step').forEach((el,idx)=>{
        el.classList.toggle('active', idx<step);
    });
};
const prevStep = step=> showStep(step);

// Step 1: Send OTP
document.getElementById('sendOtpForm').addEventListener('submit', async e=>{
    e.preventDefault();
    const res = await fetch('send_otp_ajax.php',{method:'POST',body:new FormData(e.target)});
    const data = await res.json();
    if(data.status==='success'){
        document.getElementById('sendOtpSuccess').innerText = "OTP sent to your email!";
        document.getElementById('hidden_id_no').value = data.id_no;
        document.getElementById('hidden_id_no2').value = data.id_no;
        document.getElementById('hidden_id_no3').value = data.id_no;
        showStep(2);
    } else { document.getElementById('sendOtpError').innerText = data; }
});

// Step 2: OTP Verification
document.getElementById('otpForm').addEventListener('submit', async e=>{
    e.preventDefault();
    const res = await fetch('verify_otp_ajax.php',{method:'POST',body:new FormData(e.target)});
    const data = await res.text();
    if(data.trim()==='success') showStep(3);
    else document.getElementById('otpError').innerText = data;
});
document.getElementById('resendBtn').addEventListener('click', async ()=>{
    const formData = new FormData();
    formData.append('id_no',document.getElementById('hidden_id_no').value);
    const res = await fetch('verify_otp_ajax.php?resend=1',{method:'POST',body:formData});
    const data = await res.text();
    document.getElementById('otpError').innerText = data;
});

// Step 3: Security Questions(separated js file)


// Step 4: Reset Password
document.getElementById('resetForm').addEventListener('submit', async e=>{
    e.preventDefault();
    const res = await fetch('reset_password_ajax.php',{method:'POST',body:new FormData(e.target)});
    const data = await res.text();
    if(data.trim()==='success'){
        document.getElementById('resetSuccess').innerText = "Password reset successfully!";
        setTimeout(()=>window.location.href='login.php',1500);
    } else document.getElementById('resetError').innerText = data;
});
</script>
    <script src="../script/sec_questions.js"></script>

    <script src="../script/reset_password.js"></script>

    <div class="footer-spacer"></div>

<div class="footer">
    <p class="all">© 2026 Meditation Activity Tracker. All rights reserved.</p>
</div>
</div>
</body>
</html>
