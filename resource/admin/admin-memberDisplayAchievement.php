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

if (isset($_GET['ic_number'])) {
    $memberInfo = grabMemberInfo($_GET['ic_number']);
    $meritPrograms = AdminGrabMemberMeritPrograms($_GET['ic_number']);
} else {
    // Handle case where ic_number is not set
    header('Location: admin-memberDisplayList.php');
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
                    <h2 class="content-title">Member Achievement</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row memberinformation-wrapper">
                <div class="table-responsive">
                    <table id="achievement-table" class="table table-striped table-hover">
                        <tbody>
                            <tr>
                                <td>Fullname</td>
                                <td><?php echo htmlspecialchars($memberInfo['name']); ?></td>
                            </tr>
                            <tr>
                                <td>IC Number</td>
                                <td><?php echo htmlspecialchars($memberInfo['ic_number']); ?></td>
                            </tr>
                            <tr>
                                <td>Member Status</td>
                                <td><?php echo htmlspecialchars($memberInfo['user_status']); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row py-2 sessiontotalmerit-wrapper">
                <div class="col">
                        <select class="form-select" id="session" name="session">
                        <option value="" selected disabled>Select Session</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <div class="col d-flex justify-content-end">
                <div class="input-group">
                    <span class="input-group-text" id="inputGroup-sizing-default">Total Merits</span>
                    <input type="text" class="form-control fw-bold text-center" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="<?php echo $memberInfo['total_merit'] == 0 ? 'merit is empty' : $memberInfo['total_merit']; ?>" readonly>
                </div>
                </div>
            </div>
            <div class="row py-5">
                <div class="col generateachievementbtn-wrapper">
                    <button id="generateachievementbtnid" class="generateachievementbtn">Generate Achievement</button>
                </div>
            </div>
            
            <div class="table-responsive memberinformation-wrapper">
        <table id="achievement-table" class="table table-striped table-hover">
            <tbody>
                <tr>
                    <td class="fs-3">Programme Name</td>
                    <td class="text-center">Merit</td>
                    <td class="text-center">Certificate</td>
                </tr>
                <?php foreach ($meritPrograms['meritPrograms'] as $program) : ?>
                    <tr>
                        <td class="fs-6"><?php echo htmlspecialchars($program['event_name']); ?></td>
                        <td class="text-center fs-6"><?php echo htmlspecialchars($program['merit_point']); ?></td>
                        <td class="text-center"> <a href="admin-achievementCertificate.php?merit_id=<?php echo htmlspecialchars($program['merit_id']); ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
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
    <script src="../js/savememberachievement.js"></script>
</body>
</html>