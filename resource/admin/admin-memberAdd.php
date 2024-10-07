<?php
session_start();
include '../sql/admin-functions.php';
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
    <!-- Links -->
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

    <!-- Admin Sidebar -->
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
                    <h2 class="content-title">Add Member</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row mb-5">
                <div class="col">
                    <form id="admin-addmemberform" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="registration-input form-control" id="fullname" name="fullname" placeholder="e.g. Muhammad Alif" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="ic" class="form-label">IC</label>
                                <input type="number" class="registration-input form-control" id="ic" name="ic" required>
                            </div>
                            <div class="col">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select registration-input" id="gender" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="race" class="form-label">Race</label>
                                <input type="text" class="registration-input form-control" id="race" name="race" required>
                            </div>
                            <div class="col">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="registration-input form-control" id="age" name="age" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="registration-input form-control" id="religion" name="religion" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="birthdate" class="form-label">Birth Date</label>
                                <input type="date" class="registration-input form-control" id="birthdate" name="birthdate" required>
                            </div>
                            <div class="col">
                                <label for="birthplace" class="form-label">Birth Place</label>
                                <input type="text" class="registration-input form-control" id="birthplace" name="birthplace" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="homeaddress" class="form-label">Home Address</label>
                            <input type="text" class="registration-input form-control" id="homeaddress" name="homeaddress" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="registration-input form-control" id="email" name="email" placeholder="e.g. Alif24@yahoo.com" required>
                            </div>
                            <div class="col">
                                <label for="phonenumber" class="form-label">Phone Number</label>
                                <input type="tel" class="registration-input form-control" id="phonenumber" name="phonenumber" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="cohortyear" class="form-label">Year of Cohort</label>
                                <select class="form-select registration-input" id="cohortyear" name="cohortyear" required>
                                    <option value="" selected disabled>Select Cohort</option>
                                    <option value="KOHORT 2019">KOHORT 2019</option>
                                    <option value="KOHORT 2020">KOHORT 2020</option>
                                    <option value="KOHORT 2021">KOHORT 2021</option>
                                    <option value="KOHORT 2022">KOHORT 2022</option>
                                    <option value="KOHORT 2023">KOHORT 2023</option>
                                    <option value="KOHORT 2024">KOHORT 2024</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="kemwawasan" class="form-label">Kem Wawasan/Pusat Latihan Negara</label>
                                <input type="text" class="registration-input form-control" id="kemwawasan" name="kemwawasan" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="participationproof" class="form-label">Proof of Participation</label>
                            <input type="file" class="form-control" id="participationproof" name="participationproof" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="userstatus" class="form-label">User Status</label>
                                <select class="form-select registration-input" id="userstatus" name="userstatus" required>
                                    <option value="" selected disabled>Select User Status</option>
                                    <option value="Associate Member">Associate Member</option>
                                    <option value="MFLS Alumni">MFLS Alumni</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center py-3">
                            <button type="submit" class="addmembersubmitbtn">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script src="../js/crud/addmember.js"></script>
</body>

</html>
