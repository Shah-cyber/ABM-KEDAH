<?php
session_start();
include '../sql/member-functions.php';

// Assuming 'event_id' is passed via GET parameter in the URL
$event_id = $_GET['event_id'];

// Retrieve user ID from session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Call the function to check if the user has joined the event
    $joined = CheckIfEventJoined($conn, $event_id, $user_id);
} else {
    // If user ID is not set, assume they have not joined
    $joined = false;
}

// Retrieve user information
$userInfo = [];
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Call the getUserInfo function with the user_id and role from the session
    $userInfo = getUserInfo($user_id, $role);

    // You can now use $userInfo as needed
} else {
    // Handle the case when the user is not logged in
    // For example, redirect to the login page or show an error message
    header('Location: ../homepage/homepage.php');
    exit;
}

// Get event details
$event = getEventById($conn, $event_id);

$date = new DateTime($event['event_date']);
$formatted_date = $date->format('l, F j Y');
$start_time = date('g:i a', strtotime($event['event_start_time']));
$end_time = date('g:i a', strtotime($event['event_end_time']));


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Angkatan Belia MFLS Negeri Kedah - Member Page</title>
</head>

<body>

    <!-- Header -->
    <?php include 'member-header.php'; ?>
    <!-- Admin Sidebar -->
    <?php include 'member-sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col">
                    <a href="member-activity.php">
                        <div class="redirectbackbtn-wrapper">
                            <div class="circle">
                                <img src="../img/back.png">
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row py-4">
                <div class="col">
                    <h2 class="content-title"><?php echo $event['event_name'] ?></h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row px-2">
                <div class="col">
                    <span>Due on <?php echo (new DateTime($event['event_date']))->format('j F Y'); ?></span>
                </div>
            </div>
        </div>

        <div class="container py-3">
            <div class="row">
                <div class="col">
                    <div class="eventbannerinfo-wrapper">
                        <img src="<?php echo $event['banner']?>" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="row py-4">
                <div class="col">
                    <h2 class="content-title">Details</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <h4>About this event</h4>
                    <p><?php echo $event['event_description']  ?></p>
                </div>
                <div class="col-lg gx-5 gy-3">
                    <div class="eventinformation-wrapper">
                        <h6>Date & Time</h6>
                        <i class="bi bi-calendar m-2"></i><span><?php echo $formatted_date . ' ' . $start_time . ' - ' . $end_time; ?></span>
                        <h6 class="mt-3">Location</h6>
                        <i class="bi bi-geo-alt m-2"></i><span><?php echo $event['event_location']  ?></span>
                        <h6 class="mt-3">Price</h6>
                        <i class="bi bi-credit-card m-2"></i> <span><?php echo is_null($event['event_price']) ? "Event is free to join" : "RM" . number_format((float)$event['event_price'], 2, '.', ''); ?></span>
                        <h6 class="mt-3">Capacity</h6>
                        <i class="bi bi-people-fill m-2"></i><span><?php echo $event['total_participation']?> slots left</span>
                    </div>
                </div>
            </div>
            <div class="row py-4">
                <div class="col">
                    <h2 class="content-title">Personal Information</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row">
                <div class="col-lg">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1" placeholder="Username"
                                value="<?php echo htmlspecialchars($userInfo['username']); ?>" readonly>
                            <label for="floatingInputGroup1">Username</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup2"
                                placeholder="Phone Number"
                                value="<?php echo htmlspecialchars($userInfo['phone_number']); ?>" readonly>
                            <label for="floatingInputGroup1">Phone Number</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-passport"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup3" placeholder="Identity Card"
                                value="<?php echo htmlspecialchars($userInfo['ic_number']); ?>" readonly>
                            <label for="floatingInputGroup1">Identity Card</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup4" placeholder="Email"
                                value="<?php echo htmlspecialchars($userInfo['email']); ?>" readonly>
                            <label for="floatingInputGroup1">Email</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center py-3">
                <div class="col-lg">
                    <button class="registeractiveeventbtn" id="registerButton" data-user-id="<?php echo $user_id; ?>">
                        <?php echo $joined ? 'Joined' : 'Register'; ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script>
