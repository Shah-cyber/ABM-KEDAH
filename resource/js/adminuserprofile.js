document.addEventListener('DOMContentLoaded', function() {
    var profileCircle = document.getElementById('profileCircle');
    var profilePopup = document.getElementById('profilePopup');

    if (profileCircle && profilePopup) {
        profileCircle.addEventListener('click', function() {
            if (profilePopup.classList.contains('show')) {
                profilePopup.classList.remove('show');
            } else {
                profilePopup.classList.add('show');
            }
        });

        // Close popup if clicked outside
        document.addEventListener('click', function(event) {
            if (!profileCircle.contains(event.target) && !profilePopup.contains(event.target)) {
                profilePopup.classList.remove('show');
            }
        });
    }
});
