<?php
session_start();
include '../sql/member-functions.php';

// Retrieve user information
$userInfo = [];
$totalMerit = 0;

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Call the getUserInfo function with the user_id and role from the session
    $userInfo = getUserInfo($user_id, $role);

    // Retrieve total_merit for the user
    $query = "SELECT total_merit FROM member WHERE user_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($totalMerit);
        $stmt->fetch();
        $stmt->close();
    }

    // You can now use $userInfo and $totalMerit as needed
} else {
    // Handle the case when the user is not logged in
    // For example, redirect to the login page or show an error message
    header('Location: ../homepage/homepage.php');
    exit;
}

$joinedEvents = getJoinedEvents($conn, $user_id);
$events = getActiveEvents($conn, $user_id);
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
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Angkatan Belia MFLS Negeri Kedah - Member Page</title>
</head>

<body>
    <!-- Header -->
    <?php include 'member-header.php'; ?>
    <!-- Admin Sidebar -->
    <?php include 'member-sidebar.php'; ?>
    

    <div class="content-wrapper">
        <div class="container">
            <div style="border: 0.1rem solid black; margin:2.5rem 1.25rem; padding:1.25rem; border-radius:1rem;">
                <div class="row">
                    <div class="col" style="margin: 1.25rem ;">
                        <h2 class="content-title" >Welcome <b><?php echo htmlspecialchars($userInfo['username']); ?> </b></h2>
                    </div>
                </div>    
                <div style="display:flex;">            
                    <div style="display:inline-block; border: 0.1rem solid black; padding:1.25rem; border-radius:1rem; width:50%; margin:0 1.25rem;">
                        <div style="display:flex;">
                            <i class="fa-solid fa-address-card fa-5x" style="display:inline-block; margin: 1.25rem 1.25rem 1.25rem 0;"></i>
                            <div>
                                <h4><b><?php echo htmlspecialchars($userInfo['username']); ?> </b></h4>
                                <h6><b>Email:</b> <?php echo htmlspecialchars($userInfo['email']); ?></h6>
                                <h6><b>Address:</b> <?php echo htmlspecialchars($userInfo['address']); ?></h6>
                            </div>   
                        </div>
                                            
                    </div>
                    <div style="display:inline-block; border: 0.1rem solid black;  padding:1.25rem; border-radius:1rem; width:50%; margin:0 1.25rem;">
                        
                        <div style="display:flex;">
                        <i class="fa-solid fa-list-check fa-5x" style="display:inline-block; margin: 1.25rem 1.25rem 1.25rem 0;"></i>
                            <div>
                                <h4><b>Total Activities Involved</b></h4>
                                <h6><?php echo count($joinedEvents); ?></h6>                                
                            </div>   
                        </div>
                    </div>
                    <div style="display:inline-block; border: 0.1rem solid black;  padding:1.25rem; border-radius:1rem; width:50%; margin:0 1.25rem;">
                        
                        <div style="display:flex;">
                        <i class="fa-solid fa-list-check fa-5x" style="display:inline-block; margin: 1.25rem 1.25rem 1.25rem 0;"></i>
                            <div>
                                <h4><b>Total Merit</b></h4>
                                <h6><?php echo $totalMerit; ?></h6>                                
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
</body>

</html>
