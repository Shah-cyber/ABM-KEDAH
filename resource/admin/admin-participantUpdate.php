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
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Angkatan Belia MFLS Negeri Kedah - Admin Page</title>
</head>

<body>
<?php include 'admin-header.php';?>
    <!--Admin Sidebar-->
    <?php include 'admin-sidebar.php';?>
    <!-- Admin functions -->
    <?php include '../sql/admin-functions.php'; ?>

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
                        <h2 class="content-title">Update Merit</h2>
                        <hr class="title-line-break">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <?php
                        $merit_id = $_GET['merit_id'];
                        $merit = grabMeritInfo($merit_id);
                         $events = grabEventRecords();
                        // $merit_event = grabMeritRecords($merit_id);
                        
                        ?>
                        
                        <form id="admin-updatemeritform" method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="select_activity" class="form-label">Select Activity</label>
                                    <select class="form-select" id="activity" name="activity" required disabled>
                                        <!-- <option selected disabled value="">Choose ...</option> -->
                                        <?php foreach ($events as $event): ?>
                                            <option value="<?php echo $event['event_id']; ?>" <?php if ($event['event_id'] == $merit_id) echo 'selected'; ?>><?php echo $event['event_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="merit_point" class="form-label">Merit Points</label>
                                    <input type="number" class="form-control" id="merit_points" name="merit_points" value="<?php echo isset($merit['merit_point']) ? htmlspecialchars($merit['merit_point']) : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="pic" class="form-label">PIC</label>
                                    <input type="text" class="form-control" id="pic" name="pic" value="<?php echo isset($merit['person_in_charge_name']) ? htmlspecialchars($merit['person_in_charge_name']) : ''; ?>" required>

                                </div>
                                <div class="col-md-6">
                                    <label for="activity_date" class="form-label">Activity Date</label>
                                    <input type="date" class="form-control" id="activity_date" name="activity_date" value="<?php echo isset($merit['allocation_date']) ? htmlspecialchars($merit['allocation_date']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo isset($merit['person_in_charge_phone_number']) ? htmlspecialchars($merit['person_in_charge_phone_number']) : ''; ?>" required>
                                </div>
                            </div>
                            <!-- <div class="row mb-3" style="display: none;">
                                <div class="col-md-6">
                                    <input class="form-control" id="action" name="action" value="AUME">
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col d-flex justify-content-center">
                                    <button type="submit" class="addmembersubmitbtn" name="update_merit">Update Merit</button>
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
    <script src="../js/adminsidebar.js"></script>>  
    <!-- <script src="../js/meritlist.js"></script> -->
    <script src="../js/crud/updatemerit.js"></script>
    
</body>
</html>