<?php
session_start();
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

$events = getActiveEvents($conn,$user_id);
$joinedEvents = getJoinedEvents($conn, $user_id);
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
                    <h2 class="content-title">Active Activity List</h2>
                    <hr class="title-line-break">
                </div>
            </div>

            <div class="row py-3">
                <div class="col">
                    <div class="input-group">
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" placeholder="Search..." id="searchbarActivitylist">
                            <label for="searchbar">Search...</label>
                        </div>
                        <span class="input-group-text searchbarbtn"><img class="searchbaricon" src="../img/searchbar.png"></span>
                    </div>
                </div>
            </div>

            <div class="row total-active-activity">
                <div class="col-4">
                <select id="eventFilter" class="form-select form-select-sm" aria-label="Small select example">
                    <option selected value="Ongoing Event">Ongoing Event</option>
                    <option value="Joined Event">Joined Event</option>
                </select>

                </div>
                <div class="col">
                    <span id="totalTitle" class="total-title">Total Activity:</span>
                    <span id="totalActivityCounter" class="total-counter fw-bold">
                        <?php echo count($events); ?>
                    </span>
                </div>


            <div class="container py-5">
                <div class="activeactivity-wrapper" id="activeActivityWrapper">
                    <?php foreach ($events as $event) { ?>
                    <div class="activeactivity">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="banner-container">
                                    <div class="rounded-square">
                                        <img class="img-fluid" src="<?php echo $event['banner']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg">
                                <div class="activeactivity-information">
                                    <h4><?php echo $event['event_name']; ?></h4>
                                    <p><i><?php echo $event['event_description']; ?></i></p>
                                </div>
                            </div>
                        </div>
                        <div class="row activeactivity-redirect-wrapper">
                            <div class="col">
                                <a href="member-activityinfo.php?event_id=<?php echo $event['event_id']; ?>">Register</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
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
        var activeEvents = <?php echo json_encode($events); ?>;
        var joinedEvents = <?php echo json_encode($joinedEvents); ?>;
    </script>
    <script src="../js/activeactivitylist.js"></script>

</body>
</html>
