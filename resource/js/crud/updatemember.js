document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-updateeventform');
    const event_id = new URLSearchParams(window.location.search).get('event_id'); // Get event_id from URL

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'AUE');
        formData.append('event_id', event_id);

        // Log FormData entries for debugging
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        fetch('../sql/admin-functions.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.text())  // Get response as text
        .then(rawResponse => {
            console.log('Raw Response:', rawResponse); // Log the raw response

            // Attempt to parse the response as JSON
            let data;
            try {
                data = JSON.parse(rawResponse);
            } catch (error) {
                console.error('JSON Parse Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Received invalid JSON from the server.',
                    showConfirmButton: false,
                    timer: 2500
                });
                return;
            }

            // Process the parsed JSON data
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Event information updated successfully!',
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
                    timer: 2500
                });
                console.error('Server Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
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
