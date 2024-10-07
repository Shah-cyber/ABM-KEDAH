document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.querySelector('.sidebar');
    var menuButton = document.querySelector('.adminsidebarmenu-wrapper');
    var sidebarClose = document.getElementById('sidebarClose');
    var dropdownToggles = document.querySelectorAll('.sidebar ul li a');

    // Toggle sidebar menu button click event
    if (menuButton) {
        menuButton.addEventListener('click', function() {
            sidebar.style.transform = 'translateX(0%)';
        });
    }

    // Close sidebar button click event
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            sidebar.style.transform = 'translateX(-100%)';
        });
    }

    // Handle window resize events
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.style.transform = 'translateX(0)';
        }
    });

    var isTransitioning = false; // Anti-spam flag

    // Toggle dropdown content and rotate icon on click
    dropdownToggles.forEach(function(toggle) {
        var dropdownContent = toggle.nextElementSibling; // Get the dropdown content

        if (dropdownContent && dropdownContent.classList.contains('dropdown-content')) {
            toggle.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior

                if (isTransitioning) return; // Exit if a transition is in progress

                isTransitioning = true; // Set the flag to true to indicate a transition is in progress

                if (dropdownContent.style.display === 'block') {
                    // Hide the dropdown content
                    dropdownContent.style.opacity = '0';
                    setTimeout(function() {
                        dropdownContent.style.display = 'none';
                        isTransitioning = false; // Reset the flag after the transition completes
                    }, 300); // Match this to the CSS transition duration
                } else {
                    // Show the dropdown content
                    dropdownContent.style.display = 'block';
                    setTimeout(function() {
                        dropdownContent.style.opacity = '1';
                        setTimeout(function() {
                            isTransitioning = false; // Reset the flag after the transition completes
                        }, 300); // Match this to the CSS transition duration
                    }, 10); // Small delay for better transition effect
                }

                // Toggle the rotation of the dropdown icon
                var dropdownIcon = this.querySelector('img.dropdown-icon');
                if (dropdownIcon) {
                    setTimeout(function() {
                        dropdownIcon.style.transform = dropdownContent.style.opacity === '1' ? 'rotate(0deg)' : 'rotate(-90deg)';
                    }, 10); // Delay to ensure the correct value is read
                }
            });

            // Show the dropdown icon on hover if dropdown content is not shown
            toggle.addEventListener('mouseenter', function() {
                var dropdownIcon = this.querySelector('img.dropdown-icon');
                if (dropdownIcon && dropdownContent.style.opacity !== '1') {
                    dropdownIcon.style.opacity = 1;
                }
            });

            // Hide the dropdown icon on mouse leave if dropdown content is not shown
            toggle.addEventListener('mouseleave', function() {
                var dropdownIcon = this.querySelector('img.dropdown-icon');
                if (dropdownIcon && dropdownContent.style.opacity !== '1') {
                    dropdownIcon.style.opacity = 0;
                }
            });
        } else {
            // No need for console.error for dropdown content not found
        }
    });
});
