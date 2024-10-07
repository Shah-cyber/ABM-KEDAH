document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-addmeritform');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'AAM'); // Additional action parameter

        // Log formData values to the console
        console.log('Form Data Values:');
        formData.forEach((value, key) => {
            console.log(key + ': ' + value);
        });

        fetch('../sql/admin-functions.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.text()) // Get raw response as text
        .then(text => {
            console.log('Raw Response:', text); // Log raw response
            return JSON.parse(text); // Try to parse as JSON
        })
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'New merit added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'admin-achievementmeritDisplayList.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to communicate with the server. Please try again later.',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });
});
