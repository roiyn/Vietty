async function checkValidity(event) {
    event.preventDefault();
    const form = event.currentTarget;

    // establish references to inputs
    const usernameInput = document.getElementById("usernameInput");
    const passwordInput = document.getElementById("passwordInput");
    const passwordComfirmationInput = document.getElementById("passwordComfirmationInput");
    const yearInput = document.getElementById("yearInput");


    const usernameCheck = usernameInput.value;
    const passwordCheck = passwordInput.value;
    const passwordComfirmationCheck = passwordComfirmationInput.value;
    const yearCheck = yearInput.value;

    // get the requirement messages, make all of them show nothing
    document.getElementById("usernameRequirement").textContent =  "";
    document.getElementById("usernameRequirementSymbolsAndNumbers").textContent =  "";
    document.getElementById("usernameRequirementLength").textContent =  "";
    document.getElementById("usernameRequirementExisting").textContent = "";
    document.getElementById("passwordRequirement").textContent =  "";
    document.getElementById("passwordRequirementLength").textContent =  "";
    document.getElementById("passwordRequirementCheck").textContent =  "";
    document.getElementById("yearRequirement").textContent =  "";

    // establish validitiy variables to be initally false
    let usernameValidity = false;
    let passwordValidity = false;
    let passwordComfirmationValidity = false;
    let yearValidity = false;
    let usernameValid = false;

    // check name validity
    if (!usernameCheck) {
        document.getElementById("usernameRequirement").textContent =  "Please enter a username";
        usernameInput.style.borderColor = "red";

    } else if (/[^a-zA-Z ]/.test(usernameCheck)) {
        document.getElementById("usernameRequirementSymbolsAndNumbers").textContent =  "Username must not contain any symbols or numbers";
        usernameInput.style.borderColor = "red";

    } else if (usernameCheck.length < 3 )  {
        document.getElementById("usernameRequirementLength").textContent =  "Username must be at least 3 characters";
        usernameInput.style.borderColor = "red";
    } else {
        usernameValid = await isUsernameOriginal(usernameCheck);
        if (usernameValid === false) {
            document.getElementById("usernameRequirement").textContent = "Username already exists";
            usernameInput.style.borderColor = "red";
            return;
        }
        if (usernameValid === null) {
            document.getElementById("usernameRequirement").textContent = "Unable to validate username, please try again.";
            usernameInput.style.borderColor = "red";
            return;
        } else {
            document.getElementById("usernameRequirement").textContent = "";
            usernameValid = true;
            usernameValidity = true;
            usernameInput.style.borderColor = "#e3d8ca";
        }
    }

    // check password validity
    if (!passwordCheck) {
        document.getElementById("passwordRequirement").textContent =  "Please enter a password";
        passwordInput.style.borderColor = "red";

    } else if (passwordCheck.length < 8 ) {
        document.getElementById("passwordRequirementLength").textContent =  "Password must be at least 8 characters";
        passwordInput.style.borderColor = "red";

    } else {
        passwordValidity = true;
        passwordInput.style.borderColor = "#e3d8ca";
    }

    // check password comfirmation validity
    if (passwordComfirmationCheck !== passwordCheck) {
        document.getElementById("passwordRequirementCheck").textContent =  "Passwords do not match";
        passwordComfirmationInput.style.borderColor = "red";

    } else {
        passwordComfirmationValidity = true;
        passwordComfirmationInput.style.borderColor = "#e3d8ca";
    }

    // check year validity
    if (yearCheck === "") {
        document.getElementById("yearRequirement").textContent =  "Please select a year level";
        yearInput.style.borderColor = "red";

    } else {
        yearValidity = true;
        yearInput.style.borderColor = "#e3d8ca";
    }

    // if all inputs are valid 
    if (!usernameValidity ||
        !passwordValidity ||
        !passwordComfirmationValidity ||
        !yearValidity ||
        !usernameValid) {
        // prevent the form from submitting to php
        event.preventDefault();
        return;
    }

    document.getElementById("usernameRequirement").textContent = "";
    document.getElementById("passwordRequirement").textContent = "";
    document.getElementById("passwordRequirementLength").textContent = "";
    document.getElementById("passwordRequirementCheck").textContent = "";
    document.getElementById("yearRequirement").textContent = "";
    console.log('checkValidity: submitting form');
    form.submit();
}

async function isUsernameOriginal(username) {
    try {
        const response = await fetch(`check_username.php?username=${encodeURIComponent(username)}`);
        const text = await response.text();

        if (!response.ok) {
            console.error('Username validation request failed', response.status, text);
            return null;
        }

        let data;
        try {
            data = JSON.parse(text);
        } catch (parseError) {
            console.error('Username validation response invalid JSON', parseError, text);
            return null;
        }

        if (!data || typeof data.usernameTaken !== 'boolean') {
            console.error('Username validation response invalid', data);
            return null;
        }

        return data.usernameTaken !== true;
    } catch (error) {
        console.error('Username validation request error', error);
        return null;
    }
}

document.querySelector('form').addEventListener('submit', checkValidity);
