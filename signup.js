async function checkValidity(event) {
    event.preventDefault();
    const form = event.currentTarget;

    // establish variables for input boxes
    let usernameInput = document.getElementById("usernameInput");
    let passwordInput = document.getElementById("passwordInput");
    let passwordComfirmationInput = document.getElementById("passwordComfirmationInput");
    let yearInput = document.getElementById("yearInput");

    // establish variables to hold the inputs
    let usernameCheck = usernameInput.value;
    let passwordCheck = passwordInput.value;
    let passwordComfirmationCheck = passwordComfirmationInput.value;
    let yearCheck = yearInput.value;

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
    if (!usernameCheck) { // if the username input is empty
        document.getElementById("usernameRequirement").textContent =  "Please enter a username";
        usernameInput.style.borderColor = "red";

    } else if (/[^a-zA-Z ]/.test(usernameCheck)) { // if the username input contains any symbols or numbers
        document.getElementById("usernameRequirementSymbolsAndNumbers").textContent =  "Username must not contain any symbols or numbers";
        usernameInput.style.borderColor = "red";

    } else if (usernameCheck.length < 3 )  { // if the username input is less than 3 characters
        document.getElementById("usernameRequirementLength").textContent =  "Username must be at least 3 characters";
        usernameInput.style.borderColor = "red";

    } else { // if the username input is valid, check if it is original
        usernameValid = await isUsernameOriginal(usernameCheck);
        // if userame already exists
        if (usernameValid === false) {
            document.getElementById("usernameRequirement").textContent = "Username already exists";
            usernameInput.style.borderColor = "red";
            return;
        }
        // if there is an error 
        if (usernameValid === null) {
            document.getElementById("usernameRequirement").textContent = "Unable to validate username, please try again.";
            usernameInput.style.borderColor = "red";
            return;
        // username is orignal and valid
        } else {
            document.getElementById("usernameRequirement").textContent = "Username is available";
            usernameValid = true;
            usernameValidity = true;
            usernameInput.style.borderColor = "#e3d8ca";
        }
    }

    // check password validity
    if (!passwordCheck) { // if the password input is empty
        document.getElementById("passwordRequirement").textContent =  "Please enter a password";
        passwordInput.style.borderColor = "red";

    } else if (passwordCheck.length < 8 ) { // if the password input is less than 8 characters
        document.getElementById("passwordRequirementLength").textContent =  "Password must be at least 8 characters";
        passwordInput.style.borderColor = "red";

    } else {
        passwordValidity = true;
        passwordInput.style.borderColor = "#e3d8ca";
    }

    // check password comfirmation validity
    if (passwordComfirmationCheck !== passwordCheck) { // if the password comfirmation input does not match the password input
        document.getElementById("passwordRequirementCheck").textContent =  "Passwords do not match";
        passwordComfirmationInput.style.borderColor = "red";

    } else {
        passwordComfirmationValidity = true;
        passwordComfirmationInput.style.borderColor = "#e3d8ca";
    }

    // check year validity
    if (yearCheck === "") { // if the year input is empty
        document.getElementById("yearRequirement").textContent =  "Please select a year level";
        yearInput.style.borderColor = "red";

    } else {
        yearValidity = true;
        yearInput.style.borderColor = "#e3d8ca";
    }

    // if any inputs are invalid ..
    if (!usernameValidity ||
        !passwordValidity ||
        !passwordComfirmationValidity ||
        !yearValidity ||
        !usernameValid) {
        // prevent the form from submitting to php
        event.preventDefault();
        return;
    }

    // if all inputs are valid, clear the requirement messages and submit the form
    document.getElementById("usernameRequirement").textContent = "";
    document.getElementById("passwordRequirement").textContent = "";
    document.getElementById("passwordRequirementLength").textContent = "";
    document.getElementById("passwordRequirementCheck").textContent = "";
    document.getElementById("yearRequirement").textContent = "";
    console.log('checkValidity: submitting form');
    form.submit();
}

// to check if the username is orginal / not already existing. (async is used so that an await can be used)
async function isUsernameOriginal(username) {
    try {
        // wait for the response from the check_username.php file.  encode the username to make it safe for use in a URL
        let response = await fetch(`check_username.php?username=${encodeURIComponent(username)}`);
        
        // wait for the response to be converted into text
        let text = await response.text();

        // if any inputs are invalid
        if (!response.ok) {
            return null;
        }

        let data; // establish data as a variable

        // tries to turn the data into readable text 
        try {
            data = JSON.parse(text); // make data readable in js 
        } catch (parseError) { 
            console.error('Username validation response invalid JSON', parseError, text); // if an error occurs show this on the console
            return null;
        }

        // does the data exist? is it a boolean? if no then:
        if (!data || typeof data.usernameTaken !== 'boolean') { 
            console.error('Username validation response invalid', data); // show this on the console
            return null; 
        }

        // the data is valid, username is available
        return data.usernameTaken !== true; 


    } catch (error) {
        console.error('Username validation request error', error); // if error occurs when php is contacting the database(no json received) show this on the console 

        return null;
    }
}

document.querySelector('form').addEventListener('submit', checkValidity);
