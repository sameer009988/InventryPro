const captchaCode = document.getElementById('captcha-code');
const reloadBtn = document.getElementById('reload-captcha');
const form = document.getElementById('signupForm');

// 1. Generate Random Captcha
function generateCaptcha() {
    const characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    let result = '';
    for (let i = 0; i < 6; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    captchaCode.textContent = result;
}

// 2. Initial Call
generateCaptcha();

// 3. Reload on Click
reloadBtn.addEventListener('click', generateCaptcha);

// 4. Form Validation
form.addEventListener('submit', (e) => {
    const pass = document.getElementById('password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    const captchaInput = document.getElementById('captcha-input').value;

    // Check Passwords
    if (pass !== confirmPass) {
        e.preventDefault();
        alert("Passwords do not match!");
        return;
    }

    // Check Captcha
    if (captchaInput !== captchaCode.textContent) {
        e.preventDefault();
        alert("Invalid Captcha! Please try again.");
        generateCaptcha(); 
        document.getElementById('captcha-input').value = "";
        return;
    }

    //alert("Registration Successful!");
});