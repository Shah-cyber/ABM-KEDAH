$(document).ready(function() {
    // Event delegation for dynamically added delete buttons
    $(document).on('click', '[id^="deleteBtn_"]', function(event) {
        const paymentID = $(this).data('payment-id');
        
        console.log('Delete button clicked. Payment ID :', paymentID);

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete this fee.',
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
                        action: 'ADF',
                        payment_id : paymentID
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
                            text: 'Failed to delete fee. Please try again.',
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
