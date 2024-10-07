<?php
session_start();
include '../sql/member-functions.php';
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
// Get payment ID from URL
$payment_id = $_GET['payment_id'];
// Retrieve payment details
$paymentDetails = retrievePaymentReceiptDetails($payment_id, $user_id);
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
        <div class="container py-4">
            <div class="billingform-container">
                <div class="row mb-5">
                <?php if ($paymentDetails['payment_status'] == "success"): ?>
            <div class="col-xl gx-5 billingformheader-wrapper">
                <h1>Member Receipt</h1>
                <span><i>*Your event registration for <?php echo htmlspecialchars($paymentDetails['event_name']); ?> now has been made <i class="bi bi-patch-check"></i></i></span>
            </div>
        <?php elseif ($paymentDetails['payment_status'] == "pending"): ?>
            <div class="col-xl gx-5 billingformheader-wrapper">
                <h1>Member Receipt</h1>
                <span><i>*Your event registration for <?php echo htmlspecialchars($paymentDetails['event_name']); ?> now is pending<i class="bi bi-patch-check"></i></i></span>
            </div>
        <?php elseif ($paymentDetails['payment_status'] == "fail"): ?>
            <div class="col-xl gx-5 billingformheader-wrapper">
                <h1>Member Receipt</h1>
                <span><i>*Your event registration for <?php echo htmlspecialchars($paymentDetails['event_name']); ?> was <b class="text-red">unsuccessful.</b></i></span>
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
                        <p><?php echo $paymentDetails['payment_id']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Transaction ID</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $paymentDetails['transaction_id']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Member Name</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $paymentDetails['name']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Address</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $paymentDetails['address']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Email</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $paymentDetails['email']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Payment Amount</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p>RM<?php echo $paymentDetails['payment_fee']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Payment Status</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo $paymentDetails['payment_status']; ?></p>
                    </div>
                </div>
                <div class="row py-5">
                    <div class="gx-5 col-xl">
                        <a class="receiptsuccessredirectbtn" href="member-fee.php">Return Back to Fee List</a>
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
