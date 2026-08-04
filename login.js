document.getElementById("login-button").addEventListener("click", checkForUser)

function checkForUser() {
    let usernameCheck = document.getElementById("usernameInput").value;
    let passwordCheck = document.getElementById("passwordInput").value;

    document.getElementById("usernameRequirement").textContent =  "";
    document.getElementById("usernameRequirementValidity").textContent =  "";
    document.getElementById("passwordRequirement").textContent =  "";
    document.getElementById("passwordRequirementValidity").textContent =  "";

    if (!usernameCheck) {
        document.getElementById("usernameRequirement").textContent =  "Please enter your username";
        usernameInput.style.borderColor = "red";

    } else {
        usernameValidity = true;
        usernameInput.style.borderColor = "#e3d8ca";
    }

    // check password validity
    if (!passwordCheck) {
        document.getElementById("passwordRequirement").textContent =  "Please enter your password";
        passwordInput.style.borderColor = "red";
    
    } else {
        passwordValidity = true;
        passwordInput.style.borderColor = "#e3d8ca";
    }
}
