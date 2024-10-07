document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('proof-link')) {
            e.preventDefault();
            var filename = icNumber + '_proof.pdf';
            var action = "GPP";

            var formData = new FormData();
            formData.append('action', action);
            formData.append('filename', filename);

            fetch('../sql/admin-functions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'exist') {
                    showPDFOverlay(filename);
                } else {
                    // Use SweetAlert to show a message
                    Swal.fire({
                        icon: 'error',
                        title: 'Participation Proof Not Found',
                        text: 'The participation proof for this member was not found.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while checking participation proof.');
            });
        }
    });

    document.getElementById('close-pdf').addEventListener('click', function() {
        hidePDFOverlay();
    });
});

function showPDFOverlay(filename) {
    var overlay = document.getElementById('pdf-overlay');
    var container = document.getElementById('pdf-container');

    overlay.classList.add('show'); // Show the overlay
    container.style.display = 'block'; // Show the PDF container

    var iframe = document.getElementById('pdf-frame');
    var url = '../data/proofofparticipation/' + filename;
    iframe.src = url; // Set the iframe src to load the PDF
}

function hidePDFOverlay() {
    var overlay = document.getElementById('pdf-overlay');
    var container = document.getElementById('pdf-container');
    var iframe = document.getElementById('pdf-frame');

    overlay.classList.remove('show'); // Hide the overlay
    container.style.display = 'none'; // Hide the PDF container
    iframe.src = ''; // Clear the iframe content
}
