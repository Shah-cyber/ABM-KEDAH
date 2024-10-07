document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        console.log('Form submission intercepted.');

        const formData = new FormData(form);
        formData.append('action', 'login'); // Append action parameter

        fetch('../sql/homepage-functions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response received:', data);

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    text: 'Redirecting...',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Check if the data.redirect is correct
                    console.log('Redirecting to:', data.redirect);
                    window.location.href = data.redirect; // Redirect to appropriate dashboard
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Error',
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
