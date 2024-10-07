<?php
session_start();
include '../sql/server.php'; // Ensure database connection is included
include '../sql/member-functions.php';



// Extract parameters from the URL
$user_id = intval($_GET['user_id'] ?? 0);
$event_id = intval($_GET['event_id'] ?? 0);
$transaction_id = $_GET['transaction_id'] ?? '';
$billcode = $_GET['billcode'] ?? '';
$status_id = intval($_GET['status_id'] ?? 0);
$role = $_SESSION['role'];

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

// Set nonMember_event_reg_id to null
$nonMember_event_reg_id = null;

// Fetch user information based on user_id
$userInfo = getUserInfo($user_id, $role);

// Fetch event information based on event_id
$event = getEventById($conn, $event_id);

// Check if user and event information were successfully fetched
if ($userInfo && $event) {
    $payment_fee = floatval($event['event_price']); // Get payment fee from event price
    $event_name = $event['event_name']; // Get event name

    // Get current date and time
    $payment_date = date('Y-m-d');
    $payment_time = date('H:i:s');

    // Check if the payment already exists
    $sql = "SELECT payment_id FROM payment WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $billcode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Insert payment into database using the function
        insertPayment($user_id, $event_id, $transaction_id, $payment_fee, $payment_status, $nonMember_event_reg_id, $billcode, $payment_date, $payment_time, $event_name);

        // Reserve slot for the event
        reserveSlot($conn, $event_id);
    }

    // Close the statement
    $stmt->close();
}
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
                        <p><?php echo htmlspecialchars($userInfo['username']); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Address</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo htmlspecialchars($userInfo['address']); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl gx-5">
                        <span>Email</span>
                    </div>
                    <div class="col-xl gx-5">
                        <p><?php echo htmlspecialchars($userInfo['email']); ?></p>
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
                        <a class="receiptsuccessredirectbtn" href="member-dashboard.php">Return Back to Dashboard</a>
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
