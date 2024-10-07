<?php
session_start();
include '../sql/server.php'; // Ensure database connection is included
include '../sql/nonmember-functions.php';

// Extract parameters from the URL
$event_id = intval($_GET['event_id'] ?? 0);
$transaction_id = $_GET['transaction_id'] ?? '';
$billcode = $_GET['billcode'] ?? '';
$status_id = intval($_GET['status_id'] ?? 0);
$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$phone_number = $_GET['phone_number'] ?? '';
$ic_number = $_GET['ic_number'] ?? '';

if ($status_id == 1) {
    $payment_status = "success"; // Map status_id 1 to "success"
    $payment_status_message = "Successful";
} elseif ($status_id == 2) {
    $payment_status = "pending"; // Map status_id 2 to "pending"
    $payment_status_message = "Pending";
} elseif ($status_id == 3) {
    $payment_status = "fail"; // Map status_id 3 to "fail"
    $payment_status_message = "Unsuccessful";
}

// Check if transaction ID already exists in payment table
if (!CheckTransactionExists($conn, $transaction_id)) {
    // Call PublicMakePayment function with necessary parameters
    PublicMakePayment($event_id, $billcode, $transaction_id, $payment_status, $name, $email, $phone_number, $ic_number);
}

// Fetch event information based on event_id
$event = getEventById($conn, $event_id);



?>













<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Angkatan Belia MFLS Negeri Kedah - Member Page</title>
</head>
<body>
    

    <!-- Header -->
    <?php include '../homepage/homepage-header.php'; ?>

    <div class="content-wrapper">
        <div class="container py-4 d-flex justify-content-center">
            <div class="billingform-container">
                <div class="row mb-5">
                <?php if ($status_id == 1): ?>
            <div class="col-xl gx-5 billingformheader-wrapper">
                <h1>Member Receipt</h1>
                <span><i>*Your event registration for <?php echo htmlspecialchars($event['event_name']); ?> now has been made <i class="bi bi-patch-check"></i></i></span>
            </div>
        <?php elseif ($status_id == 2): ?>
            <div class="col-xl gx-5 billingformheader-wrapper">
                <h1>Member Receipt</h1>
                <span><i>*Your event registration for <?php echo htmlspecialchars($event['event_name']); ?> now is pending<i class="bi bi-patch-check"></i></i></span>
            </div>
        <?php elseif ($status_id == 3): ?>
            <div class="col-xl gx-5 billingformheader-wrapper">
                <h1>Member Receipt</h1>
                <span><i>*Your event registration for <?php echo htmlspecialchars($event['event_name']); ?> was <b class="text-red">unsuccessful.</b></i></span>
            </div>
        <?php else: ?>
            <div class="col-xl gx-5 billingformheader-wrapper">
                <h1>Member Receipt</h1>
                <span><i>*Your event registration status is unknown. Please contact support.<i class="bi bi-patch-check"></i></i></span>
            </div>
        <?php endif; ?>
                    <div class="col-xl gy-4 downloadreceiptbtn-wrapper">
                        <button class="downloadreceiptbtn">Download Receipt</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Billing Order</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $billcode; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Transaction ID</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $transaction_id; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Member Name</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $name; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Email</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $email ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Payment Amount</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo 'RM' . number_format($event['event_price'], 2); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Payment Status</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo  $payment_status_message ?></p>
                    </div>
                </div>
                <div class="row py-5">
                    <div class="gx-5 col-xl">
                        <a class="receiptsuccessredirectbtn" href="../homepage/homepage.php">Return Back to Homepage</a>
                    </div>
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
        $(document).ready(function() {
            $('.downloadreceiptbtn').click(function() {
                window.print();
            });
        });
    </script>
</body>
</html>