function handleFreeEventRegistration() {
    document.getElementById('registerButton').addEventListener('click', function () {
        if (this.textContent.trim() === 'Register') { // Check if button text is 'Register'
            // Check if total participation limit is reached
            var totalParticipation = <?php echo json_encode($event['total_participation']); ?>;
            if (totalParticipation === 0) {
                Swal.fire({
                    title: 'This event is already full.',
                    icon: 'error'
                });
                return; // Exit function if event is full
            }

            // Check if the event is free
            var eventPrice = <?php echo json_encode($event['event_price']); ?>;
            if (eventPrice === null || eventPrice === 0) { // Adjust condition to check for 0 price
                Swal.fire({
                    title: 'Are you sure you want to join this event?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    icon: 'question'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var user_id = this.getAttribute('data-user-id'); // Get user_id from button data attribute
                        var event_id = '<?php echo $event_id; ?>'; // Get event_id from PHP variable

                        $.ajax({
                            url: '../sql/member-functions.php',
                            type: 'POST',
                            data: {
                                action: 'joinFreeEvent',
                                user_id: user_id,
                                event_id: event_id
                            },
                            dataType: 'json', // Ensure jQuery parses the response as JSON
                            success: function (response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Success',
                                        text: response.message,
                                        icon: 'success'
                                    }).then(() => {
                                        // Optionally, update the button text to 'Joined' or disable it
                                        document.getElementById('registerButton').textContent = 'Joined';
                                        document.getElementById('registerButton').disabled = true;
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'There was an error processing your request. Please try again.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            }
        }
    });
}
</script>
<script>
    function handlePaidEventRegistration() {
    document.getElementById('registerButton').addEventListener('click', function () {
        if (this.textContent.trim() === 'Register') { // Check if button text is 'Register'
            // Check if total participation limit is reached
            var totalParticipation = <?php echo json_encode($event['total_participation']); ?>;
            if (totalParticipation === 0) {
                Swal.fire({
                    title: 'This event is already full.',
                    icon: 'error'
                });
                return; // Exit function if event is full
            }

            // Check if the event is paid
            var eventPrice = <?php echo json_encode($event['event_price']); ?>;
            if (eventPrice !== null && eventPrice > 0) { // Adjust condition to check for positive price
                Swal.fire({
                    title: 'You will be proceeded to the payment page for the event fee. Proceed?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    icon: 'warning'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var user_id = this.getAttribute('data-user-id'); // Get user_id from button data attribute
                        var event_id = '<?php echo $event_id; ?>'; // Get event_id from PHP variable

                        $.ajax({
                            url: 'payment-page-url.php', // URL to your payment handling PHP file
                            type: 'POST',
                            data: { user_id: user_id, event_id: event_id },
                            dataType: 'json', // Ensure jQuery parses the response as JSON
                            success: function (response) {
                                console.log(response); // Log the response for debugging
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Redirecting to Payment Page',
                                        text: response.message,
                                        icon: 'info',
                                        timer: 3000,
                                        willClose: () => {
                                            window.location.href = 'https://dev.toyyibpay.com/' + response.billCode;
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('AJAX Error:', status, error); // Log the error for debugging
                                console.error('Response Text:', xhr.responseText); // Log the response text for debugging
                                Swal.fire({
                                    title: 'Error',
                                    text: 'There was an error processing your request. Please try again.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            }
        }
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var eventPrice = <?php echo json_encode($event['event_price']); ?>;
    if (eventPrice === null || eventPrice === 0) {
        handleFreeEventRegistration();
    } else {
        handlePaidEventRegistration();
    }
});
</script>



</body>

</html>
