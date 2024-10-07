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
    <!--Links-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css"">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
                            <a href="admin-activityDisplayList.php">
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
                            <h2 class="content-title">Update Activity</h2>
                            <hr class="title-line-break">
                        </div>
                    </div>

                    <div class="row">
    <div class="col">
        <?php
            $event_id = $_GET['event_id'];
            $event = grabEventInfo($event_id);

            // Ensure the time is in the correct format for input[type="time"]
            function formatTimeForInput($time) {
                return date('H:i', strtotime($time));
            }
        ?>
        <form id="admin-updateeventform" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="event_name" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" value="<?php echo isset( $event['event_name']) ? htmlspecialchars($event['event_name'] ): ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="event_description" class="form-label">Event Description</label>
                <textarea class="form-control" id="event_description" name="event_description" rows="3" required><?php echo isset($event['event_description']) ? htmlspecialchars($event['event_description']) : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <label for="existing_banner" class="form-label">Existing Event Banner</label><br>
                        <?php if (!empty($event['banner'])): ?>
                            <img src="<?php echo htmlspecialchars($event['banner']); ?>" alt="Existing Banner" style="max-width: 100%; height: auto;"><br>
                        <?php else: ?>
                            <span>No existing banner</span><br>
                        <?php endif; ?>
                        <small class="text-muted">Upload a new banner if needed</small>
                    </div>
                    <div class="col-md-6">
                        <label for="banner" class="form-label">Upload New Event Banner</label>
                        <input type="file" class="form-control" id="banner" name="banner">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                <label for="total_participation" class="form-label">Total Participant</label>
                <input type="number" class="form-control" id="total_participation" name="total_participation" value="<?php echo isset($event['total_participation']) ? htmlspecialchars($event['total_participation']) : ''; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_category" class="form-label">Event Category</label>
                    <select class="form-select" id="event_category" name="event_category" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="public" <?php echo (isset($event['event_category']) && $event['event_category'] == 'public') ? 'selected' : ''; ?>>Public</option>
                        <option value="private" <?php echo (isset($event['event_category']) && $event['event_category'] == 'private') ? 'selected' : ''; ?>>Private</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="event_status" class="form-label">Event Status</label>
                    <select class="form-select" id="event_status" name="event_status" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="running" <?php echo (isset($event['event_status']) && $event['event_status'] == 'running') ? 'selected' : ''; ?>>Running</option>
                        <option value="draft" <?php echo (isset($event['event_status']) && $event['event_status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                        <option value="ended" <?php echo (isset($event['event_status']) && $event['event_status'] == 'ended') ? 'selected' : ''; ?>>Ended</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_session" class="form-label">Event Session</label>
                    <input type="text" class="form-control" id="event_session" name="event_session" value="<?php echo isset($event['event_session']) ? htmlspecialchars($event['event_session']) : ''; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="event_location" class="form-label">Event Location</label>
                    <input type="text" class="form-control" id="event_location" name="event_location" value="<?php echo isset($event['event_location']) ? htmlspecialchars($event['event_location']) : ''; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="event_start_time" value="<?php echo isset($event['event_start_time']) ? formatTimeForInput($event['event_start_time']) : ''; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="event_end_time" value="<?php echo isset($event['event_end_time']) ? formatTimeForInput($event['event_end_time']) : ''; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo isset($event['event_date']) ? htmlspecialchars($event['event_date']) : ''; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="event_price" class="form-label">Event Price</label>
                    <input type="number" class="form-control" id="event_price" name="event_price" value="<?php echo isset($event['event_price']) ? htmlspecialchars($event['event_price']) : ''; ?>" placeholder="RM 0.00" required>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <button type="submit" class="addmembersubmitbtn" name="update_activity">Update</button>
                </div>
            </div>
        </form>
    </div>
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
    <script src="../js/crud/updateEvent.js"></script>
</body>
</html>