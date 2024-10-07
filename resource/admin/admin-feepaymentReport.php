<?php
    session_start();
    include '../sql/admin-functions.php';
    if (isset($_GET['event_id'])) {
        $feedetails = grabFeePaymentInfo($_GET['event_id']);
    }

    if (isset($_GET['event_id'])) {
        $feedetailsreport = grabFeePaymentReports($_GET['event_id']);
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


    // Number of members per page
    $feeDetailsReportPerPage = 10;
    // Calculate the total number of pages
    $totalPages = 0;
    if ($feedetailsreport !== null) {
        $totalPages = ceil(count($feedetailsreport) / $feeDetailsReportPerPage);
    }

    // Get the current page number from the query string, default to page 1
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
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
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Angkatan Belia MFLS Negeri Kedah - Admin Page</title>
</head>

<body>
    <!-- Header -->
    <?php include 'admin-header.php'; ?>
    <!--Admin Sidebar-->
    <?php include 'admin-sidebar.php'; ?>

  

    <!--Content-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col">
                    <a href="admin-feepaymentDisplayList.php">
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
                    <h2 class="content-title">Fee Collection Report</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row memberinformation-wrapper">
                <div class="table-responsive">
                    <table id="achievement-table" class="table table-striped table-hover">
                        <tbody>
                            <tr>
                                <td>Fee Name</td>
                                <td><?php echo htmlspecialchars($feedetails['event_name']); ?></td>
                            </tr>
                            <tr>
                                <td>Fee Amount</td>
                                <td>RM <?php echo number_format($feedetails['event_price'], 2); ?></td>
                            </tr>
                            <tr>
                                <td>Fee Collection</td>
                                <?php if ($feedetailsreport == null) : ?>
                                    <td>RM 0.00</td>
                                <?php else : ?>
                                    <td>RM <?php echo number_format($feedetails['event_price'] * count($feedetailsreport), 2); ?></td>
                                <?php endif; ?>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col memberinformationgeneratebtn-wrapper">
                    <button class="memberinformationgeneratebtn">Generate Report</button>
                </div>
            </div>

            <div class="row pt-4">
                <div class="col d-flex justify-content-end">
                    <div class="paging-container" id="pagingContainer">
                        <span class="paging-item" id="prevPage">&lt;</span>
                        <!-- Page numbers will be inserted here dynamically -->
                        <span class="paging-item" id="nextPage">&gt;</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive py-4">
                <table id="achievement-table" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Status</th>
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
        var feedetailsreport = <?php echo json_encode($feedetailsreport); ?>;
        var currentPage = <?php echo $currentPage; ?>;
        var totalPages = <?php echo $totalPages; ?>;
    </script>
    <script src="../js/feepaymentreport.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
const generateBtn = document.querySelector('.memberinformationgeneratebtn');

generateBtn.addEventListener('click', function() {
window.print(); // This triggers the browser's print dialog to save as PDF
});
});
    </script>

</body>

</html>