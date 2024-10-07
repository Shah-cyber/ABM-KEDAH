$(document).ready(function() {
    // Event delegation for dynamically added delete buttons
    $(document).on('click', '[id^="deleteBtn_"]', function(event) {
        const icNumber = $(this).data('ic-number');
        
        console.log('Delete button clicked. IC Number:', icNumber);

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete this member.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to delete member
                $.ajax({
                    url: '../sql/admin-functions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'ADM',
                        ic_number: icNumber
                    },
                    success: function(response) {
                        // Handle success response
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                // Optionally, update the member list or perform any other action
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
                            text: 'Failed to delete member. Please try again.',
                            icon: 'error'
                        });
                    }
                });
            }
        });

        // Prevent default form submission
        event.preventDefault();
    });
});
