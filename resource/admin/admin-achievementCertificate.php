<?php
include '../sql/admin-functions.php';

session_start();
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

// Fetch merit information if merit_id is set
$meritInfo = [];
if (isset($_GET['merit_id'])) {
    $merit_id = $_GET['merit_id'];
    $meritInfo = grabMeritInfo($merit_id);
}
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
                    <a href="admin-memberDisplayList.php">
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
                    <h2 class="content-title">Merit Certificate</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row">
                <div class="col memberinformationgeneratebtn-wrapper">
                    <button class="memberinformationgeneratebtn">Save Merit Certificate</button>
                </div>
            </div>
        
        <div class="table-responsive">
            <table id="Memberinfo-table" class="table table-striped table-hover">
                <colgroup>
                    <col class="col-label">
                    <col>
                </colgroup>
                <tbody>
                    <?php if ($meritInfo): ?>
                        <tr>
                            <td class="fw-bold">Event Name</td>
                            <td><?php echo htmlspecialchars($meritInfo['event_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Allocated by:</td>
                            <td><?php echo htmlspecialchars($meritInfo['person_in_charge_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Person In Charge Phone Number</td>
                            <td><?php echo htmlspecialchars($meritInfo['person_in_charge_phone_number']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Allocation Date</td>
                            <td>  <?php
                                $date = new DateTime($meritInfo['allocation_date']);
                                echo htmlspecialchars($date->format('j F Y'));
                                ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No merit information found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- PDF Viewer Overlay and Container -->
    <div id="pdf-overlay" class="overlay"></div>
    <div id="pdf-container" class="pdf-container" style="display: none;">
        <div class="pdf-header">
            <button id="close-pdf" class="close-btn">&times;</button>
        </div>
        <div id="pdf-viewer" class="pdf-viewer">
            <iframe id="pdf-frame" src="" type="application/pdf"></iframe>
        </div>
    </div>
    


    
    <!--Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script src="../js/savememberinformation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js"></script>
    <!--Store PHP files in the -->     
    <script>var icNumber = "<?php echo isset($_GET['ic_number']) ? $_GET['ic_number'] : ''; ?>";</script>
    <script src="../js/crud/readproofparticipation.js"></script>

</body>
</html>
