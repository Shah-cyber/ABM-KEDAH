<?php
session_start();
include '../sql/admin-functions.php';
$feeCollections = grabPaymentCollections();
// Number of members per page
$feeCollectionPerPage = 10;
// Calculate the total number of pages
$totalPages = ceil(count($feeCollections) / $feeCollectionPerPage);
// Get the current page number from the query string, default to page 1
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Angkatan Belia MFLS Negeri Kedah - Admin Page</title>
</head>

<body>
    <!-- Header -->
    <?php include 'admin-header.php'; ?>
    <!-- Admin Sidebar -->
    <?php include 'admin-sidebar.php'; ?>

    <!--Content-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="content-title">Fee Collection List</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row py-2">
                <div class="col addexport-wrapper text-center">
                    <a class="addmemberbtn" href="admin-feecollectionAdd.php"><img src="../img/addmember.png"> Add New Payment</a>
                </div>
            </div>

            <!-- Placeholder for total member count -->
            <div class="row py-3">
                <div class="col memberdisplay-wrapper">
                    <div class="member-display">
                        <span class="total-title">Total Collection:</span>
                        <span class="total-counter fw-bold">
                            <?php echo count($feeCollections); // Output the total number of members 
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row py-4">
                <div class="col">
                    <div class="input-group">
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" placeholder="Search..." id="searchbar">
                            <label for="searchbar">Search...</label>
                        </div>
                        <span class="input-group-text searchbarbtn"><img class="searchbaricon" src="../img/searchbar.png"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col d-flex justify-content-end">
                    <div class="paging-container" id="pagingContainer">
                        <span class="paging-item" id="prevPage">&lt;</span>
                        <!-- Page numbers will be inserted here dynamically -->
                        <span class="paging-item" id="nextPage">&gt;</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive py-4">
                <table id="feeCollectionTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Payment Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Rows will be inserted here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <!-- Pass PHP variables to JavaScript -->
    <script>
        var feeCollections = <?php echo json_encode($feeCollections); ?>;
        var currentPage = <?php echo $currentPage; ?>;
        var totalPages = <?php echo $totalPages; ?>;
    </script>
    <script src="../js/feecollectionlist.js"></script>
    <script src="../js/crud/deletefee.js"></script>

</body>

</html>