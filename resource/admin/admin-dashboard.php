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


$events = grabEventRecords();
$members = grabMemberRecords();

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
    <style>
        .card-container {
            padding: 20px;
        }
        .mini-card {
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .mini-card.blue {
            background-color: #007bff;
        }
        .mini-card.light-blue {
            background-color: #17a2b8;
        }
        .mini-card.purple {
            background-color: #6f42c1;
        }
        .mini-card.green {
            background-color: #28a745;
        }
        .main-card {
            background-color: #F5F5F5;
            border-radius: 8px;
            padding: 20px;
        }
    </style>
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
                    <div class="col py-2 mt-2">
                        <h2 class="content-title" >Welcome <b><?php echo htmlspecialchars($userInfo['username']); ?> </b></h2>
                    </div>
                </div>   

                <div class="row mt-3">
                    <div class="col">
                        <h1 class="content-title">Dashboard</h1>
                        <hr class="title-line-break">
                    </div>
                </div>

            <div class="row py-2">
                <div class="col">
                    <!-- <h2 class="dashboard-title">Dashboard</h2> -->
                    <div class="dashboard-boxes d-flex justify-content-around">
                        <div class="box text-center">
                            <img src="../img/money.png" class="image-fluid" alt="Jumlah Terkumpul">
                            <p>Total Collection</p>
                            <p class="amount">
                                RM 320.50
                            </p>
                        </div>
                        <div class="box text-center">
                            <img src="../img/suitcase.png" alt="Jumlah Projek">
                            <p>Total Project</p>
                            <p class="amount">
                                <?php echo count($events); ?>
                            </p>
                        </div>
                        <div class="box text-center">
                            <img src="../img/people.png" alt="Jumlah Ahli Berdaftar">
                            <p>
                                Total registered member
                            </p>
                            <p class="amount">
                                <?php echo count($members); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="container mt-5">
        <div class="row mb-4">
            <!-- <div class="col-md-3">
                <div class="mini-card blue">
                    <h5>Total Income</h5>
                    <h2>$579,000</h2>
                    <p>Saved 25%</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mini-card light-blue">
                    <h5>Total Expenses</h5>
                    <h2>$79,000</h2>
                    <p>Saved 25%</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mini-card purple">
                    <h5>Cash on Hand</h5>
                    <h2>$0</h2>
                    <p>Saved 25%</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mini-card green">
                    <h5>Net Profit Margin</h5>
                    <h2>$28,639</h2>
                    <p>Saved 65%</p>
                </div>
            </div> -->
        </div>
        <!-- <div class="card main-card">
            <h3>Main Card Content</h3>
            <div class="row">
            <canvas id="eventChart" width="400" height="200"></canvas>
            </div>
        </div>  -->
    </div>

    </div>

    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script>
        var events = <?php echo json_encode($events); ?>;
        var currentPage = <?php echo $currentPage; ?>;
        var totalPages = <?php echo $totalPages; ?>;
    </script>
</body>
</html> 