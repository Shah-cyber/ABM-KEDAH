// sidebar.js

document.querySelector('.register-btn').addEventListener('click', function(event) {
    event.preventDefault();
    openSidebar();
});

function openSidebar() {
    var sidebar = document.getElementById('sidebar');
    var registerBtn = document.querySelector('.registerbtn');

    if (window.innerWidth <= 769) {
        sidebar.style.width = '100%';
    } else {
        sidebar.style.width = '50%';
    }

    document.getElementById('overlay').classList.add('visible');
    sidebar.classList.add('fade-in');

    // Wait for the content to finish fading in before showing the register button
    setTimeout(function() {
        registerBtn.style.opacity = '1';
    }, 500); // Match the duration of the sidebar content fade-in transition
}

function closeSidebar() {
    var sidebar = document.getElementById('sidebar');
    var registerBtn = document.querySelector('.registerbtn');

    sidebar.style.width = '0';
    document.getElementById('overlay').classList.remove('visible');
    sidebar.classList.remove('fade-in');

    // Reset register button opacity when closing sidebar
    registerBtn.style.opacity = '0';
}

// Close sidebar if overlay is clicked
document.getElementById('overlay').addEventListener('click', closeSidebar);

function toggleHiddenAttribute() {
    var element = document.querySelector('.abmkedah-name');
    if (window.innerWidth <= 427) {
        element.removeAttribute('hidden');
    } else {
        element.setAttribute('hidden', 'hidden');
    }
}

// Initial check
toggleHiddenAttribute();

// Listen for resize events
window.addEventListener('resize', toggleHiddenAttribute);

document.addEventListener('DOMContentLoaded', function () {
    var registerSection = document.getElementById('registerSection');
    var loginSection = document.getElementById('loginSection');
    var showLoginForm = document.getElementById('showLoginForm');
    var showRegisterForm = document.getElementById('showRegisterForm');
    var redirectStep1 = document.getElementById('redirectstep1'); // Added this line

    showLoginForm.addEventListener('click', function (event) {
        event.preventDefault();
        registerSection.classList.remove('fade-in');
        setTimeout(function () {
            registerSection.style.display = 'none';
            loginSection.style.display = 'block';
            setTimeout(function () {
                loginSection.classList.add('fade-in');
            }, 10);
        }, 500);
    });

    showRegisterForm.addEventListener('click', function (event) {
        event.preventDefault();
        loginSection.classList.remove('fade-in');
        setTimeout(function () {
            loginSection.style.display = 'none';
            registerSection.style.display = 'block';
            setTimeout(function () {
                registerSection.classList.add('fade-in');
            }, 10);
        }, 500);
    });

    // Function to handle going back to step 1
    redirectStep1.addEventListener('click', function (event) {
        event.preventDefault();
        var wizardStep1 = document.querySelector('.wizardformstep1');
        var wizardStep2 = document.querySelector('.wizardformstep2');

        // Hide step 2 and show step 1
        wizardStep2.style.display = 'none';
        wizardStep1.style.display = 'block';
    });

    function registrationWizardNextStep() {
        var wizardStep1 = document.querySelector('.wizardformstep1');
        var wizardStep2 = document.querySelector('.wizardformstep2');
    
        // Validate step 1 fields manually
        var fullnameInput = document.getElementById('fullname');
        var icInput = document.getElementById('ic');
        var genderInput = document.getElementById('gender');
        var raceInput = document.getElementById('race');
        var religionInput = document.getElementById('religion');
        var birthdateInput = document.getElementById('birthdate');
        var birthplaceInput = document.getElementById('birthplace');
        var homeaddressInput = document.getElementById('homeaddress');
        var phonenumberInput = document.getElementById('phonenumber');
        var statusInput = document.getElementById('userstatus');
        var proofInput = document.getElementById('proof');
    
        // Check validity of each input
        if (fullnameInput.checkValidity() &&
            icInput.checkValidity() &&
            genderInput.checkValidity() &&
            raceInput.checkValidity() &&
            religionInput.checkValidity() &&
            birthdateInput.checkValidity() &&
            birthplaceInput.checkValidity() &&
            homeaddressInput.checkValidity() &&
            phonenumberInput.checkValidity() &&
            statusInput.checkValidity() &&
            proofInput.checkValidity()) {
    
            // All fields in step 1 are valid, proceed to step 2
            wizardStep1.style.display = 'none'; // Hide step 1
            wizardStep2.style.display = 'block'; // Show step 2
            wizardStep2.style.opacity = '1'; // Show step 2 with opacity transition
        } else {
            // If any field is invalid, the form won't proceed
            // You can optionally provide feedback to the user about invalid fields
            console.log('Step 1 form validation failed.');
        }
    }
    
    // Example: Add event listener for next button in step 1
    var nextButtonStep1 = document.getElementById('registrationstep2form');
    if (nextButtonStep1) {
        nextButtonStep1.addEventListener('click', function (event) {
            event.preventDefault();
            registrationWizardNextStep();
        });
    }

    // Initially show the registration form
    setTimeout(function () {
        registerSection.classList.add('fade-in');
    }, 10);
});
