document.getElementById("signup-button").addEventListener("click", checkValidity)

function checkValidity(event) {
    // establish variables to inputs by the user
    let usernameCheck = document.getElementById("usernameInput").value;
    let passwordCheck = document.getElementById("passwordInput").value;
    let passwordComfirmationCheck = document.getElementById("passwordComfirmationInput").value;
    let yearCheck = document.getElementById("yearInput").value;

    // get the requirement messages, make all of them show nothing
    document.getElementById("usernameRequirement").textContent =  "";
    document.getElementById("usernameRequirementSymbolsAndNumbers").textContent =  "";
    document.getElementById("usernameRequirementLength").textContent =  "";
    document.getElementById("usernameRequirementValidity").textContent = "";
    document.getElementById("passwordRequirement").textContent =  "";
    document.getElementById("passwordRequirementLength").textContent =  "";
    document.getElementById("passwordRequirementCheck").textContent =  "";
    document.getElementById("yearRequirement").textContent =  "";

    // establish validitiy variables to be initally false
    let usernameValidity = false;
    let passwordValidity = false;
    let passwordComfirmationValidity = false;
    let yearValidity = false;


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
        usernameValidity = true;
        usernameInput.style.borderColor = "#e3d8ca";
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
    !yearValidity) {
    // prevent the form from submitting to php
        event.preventDefault();
        return;
    }

    event.preventDefault();

    // checks if the username exists in the database. 
    fetch ("checkexistingusers.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "username=" + encodeURIComponent(usernameCheck)
    })
    .then(function(response) {
        return response.json(); 
    })
    .then(function(data) {

        if (data.existing) {

            document.getElementById("usernameRequirementValidity").textContent = "This username already exists";
            usernameInput.style.borderColor = "red";

            return;
        }

        document.querySelector("form").submit();

    });
}