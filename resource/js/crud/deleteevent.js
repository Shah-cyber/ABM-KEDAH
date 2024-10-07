$(document).ready(function() {
    // Event delegation for dynamically added delete buttons
    $(document).on('click', '[id^="deleteBtn_"]', function(event) {
        const event_id = $(this).data('event-id');

        console.log('Delete button clicked. Event ID:', event_id);

        // Prevent default form submission
        event.preventDefault();

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete this event.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable the delete button to prevent multiple clicks
                $(this).prop('disabled', true);

                // AJAX request to delete event
                $.ajax({
                    url: '../sql/admin-functions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'ADE',
                        event_id: event_id
                    },
                    success: function(response) {
                        console.log('AJAX Success Response:', response);

                        // Handle success response
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                // Optionally, update the event list or perform any other action
                                // For example, reload the page to reflect changes
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                        // Handle error (e.g., show error message)
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to delete event. Please try again.',
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        // Re-enable the delete button after the request completes
                        $(this).prop('disabled', false);
                    }
                });
            }
        });
    });
});
