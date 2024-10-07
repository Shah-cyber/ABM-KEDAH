document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registrationForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        console.log('Form submission intercepted.');

        const password = document.getElementById('password').value;
        const confirmpassword = document.getElementById('confirmpassword').value;

        if (password !== confirmpassword) {
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Password and Confirm Password do not match!',
            });
            return;
        }

        const formData = new FormData(form);
        formData.append('action', 'register'); // Append action parameter

        fetch('../sql/homepage-functions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response received:', data);

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
                    text: data.message,
                }).then(() => {
                    window.location.href = 'homepage.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Error',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Unknown Error',
                text: 'An unknown error occurred. Please try again later.',
            });
        });
    });
});