<?php 
session_start();
    include '../sql/admin-functions.php';

    if (isset($_GET['event_id'])) {
        $event_id = intval($_GET['event_id']);
        $eventData = getEventAttendees($event_id);
        $eventDetails = $eventData['event_details'];
        $attendees = $eventData['attendees'];
    } else {
        // Handle the case where event_id is not provided
        echo "Event ID not provided.";
        exit;
    }

    // Number of events per page
    $eventsPerPage = 10;
    // Calculate the total number of pages
    $totalPages = ceil(count($attendees) / $eventsPerPage);
    // Get the current page number from the query string, default to page 1
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

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
    <!-- Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Angkatan Belia MFLS Negeri Kedah - Admin Page</title>
</head>

<body>
    <!-- Header -->
    <?php include 'admin-header.php';?>
    <!-- Admin Sidebar -->
    <?php include 'admin-sidebar.php';?>
    <!-- Admin functions -->

    <!-- Content -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Back button and title -->
            <div class="row">
                <div class="col">
                    <a href="admin-activityDisplayList.php">
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
                    <h2 class="content-title">Report Activity</h2>
                    <hr class="title-line-break">
                </div>
            </div>

            <!-- Event Details -->
            <div class="row memberinformation-wrapper">
                <div class="table-responsive">
                    <table id="achievement-table" class="table table-striped table-hover">
                        <tbody>
                            <tr>
                                <td>Activity Name</td>
                                <td><span id="namaAktiviti"><?php echo htmlspecialchars($eventDetails['event_name']); ?></span></td>
                                <td>Session</td>
                                <td><span><?php echo htmlspecialchars($eventDetails['event_session']); ?></span></td>
                            </tr>
                            <tr>
                                <td>Activity Date</td>
                                <td><span id="tarikhAktiviti"><?php echo htmlspecialchars($eventDetails['event_date']); ?></span></td>
                                <td>Member Involved</td>
                                <td><?php echo count($attendees); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Generate PDF and Allocate Merit Buttons -->
            <div class="row py-4 align-items-center">
                <div class="col d-flex justify-content-between align-items-center">
                    <div class="addexport-wrapper">
                        <a class="addactivitybtn" id="reportgeneratepdf" href="#"><img src="../img/pdf.png"> Generate PDF</a>
                    </div>
                    <div class="addexport-wrapper">
                        <a class="addactivitybtn" id="allocatemerit" href="#"><img src="../img/award.png"> Allocate Merit</a>
                    </div>
                </div>
            </div>

            <div class="row py-2">
                <div class="col">
                    <div class="input-group">
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" placeholder="Search..." id="searchbarMemberAttendees">
                            <label for="searchbar">Search...</label>
                        </div>
                        <span class="input-group-text searchbarbtn"><img class="searchbaricon" src="../img/searchbar.png"></span>
                    </div>
                </div>
            </div>

            <div class="row py-2 align-items-center">
                <div class="col d-flex justify-content-end">
                    <div class="paging-container" id="pagingContainer">
                        <span class="paging-item" id="prevPage">«</span>
                        <!-- Page numbers will be inserted here dynamically -->
                        <span class="paging-item" id="nextPage">»</span>
                    </div>
                </div>
            </div>

            <!-- Attendees Table -->
            <div class="table-responsive">
                <table id="memberTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Member Status</th>
                            <th scope="col">Allocate Merit</th> 
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Dynamic content will be inserted here -->
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
    <script src="../js/eventlist.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script src="../js/generatePdfEventReport.js"></script>
    <script>
        var attendees = <?php echo json_encode($attendees); ?>;
        var currentPage = <?php echo $currentPage; ?>;
        var totalPages = <?php echo $totalPages; ?>;
        var eventId = <?php echo $event_id; ?>;
    </script>
    <script src="../js/eventReport.js"></script>

</body>
</html>