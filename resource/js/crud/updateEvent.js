document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-updateeventform');
    const event_id = new URLSearchParams(window.location.search).get('event_id'); // Get event_id from URL

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'AUE');
        formData.append('event_id', event_id);

        fetch('../sql/admin-functions.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'admin-activityDisplayList.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 25000
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
