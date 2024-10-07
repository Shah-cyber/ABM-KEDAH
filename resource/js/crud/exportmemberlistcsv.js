document.addEventListener('DOMContentLoaded', function() {
    var exportButton = document.querySelector('.exportmemberlistbtn'); // Update selector if needed

    exportButton.addEventListener('click', function(e) {
        e.preventDefault();

        // Create a form element to submit the export request
        var exportForm = document.createElement('form');
        exportForm.setAttribute('method', 'post');
        exportForm.setAttribute('action', '../sql/admin-functions.php'); // Adjust URL if needed
        exportForm.innerHTML = '<input type="hidden" name="action" value="exportMemberList">'; // Set the action parameter

        document.body.appendChild(exportForm);

        // Submit the form to trigger CSV download
        exportForm.submit();

        // Clean up: remove the form from the DOM after submission
        document.body.removeChild(exportForm);
    });
});
