document.addEventListener('DOMContentLoaded', function() {
    // Get the form element
    const form = document.getElementById('admin-addmemberform');

    // Add submit event listener to the form
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Create a FormData object from the form
        const formData = new FormData(form);
        formData.append('action', 'AANM'); // Additional action parameter 



        // Fetch POST request
        fetch('../sql/admin-functions.php', {
            method: 'POST',
            body: formData,
            // Important to set these headers for FormData
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            // Check if success or error
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'New member added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Redirect or do something else
                    window.location.href = 'admin-memberDisplayList.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message, // Display the specific error message from the server
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
