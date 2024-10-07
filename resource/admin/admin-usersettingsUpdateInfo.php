<?php

include '../sql/admin-functions.php';

 // Check if IC number is provided in the URL
 if (isset($_GET['ic_number'])) {
 $ic_number = $_GET['ic_number'];
 $userInfo = grabUserInfo($ic_number);
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
                    <a href="admin-usersettingsDisplayList.php">
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
                    <h2 class="content-title">Update User</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row mb-5">
                <div class="col">
                    <form id="admin-updateuserinfoform" autocomplete="off" enctype="multipart/form-data">

                        <div class="row mb-3">
                            <div class="col">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="registration-input form-control" id="username" name="username" required value="<?php echo isset($userInfo['username']) ? htmlspecialchars($userInfo['username']) : ''; ?>">
                            </div>
                            <div class="col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="registration-input form-control" id="email" name="email" placeholder="e.g. Alif24@yahoo.com" required value="<?php echo isset($userInfo['email']) ? htmlspecialchars($userInfo['email']) : ''; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="registration-input form-control" id="password" name="password" required value="<?php echo isset($userInfo['password']) ? htmlspecialchars($userInfo['password']) : ''; ?>">
                            </div>
                            <div class="col">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="registration-input form-control" required value="<?php echo isset($userInfo['password']) ? htmlspecialchars($userInfo['password']) : ''; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select registration-input" id="role" name="role">
                                    <option value="" selected disabled>Select User Role</option>
                                    <option value="Admin" <?php echo isset($userInfo['role']) && $userInfo['role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="User" <?php echo isset($userInfo['role']) && $userInfo['role'] === 'User' ? 'selected' : ''; ?>>User</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select registration-input" id="status" name="status" required>
                                    <option value="" selected disabled>Select User Status</option>
                                    <option value="Active" <?php echo isset($userInfo['status']) && $userInfo['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="Deactive" <?php echo isset($userInfo['status']) && $userInfo['status'] === 'Deactive' ? 'selected' : ''; ?>>Deactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Add hidden fiels for current ic number and email -->
                        <input type="hidden" id="current_email" name="current_email" value="<?php echo isset($userInfo['email']) ? htmlspecialchars($userInfo['email']) : ''; ?>">
                        <input type="hidden" id="ic_number" name="ic_number" value="<?php echo isset($userInfo['ic_number']) ? htmlspecialchars($userInfo['ic_number']) : ''; ?>">

                        <div class="d-flex justify-content-center py-3">
                            <button type="submit" id="admin-updateuserinfobtn" class="addmembersubmitbtn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script src="../js/crud/updateuser.js"></script>
</body>

</html>