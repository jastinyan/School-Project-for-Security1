let lockTimer = null;

document.getElementById("questionForm").addEventListener("submit", function(e){
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const msgBox = document.getElementById("sec-msg");
    const submitBtn = form.querySelector("button[type='submit']");

    // Reset message
    msgBox.innerHTML = "";
    msgBox.className = "error";

    fetch("sec_questions_ajax.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        clearInterval(lockTimer); // stop any existing lock timer

        if(data.status === "success"){
            submitBtn.disabled = false;
            showStep(4);
            return;
        } 

        else if(data.status === "failed"){
            msgBox.className = "error";
            data.errors.forEach(err => {
                msgBox.innerHTML += `<p>${err}</p>`;
            });
            // Show remaining attempts for current cycle
            msgBox.innerHTML += `<p>Attempts left: ${data.attempts_left}</p>`;
            submitBtn.disabled = false;
        } 

        else if(data.status === "locked"){
            submitBtn.disabled = true;
            msgBox.className = "warning";

            let minutes = parseInt(data.message.match(/\d+/)[0]);
            let seconds = minutes * 60;

            lockTimer = setInterval(() => {
                if(seconds <= 0){
                    clearInterval(lockTimer);
                    msgBox.className = "error";
                    msgBox.innerHTML = "You may try again now. Attempts reset to 3.";
                    submitBtn.disabled = false;
                    return;
                }

                let m = Math.floor(seconds / 60);
                let s = seconds % 60;
                msgBox.innerHTML = `Too many attempts. Try again in ${m}:${s.toString().padStart(2,'0')}`;
                seconds--;
            }, 1000);
        } 

        else if(data.status === "redirect"){
            alert(data.message);
            window.location.href = data.redirect;
        }

    })
    .catch(() => {
        msgBox.className = "error";
        msgBox.innerHTML = "Server error. Please try again.";
        submitBtn.disabled = false;
    });
});
