require('./bootstrap');

const loginForm = document.getElementById('login-form');
const password = document.getElementById('password');
const meter = document.getElementById('password-strength-meter');
const indicPassword = document.getElementById('indic-password');

const strength = {
    0: "Worst",
    1: "Bad",
    2: "Weak",
    3: "Good",
    4: "Strong"
}

password.addEventListener('input', function() {
    let val = password.value;
    let result = zxcvbn(val);

    // Update the password strength meter
    meter.value = result.score;

    // Update the text indicator
    if (val !== "") {
        indicPassword.innerHTML = "Strength: " + strength[result.score];
    } else {
        indicPassword.innerHTML = "";
    }
});

loginForm.addEventListener('submit', ev => {
    ev.preventDefault()
})
