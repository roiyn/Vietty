async function checkValidity(event) {
    // prevent the form from submitting to php
    event.preventDefault();
    // get the form element
    const form = event.currentTarget;
    
    // establish input elements
    let usernameInput = document.getElementById("usernameInput");
    let passwordInput = document.getElementById("passwordInput");

    // establish input values
    let usernameCheck = usernameInput.value;
    let passwordCheck = passwordInput.value;

    // clear requirement messages
    document.getElementById("usernameRequirement").textContent =  "";
    document.getElementById("usernameRequirementExisting").textContent = "";
    document.getElementById("passwordRequirement").textContent =  "";

    // establish validity variables
    let usernameValidity = false;
    let passwordValidity = false;
 
    // check name validity
    if (!usernameCheck) { // if the username input is empty
        document.getElementById("usernameRequirement").textContent = "Please enter a username";
        usernameInput.style.borderColor = "red";
        return;
    } else {
        // if the username input is valid, check if it exists in the database
        usernameFound = await isUsernameInDB(usernameCheck);
        if (usernameFound === false) { // if the username does not exist in database
        document.getElementById("usernameRequirementExisting").textContent = "Username does not exist.";
        usernameInput.style.borderColor = "red";
        return;
        }
        if (usernameFound === null) { // if there is an error checking the database
            document.getElementById("usernameRequirement").textContent = "Unable to check username, please try again.";
            usernameInput.style.borderColor = "red";
            return;

        } else { // if the username exists in the database
            document.getElementById("usernameRequirement").textContent = "";
            usernameValidity = true;
            usernameInput.style.borderColor = "#e3d8ca";
        }
    }

    // check password validity
    if (!passwordCheck) { // if the password input is empty
        document.getElementById("passwordRequirement").textContent = "Please enter a password";
        passwordInput.style.borderColor = "red";

    } else {
        // if the password input is valid, check if it matches the username in the database
        passwordValidity = await isPasswordInDB(usernameCheck, passwordCheck);
        if (passwordValidity === false) { // if the password does not match the username in the database
        document.getElementById("passwordRequirement").textContent = "Incorrect password";
        passwordInput.style.borderColor = "red";
        return;
        }
        if (passwordValidity === null) { // if there is an error checking the database
            document.getElementById("passwordRequirement").textContent = "Unable to check password, please try again.";
            passwordInput.style.borderColor = "red";
            return;
        } else {
            document.getElementById("passwordRequirement").textContent = "";
            passwordValidity = true;
            passwordInput.style.borderColor = "#e3d8ca";
        }
    }
    // if either the username or password is invalid, do not submit the form
    if (!usernameValidity || !passwordValidity) {
        return;
    }

    // if both the username and password are valid, submit the form
    form.submit();
}

// check if the username exists in the database
async function isUsernameInDB(username) {
    try {
        const response = await fetch(`login_check_username.php?username=${encodeURIComponent(username)}`); // fetch the php file to check if the username exists in the database
        const text = await response.text(); // get the response text from the php file

        if (!response.ok) { // if the response is not ok, log the error and return null
            console.error('Username validation request failed', response.status, text);
            return null;
        }

        // establish a variable to hold the data from the php file
        let data;
        try {
            data = JSON.parse(text); // turn the response text into a JSON object (so it is readable in js)
        } catch (parseError) {
            console.error('Username validation response invalid JSON', parseError, text); //for debugging
            return null;
        }

        if (!data || typeof data.usernameFound !== 'boolean') { // if the data is not valid, log the error and return null
            console.error('Username validation response invalid', data); //for debugging
            return null;
        }

        return data.usernameFound === true; // return true if the username exists in the database, false if it does not
    } catch (error) { // if there is an error with the fetch request, log the error and return null
        console.error('Username validation request error', error); //for debugging
        return null;
    }
}

// check if the password matches the username in the database
async function isPasswordInDB(username, password) {
    try {
        const response = await fetch(`login_check_password.php?username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`); // fetch the php file to check if the password matches the username in the database
        const text = await response.text(); // get the response text from the php file

        if (!response.ok) { // if the response is not ok, log the error and return null
            console.error('password validation request failed', response.status, text); //for debugging
            return null;
        }

        // establish a variable to hold the data from the php file
        let data;
        try {
            data = JSON.parse(text); // turn the response text into a JSON object (so it is readable in js)
        } catch (parseError) {
            console.error('password validation response invalid JSON', parseError, text); //for debugging
            return null;
        }

        if (!data || typeof data.passwordCorrect !== 'boolean') {// if the data is not valid, log the error and return null
            console.error('password validation response invalid', data); //for debugging
            return null;
        }

        return data.passwordCorrect === true; // return true if the password matches the username in the database, false if it does not
    } catch (error) {
        console.error('password validation request error', error); // establish a variable to hold the data from the php file
        return null;
    }
}

// add an event listener to the form to check the validity of the inputs when the form is submitted (runs the functions)
document.getElementById("login-form").addEventListener("submit", checkValidity);
