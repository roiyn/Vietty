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

let params = new URLSearchParams(window.location.search);
let error = params.get("error");

if (error === "usernotfound") {
    document.getElementById("usernameRequirementValidity").textContent =
        "Username doesn't exist";

    document.getElementById("usernameInput").style.borderColor = "red";
}

if (error === "wrongpassword") {
    document.getElementById("passwordRequirementValidity").textContent =
        "Incorrect password";

    document.getElementById("passwordInput").style.borderColor = "red";
}
