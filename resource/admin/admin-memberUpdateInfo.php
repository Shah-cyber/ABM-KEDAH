<?php include '../sql/admin-functions.php'; ?>
<?php
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
    $ic_number = $_GET['ic_number'];
    $memberInfo = grabMemberInfo($ic_number);
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
                    <h2 class="content-title">Update Information</h2>
                    <hr class="title-line-break">
                </div>
            </div>
            <div class="row mb-5">
                <div class="col">
                    <form id="admin-updatememberinfoform" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="registration-input form-control" id="fullname" name="fullname" placeholder="e.g. Muhammad Alif" required value="<?php echo isset($memberInfo['name']) ? htmlspecialchars($memberInfo['name']) : ''; ?>">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="ic" class="form-label">IC</label>
                                <input type="text" class="registration-input form-control" id="ic" name="ic" required value="<?php echo isset($memberInfo['ic_number']) ? htmlspecialchars($memberInfo['ic_number']) : ''; ?> " disabled>
                                <input type="hidden" name="ic" value="<?php echo isset($memberInfo['ic_number']) ? htmlspecialchars($memberInfo['ic_number']) : ''; ?>">
                            </div>
                            <div class="col">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select registration-input" id="gender" name="gender" required>
                                    <option value="" disabled>Select Gender</option>
                                    <option value="Male" <?php echo isset($memberInfo['gender']) && $memberInfo['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo isset($memberInfo['gender']) && $memberInfo['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="race" class="form-label">Race</label>
                                <input type="text" class="registration-input form-control" id="race" name="race" required value="<?php echo isset($memberInfo['race']) ? htmlspecialchars($memberInfo['race']) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="registration-input form-control" id="religion" name="religion" required value="<?php echo isset($memberInfo['religion']) ? htmlspecialchars($memberInfo['religion']) : ''; ?>">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="birthdate" class="form-label">Birth Date</label>
                                <input type="date" class="registration-input form-control" id="birthdate" name="birthdate" required value="<?php echo isset($memberInfo['birthdate']) ? $memberInfo['birthdate'] : ''; ?>">
                            </div>
                            <div class="col">
                                <label for="birthplace" class="form-label">Birth Place</label>
                                <input type="text" class="registration-input form-control" id="birthplace" name="birthplace" required value="<?php echo isset($memberInfo['birthplace']) ? htmlspecialchars($memberInfo['birthplace']) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="homeaddress" class="form-label">Home Address</label>
                            <input type="text" class="registration-input form-control" id="homeaddress" name="homeaddress" required value="<?php echo isset($memberInfo['address']) ? htmlspecialchars($memberInfo['address']) : ''; ?>">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="registration-input form-control" id="email" name="email" placeholder="e.g. Alif24@yahoo.com" required value="<?php echo isset($memberInfo['email']) ? htmlspecialchars($memberInfo['email']) : ''; ?>">
                            </div>
                            <div class="col">
                                <label for="phonenumber" class="form-label">Phone Number</label>
                                <input type="tel" class="registration-input form-control" id="phonenumber" name="phonenumber" required value="<?php echo isset($memberInfo['phone_number']) ? htmlspecialchars($memberInfo['phone_number']) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="participationproof" class="form-label">Proof of Participation</label>
                            <input type="file" class="form-control" id="participationproof" name="participationproof">
                                <!-- Display existing file if available -->
                                <?php if (isset($memberInfo['prove_letter']) && !empty($memberInfo['prove_letter'])): ?>
                                    <p>Current File: <a href="../data/proofofparticipation/<?php echo $memberInfo['prove_letter']; ?>" target="_blank"><?php echo $memberInfo['prove_letter']; ?></a></p>
                                    <p>Replace file if needed.</p>
                                <?php endif; ?>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="userstatus" class="form-label">User Status</label>
                                <select class="form-select registration-input" id="userstatus" name="userstatus" required>
                                    <option value="" disabled>Select User Status</option>
                                    <option value="Associate Member" <?php echo isset($memberInfo['user_status']) && $memberInfo['user_status'] === 'Associate Member' ? 'selected' : ''; ?>>Associate Member</option>
                                    <option value="MFLS Alumni" <?php echo isset($memberInfo['user_status']) && $memberInfo['user_status'] === 'MFLS Alumni' ? 'selected' : ''; ?>>MFLS Alumni</option>
                                </select>
                            </div>
                        </div>

                            <!-- Add hidden fields for current IC and email -->
                        <input type="hidden" id="current_ic" name="current_ic" value="<?php echo isset($memberInfo['ic_number']) ? htmlspecialchars($memberInfo['ic_number']) : ''; ?>">
                        <input type="hidden" id="current_email" name="current_email" value="<?php echo isset($memberInfo['email']) ? htmlspecialchars($memberInfo['email']) : ''; ?>">
                        <div class="d-flex justify-content-center py-3">
                            <button type="submit" id="admin-updatememberinfobtn" class="addmembersubmitbtn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/adminuserprofile.js"></script>
    <script src="../js/adminsidebar.js"></script>
    <script>
$(document).ready(function () {
    $('#admin-updatememberinfoform').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Create FormData object from the form
        formData.append('action', 'AUM'); // Append the action to the formData

        $.ajax({
            url: '../sql/admin-functions.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log('AJAX success response:', response); // Log the raw response for debugging

                // Parse the JSON response
                var res = JSON.parse(response);

                if (res.status === 'success') {
                    console.log('Success:', res); // Log success response
                    // Show success message with SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                    }).then(() => {
                        // Redirect to the member list page after successful update
                        window.location.href = 'admin-memberDisplayList.php';
                    });
                } else {
                    console.log('Error:', res); // Log error response
                    // Show error message with SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log('AJAX error response:', xhr, status, error); // Log the error details for debugging
                // Show generic error message with SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error updating the member information.',
                });
            }
        });
    });
});
</script>



</body>

</html>
