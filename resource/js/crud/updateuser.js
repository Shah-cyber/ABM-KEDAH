document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-updateuserinfoform');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'AUU');



        fetch('../sql/admin-functions.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'User information updated successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'admin-usersettingsDisplayList.php';
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
    });
});
