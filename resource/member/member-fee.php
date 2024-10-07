<?php
session_start();
include '../sql/server.php';
include '../sql/member-functions.php';

// Get user ID from session
$user_id = $_SESSION['user_id'];

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

// Retrieve pending and completed payments
$pendingPayments = retrieveFeePayment($conn, $user_id, "Pending");
$completedPayments = retrieveFeePayment($conn, $user_id, "success");
$completedPayments = array_merge($completedPayments, retrieveFeePayment($conn, $user_id, "fail"));


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Links-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
                    <h2 class="content-title">Fee Payment List</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row py-3">
                <div class="col">
                    <div class="input-group">
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" placeholder="Search..." id="searchbarPaymentList">
                            <label for="searchbar">Search...</label>
                        </div>
                        <span class="input-group-text searchbarbtn"><img class="searchbaricon" src="../img/searchbar.png"></span>
                    </div>
                </div>
            </div>
            <div class="row total-active-activity">
                <div class="col-md-4">
                    <select id="paymentFilter" class="form-select form-select-sm" aria-label="Small select example">
                        <option selected value="Pending Payment">Pending Payment</option>
                        <option value="History Payment">History Payment</option>
                    </select>
                </div>
                <div class="col-md">
                    <span id="totalTitle" class="total-title">Pending Payment:</span>
                    <span id="totalPaymentCounter" class="total-counter fw-bold">0</span>
                </div>
            </div>
            <div class="table-responsive py-4">
                <table id="memberTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Payment Name</th>
                            <th scope="col">Total Fee</th>
                            <th scope="col">Payment Date</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Rows will be inserted here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script>
        var pendingPayments = <?php echo json_encode($pendingPayments); ?>;
        var completedPayments = <?php echo json_encode($completedPayments); ?>;
    </script>
    <script src="../js/member-fee.js"></script>
</body>
</html>
