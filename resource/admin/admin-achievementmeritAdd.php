<?php
session_start();
include '../sql/admin-functions.php'; 
     $events = grabEventRecords(); 

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
     <?php include 'admin-header.php';?>
    <!--Admin Sidebar-->
    <?php include 'admin-sidebar.php';?>
     <!-- Admin functions -->


    <!--Content-->
    <div class="content-wrapper">
        <div class="container">

            <div class="row">
                    <div class="col">
                        <a href="admin-achievementmeritDisplayList.php">
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
                        <h2 class="content-title">Add Merit</h2>
                        <hr class="title-line-break">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <form id="admin-addmeritform" method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="select_activity" class="form-label">Select Activity</label>
                                    <select class="form-select" id="activity" name="activity" required>
                                        <option selected disabled value="">Choose ...</option>
                                        <?php foreach ($events as $event): ?>
                                            <option value="<?php echo $event['event_id']; ?>"><?php echo $event['event_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="merit_points" class="form-label">Merit Points</label>
                                    <input type="number" class="form-control" id="merit_points" name="merit_points" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="pic" class="form-label">PIC</label>
                                    <input type="text" class="form-control" id="pic" name="pic" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="activity_date" class="form-label">Activity Date</label>
                                    <input type="date" class="form-control" id="activity_date" name="activity_date" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                                </div>
                            </div>

                           
                            <div class="row">
                                        <div class="col d-flex justify-content-center">
                                             <button type="submit" class="addmembersubmitbtn " name="add_merit">Create Merit</button>
                                        </div>                                      
                            </div>

                        </form>
                    </div>
                </div>

        </div>
    </div>


    <!--Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script src="../js/crud/addmerit.js"></script>
    
</body>
</html>