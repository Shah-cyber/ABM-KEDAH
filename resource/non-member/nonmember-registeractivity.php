<?php
session_start();
include '../sql/member-functions.php';

// Assuming 'event_id' is passed via GET parameter in the URL
$event_id = $_GET['event_id'];

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
    <!--Links-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="#">
    <title>Angkatan Belia MFLS Negeri Kedah - Non-Member Page</title>
</head>

<body>
    <!-- Homepage Header -->
    <?php include '../homepage/homepage-header.php'; ?>
    <!-- Overlay for sidebar -->
    <div id="overlay" class="overlay"></div>
    <!-- Homepage Sidebar -->
    <?php include '../homepage/homepage-sidebar.php'; ?>
    <!--Content-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col">
                    <a href="../homepage/homepage.php">
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
                <div class="col d-flex justify-content-center">
                    <div class="eventbannerinfo-wrapper">
                        <img src="<?php echo $event['banner']?>" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <h2 class="content-title">Details</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <span>*Please be <b>aware</b> if you've registered this event before. We can only track if you've already registered this event by using the <b>same email.</b></span>
                </div>
            </div>
            <div class="row py-5">
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
            <form method="POST" id="publicRegisterEventMember" autocomplete="off" action="#">
    <div class="row">
        <div class="col-lg">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInputGroup1" name="name" placeholder="Full name" required>
                    <label for="floatingInputGroup1">Full name</label>
                </div>
            </div>
        </div>
        <div class="col-lg">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                <div class="form-floating">
                    <input type="number" class="form-control" id="floatingInputGroup2" name="phone_number" placeholder="Phone Number" required>
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
                    <input type="number" class="form-control" id="floatingInputGroup3" name="ic_number" placeholder="Identity Card" required>
                    <label for="floatingInputGroup1">Identity Card</label>
                </div>
            </div>
        </div>
        <div class="col-lg">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInputGroup4" name="email" placeholder="Email" required>
                    <label for="floatingInputGroup1">Email</label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row text-center">
        <div class="col-lg">
            <button class="registeractiveeventbtn" id="registerEventButton" data-event-id="<?php echo $event_id; ?>">
                Register
            </button>
        </div>
    </div>
</form>


        </div>
    </div>

    <!--Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
function handleFreeEventRegistration() {
    document.getElementById('registerEventButton').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default form submission

        // Validate required fields
        var form = document.getElementById('publicRegisterEventMember');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (this.textContent.trim() === 'Register') {
            var totalParticipation = <?php echo json_encode($event['total_participation']); ?>;
            if (totalParticipation === 0) {
                Swal.fire({
                    title: 'This event is already full.',
                    icon: 'error'
                });
                return;
            }

            var eventPrice = <?php echo json_encode($event['event_price']); ?>;
            if (eventPrice === null || eventPrice === 0) {
                Swal.fire({
                    title: 'Are you sure you want to join this event?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    icon: 'question'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var event_id = this.getAttribute('data-event-id');
                        var formData = $('#publicRegisterEventMember').serialize() + '&action=PublicJoinFreeEvent&event_id=' + event_id;

                        $.ajax({
                            url: '../sql/nonmember-functions.php',
                            type: 'POST',
                            data: formData,
                            success: function (response) {
                                var responseObject = JSON.parse(response);
                                
                                if (responseObject.status === 'success') {
                                    Swal.fire({
                                        title: 'Successfully Registered!',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function () {
                                        window.location.href = '../homepage/homepage.php';
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: responseObject.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('AJAX Error:', status, error); // Log the error for debugging
                                console.error('Response Text:', xhr.responseText); // Log the response text for debugging
                                Swal.fire({
                                    title: 'An error occurred during registration. Please try again later.',
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
    document.getElementById('registerEventButton').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default form submission

        // Validate required fields
        var form = document.getElementById('publicRegisterEventMember');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (this.textContent.trim() === 'Register') {
            var totalParticipation = <?php echo json_encode($event['total_participation']); ?>;
            if (totalParticipation === 0) {
                Swal.fire({
                    title: 'This event is already full.',
                    icon: 'error'
                });
                return;
            }

            var eventPrice = <?php echo json_encode($event['event_price']); ?>;
            if (eventPrice !== null && eventPrice > 0) {
                Swal.fire({
                    title: 'You will be proceeded to the payment page for the event fee. Proceed?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    icon: 'warning'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var event_id = this.getAttribute('data-event-id');
                        var formData = $('#publicRegisterEventMember').serialize() + '&event_id=' + event_id;

                        $.ajax({
                            url: 'public-payment-page-url.php',
                            type: 'POST',
                            data: formData,
                            success: function (response) {
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
                                console.error('AJAX Error:', status, error);
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
