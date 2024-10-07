<?php
include '../sql/admin-functions.php';
if (isset($_GET['ic_number'])) {
    $memberInfo = grabMemberInfo($_GET['ic_number']);
}

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
                    <h2 class="content-title">Member Information</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row">
                <div class="col memberinformationgeneratebtn-wrapper">
                    <button class="memberinformationgeneratebtn">Generate Profile</button>
                </div>
            </div>
        
        <div class="table-responsive">
            <table id="Memberinfo-table" class="table table-striped table-hover">
                <colgroup>
                    <col class="col-label">
                    <col>
                </colgroup>
                <tbody>
                    <?php if ($memberInfo): ?>
                        <tr>
                            <td class="fw-bold">Full Name</td>
                            <td><?php echo htmlspecialchars($memberInfo['name']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">IC</td>
                            <td><?php echo htmlspecialchars($memberInfo['ic_number']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Gender</td>
                            <td><?php echo htmlspecialchars($memberInfo['gender']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Race</td>
                            <td><?php echo htmlspecialchars($memberInfo['race']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Age</td>
                            <td>
                                <?php
                                $birthDate = new DateTime($memberInfo['birthdate']);
                                $currentDate = new DateTime();
                                $age = $currentDate->diff($birthDate)->y;
                                echo htmlspecialchars($age);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Religion</td>
                            <td><?php echo htmlspecialchars($memberInfo['religion']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Birth Date</td>
                            <td><?php echo date('j F Y', strtotime($memberInfo['birthdate'])); ?></td>

                        </tr>
                        <tr>
                            <td class="fw-bold">Place of Birth</td>
                            <td><?php echo htmlspecialchars($memberInfo['birthplace']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Home Address</td>
                            <td><?php echo htmlspecialchars($memberInfo['address']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email</td>
                            <td><?php echo htmlspecialchars($memberInfo['email']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Phone Number</td>
                            <td><?php echo htmlspecialchars($memberInfo['phone_number']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Proof of participation</td>
                            <td><a href="#" class="proof-link">Proof</a></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No member information found.</td>
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