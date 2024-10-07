document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-addeventform');
    const eventPriceInput = document.getElementById('event_price');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const eventPriceButton = document.querySelector('.dropdown-toggle');

    // Initial state: Disable event_price input and set button text to "Event Price"
    eventPriceInput.disabled = true;
    eventPriceButton.textContent = 'Event Price';

    // Event listener for dropdown menu items
    dropdownMenu.addEventListener('click', function(event) {
        event.preventDefault();  // Prevent default action
        event.stopPropagation(); // Stop event from bubbling up

        const target = event.target;
        if (target.classList.contains('dropdown-item')) {
            // Update button text based on selection
            eventPriceButton.textContent = target.textContent;

            // Enable/disable event_price input based on selection
            if (target.textContent === 'Paid Event') {
                eventPriceInput.disabled = false;
            } else {
                eventPriceInput.disabled = true;
                eventPriceInput.value = ''; // Clear input value if disabled
            }
        }
    });

    // Event listener for form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Check if event_price input is disabled and event is not free
        if (eventPriceButton.textContent === 'Event Price') {
            e.preventDefault(); // Prevent form submission
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                text: 'Select if the event is free or paid.',
                showConfirmButton: false,
                timer: 2500
            });
        } else {
            // Proceed with form submission
            const formData = new FormData(form);
            formData.append('action', 'AAE'); // Additional action parameter

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
                        text: 'New event added successfully!',
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
        }
    });
});
