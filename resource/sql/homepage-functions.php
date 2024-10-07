<?php
include 'server.php'; // Include the database connection
session_start(); // Start the session



function MemberRegistration($postData, $fileData) {
    global $conn;
    
    $response = ['status' => 'error', 'message' => 'Unknown error'];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fullname = $postData['fullname'];
        $ic = $postData['ic'];
        $gender = $postData['gender'];
        $race = $postData['race'];
        $religion = $postData['religion'];
        $birthdate = $postData['birthdate'];
        $birthplace = $postData['birthplace'];
        $homeaddress = $postData['homeaddress'];
        $email = $postData['email'];
        $phonenumber = $postData['phonenumber'];
        $userstatus = $postData['userstatus'];
        $username = $postData['username'];
        $password = password_hash($postData['password'], PASSWORD_ARGON2ID); // Hash the password using Argon2
        $proof = $fileData['proof']['name'];

        // Check for duplicate IC number
        $icCheckSql = "SELECT * FROM registration WHERE ic_number='$ic'";
        $icCheckResult = $conn->query($icCheckSql);

        if ($icCheckResult->num_rows > 0) {
            $response['message'] = 'IC number is already registered.';
            return json_encode($response);
        }

        // Check for duplicate email
        $emailCheckSql = "SELECT * FROM registration WHERE email='$email'";
        $emailCheckResult = $conn->query($emailCheckSql);

        if ($emailCheckResult->num_rows > 0) {
            $response['message'] = 'Email is already registered.';
            return json_encode($response);
        }

        // Check for duplicate username
        $usernameCheckSql = "SELECT * FROM member WHERE username='$username'";
        $usernameCheckResult = $conn->query($usernameCheckSql);

        if ($usernameCheckResult->num_rows > 0) {
            $response['message'] = 'Username is already in use.';
            return json_encode($response);
        }

        // Directory to store uploaded files
        $target_dir = "../data/proofofparticipation/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // New file name
        $new_filename = $ic . '_proof.pdf';
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($fileData["proof"]["tmp_name"], $target_file)) {
            $conn->begin_transaction(); // Start transaction

            $registrationSql = "INSERT INTO registration (ic_number, name, gender, race, religion, birthdate, birthplace, address, email, phone_number, prove_letter)
                    VALUES ('$ic', '$fullname', '$gender', '$race', '$religion', '$birthdate', '$birthplace', '$homeaddress', '$email', '$phonenumber', '$new_filename')";

            if ($conn->query($registrationSql) === TRUE) {
                $lastInsertedId = $conn->insert_id; // Get last inserted ID

                $memberSql = "INSERT INTO member (username, password, status, role, ic_number)
                              VALUES ('$username', '$password', '$userstatus', 'user', '$ic')";

                if ($conn->query($memberSql) === TRUE) {
                    $conn->commit(); // Commit transaction
                    $response['status'] = 'success';
                    $response['message'] = 'You have been registered successfully.';
                } else {
                    $conn->rollback(); // Rollback transaction
                    $response['message'] = 'Database error: ' . $conn->error;
                }
            } else {
                $conn->rollback(); // Rollback transaction
                $response['message'] = 'Database error: ' . $conn->error;
            }
        } else {
            $response['message'] = 'File upload error.';
        }
    }

    return json_encode($response);
}






function loginUser($email, $password) {
    global $conn; // Use the global connection

    // Sanitize input
    $email = $conn->real_escape_string($email);

    // Check if email exists in registration table and get ic_number
    $sql_registration = "SELECT r.ic_number, r.email
                         FROM registration r
                         WHERE r.email='$email'";
    $result_registration = $conn->query($sql_registration);

    if ($result_registration->num_rows > 0) {
        $row = $result_registration->fetch_assoc();
        $ic_number = $row['ic_number'];

        // Check in member table
        $sql_member = "SELECT m.user_id, m.username, m.password, m.role
                       FROM member m
                       WHERE m.ic_number='$ic_number'";
        $result_member = $conn->query($sql_member);

        if ($result_member->num_rows > 0) {
            $member_row = $result_member->fetch_assoc();
            $hashed_password = $member_row['password'];

            // Verify password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $member_row['user_id'];
                $_SESSION['role'] = 'member';
                return ['success' => true, 'redirect' => '../member/member-dashboard.php'];
            } else {
                return ['success' => false, 'message' => 'Invalid password for member'];
            }
        } else {
            // Check in admin table if not found in member table
            $sql_admin = "SELECT a.user_id, a.username, a.password, a.role
                          FROM admin a
                          WHERE a.ic_number='$ic_number'";
            $result_admin = $conn->query($sql_admin);

            if ($result_admin->num_rows > 0) {
                $admin_row = $result_admin->fetch_assoc();
                $hashed_password = $admin_row['password'];

                // Verify password
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $admin_row['user_id'];
                    $_SESSION['role'] = 'admin';
                    return ['success' => true, 'redirect' => '../admin/admin-dashboard.php'];
                } else {
                    return ['success' => false, 'message' => 'Invalid password for admin'];
                }
            }
        }
    }

    // Invalid credentials
    return ['success' => false, 'message' => 'Invalid email or password'];
}





function PublicMakePayment($event_id, $billcode, $transaction_id, $payment_status) {
    global $conn;

    // Insert into nonmember table
    $insertNonmemberQuery = "INSERT INTO nonmember (name, email, phone_number) 
                             SELECT name, email, phone_number FROM nonmember_event_reg WHERE nonMember_event_reg_id = ?";
    $stmt = $conn->prepare($insertNonmemberQuery);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();

    // Retrieve the newly inserted nonMember_event_reg_id
    $nonMemberEventRegId = $stmt->insert_id;

    // Insert into joinevent table
    $insertJoineventQuery = "INSERT INTO joinevent (event_id, nonMember_event_reg_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertJoineventQuery);
    $stmt->bind_param("ii", $event_id, $nonMemberEventRegId);
    $stmt->execute();

    // Insert into payment table
    $insertPaymentQuery = "INSERT INTO payment (payment_name, payment_fee, payment_time, payment_date, nonMember_event_reg_id, payment_status, transaction_id) 
                           SELECT event_name, event_price, CURRENT_TIME(), CURRENT_DATE(), ?, ?, ? FROM nonmember_event_reg WHERE nonMember_event_reg_id = ?";
    $stmt = $conn->prepare($insertPaymentQuery);
    $stmt->bind_param("isssi", $nonMemberEventRegId, $payment_status, $transaction_id, $event_id);
    $stmt->execute();
}










// Main entry point
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'register':
            // Call the function to register a member
            $response = MemberRegistration($_POST, $_FILES);
            echo $response;
            break;

        case 'login':
            // Call the function to login a user
            $email = $_POST['email'];
            $password = $_POST['password'];
            $response = loginUser($email, $password);
            echo json_encode($response);
            break;

        default:
            $response = array(
                'status' => 'error',
                'message' => 'Invalid action: ' . $action
            );
            echo json_encode($response);
            break;
    }
}

$conn->close();
?>
