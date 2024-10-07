<?php
// Include server.php to establish database connection
include 'server.php';

// Function to grab member records
function grabMemberRecords() {
    global $conn; // Access the $conn variable from server.php

    $records = array();

    // Query to fetch records from member table joined with registration table using ic_number
    $sql = "SELECT m.user_id, m.username, m.status, m.role, r.email, m.ic_number
            FROM member m
            INNER JOIN registration r ON m.ic_number = r.ic_number";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch associative array of each row
        while($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    }

    return $records;
}


function grabMemberInfo($ic_number) {
    global $conn; // Access the $conn variable from server.php

    $sql = "SELECT r.*, m.status AS user_status, m.total_merit AS total_merit 
            FROM registration AS r
            LEFT JOIN member AS m ON r.ic_number = m.ic_number
            WHERE r.ic_number = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ic_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}



function countTotalMembers() {
    global $conn; // Access the $conn variable from server.php

    // Query to count total records in the member table
    $sql = "SELECT COUNT(*) AS total_members FROM member";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_members']; // Return the total count
    } else {
        return 0; // Return 0 if no records found
    }
}





function AdminAddNewMembers($fullname, $ic, $gender, $race, $age, $religion, $birthdate, $birthplace, $homeaddress, $email, $phonenumber, $cohortyear, $kemwawasan, $participationproof, $userstatus) {
    global $conn;

    // Check if the member with this IC number already exists
    $sql_check_ic_existence = "SELECT * FROM member WHERE ic_number = '$ic'";
    $result_check_ic_existence = $conn->query($sql_check_ic_existence);

    if ($result_check_ic_existence->num_rows > 0) {
        // Member already exists with this IC number
        $response = array(
            'status' => 'error',
            'message' => 'This person already registered themselves with the IC Number as a member.'
        );
        echo json_encode($response);
        return;
    }

    // Check if the email already exists in registration table with approved status
    $sql_check_email_existence = "SELECT * FROM registration WHERE email = '$email' AND registration_status = 'approved'";
    $result_check_email_existence = $conn->query($sql_check_email_existence);

    if ($result_check_email_existence->num_rows > 0) {
        // Email already exists in registration table with approved status
        $response = array(
            'status' => 'error',
            'message' => 'This email address is already registered. Please use a different email address.'
        );
        echo json_encode($response);
        return;
    }

    // Directory to store uploaded files
    $upload_dir = '../data/proofofparticipation/';

    // Check if the directory exists or create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle file upload
    $prove_letter_filename = '';
    if (isset($_FILES['participationproof'])) {
        $prove_letter_tmp = $_FILES['participationproof']['tmp_name'];
        $prove_letter_original_name = basename($_FILES['participationproof']['name']);
        $prove_letter_extension = pathinfo($prove_letter_original_name, PATHINFO_EXTENSION);
        $prove_letter_filename = $ic . '_proof.' . $prove_letter_extension; // Rename file as ic_number_proof.extension
        $prove_letter_destination = $upload_dir . $prove_letter_filename;

        if (!move_uploaded_file($prove_letter_tmp, $prove_letter_destination)) {
            // Handle upload error if necessary
            $response = array(
                'status' => 'error',
                'message' => 'Failed to upload proof of participation file.'
            );
            echo json_encode($response);
            return;
        }
    }

    // Insert into registration table
    $sql_registration = "INSERT INTO registration (ic_number, name, gender, race, age, religion, birthdate, birthplace, address, email, phone_number, cohort_year, kem_wawasan_placement, prove_letter, registration_status)
                         VALUES ('$ic', '$fullname', '$gender', '$race', '$age', '$religion', '$birthdate', '$birthplace', '$homeaddress', '$email', '$phonenumber', '$cohortyear', '$kemwawasan', '$prove_letter_filename', 'approved')";

    if ($conn->query($sql_registration) === TRUE) {
        // Retrieve the auto-generated id for the next insertion
        $last_id = $conn->insert_id;

        // Insert into member table
        $username = $fullname;
        $hashed_password = password_hash('123', PASSWORD_ARGON2ID); // Example hashing, use appropriate hashing method
        $status = $userstatus;
        $role = 'user';

        $sql_member = "INSERT INTO member (user_id, username, password, status, role, ic_number)
                       VALUES ('$last_id', '$username', '$hashed_password', '$status', '$role', '$ic')";

        if ($conn->query($sql_member) === TRUE) {
            // Success response
            $response = array(
                'status' => 'success',
                'message' => 'New member added successfully!'
            );
        } else {
            // Error response for member table insertion
            $response = array(
                'status' => 'error',
                'message' => 'Error inserting into member table: ' . $conn->error
            );
        }
    } else {
        // Error response for registration table insertion
        $response = array(
            'status' => 'error',
            'message' => 'Error inserting into registration table: ' . $conn->error
        );
    }

    // Output JSON response
    echo json_encode($response);
}

function AdminUpdateMember($fullname, $ic, $gender, $race, $religion, $birthdate, $birthplace, $homeaddress, $email, $phonenumber, $participationproof, $userstatus, $current_ic, $current_email, $current_prove_letter_filename) {
    global $conn;

    // Initialize response array
    $response = array();

    // Check if the member with this IC number already exists but is not the current member
    if ($ic !== $current_ic) {
        $sql_check_ic_existence = "SELECT * FROM member WHERE ic_number = '$ic'";
        $result_check_ic_existence = $conn->query($sql_check_ic_existence);

        if ($result_check_ic_existence->num_rows > 0) {
            // Another member already exists with this IC number
            $response = array(
                'status' => 'error',
                'message' => 'Another person is already registered with this IC Number.'
            );
            echo json_encode($response);
            return;
        }
    }

    // Check if the email already exists in the registration table with approved status but is not the current member's email
    if ($email !== $current_email) {
        $sql_check_email_existence = "SELECT * FROM registration WHERE email = '$email'";
        $result_check_email_existence = $conn->query($sql_check_email_existence);

        if ($result_check_email_existence->num_rows > 0) {
            // Another member already exists with this email address
            $response = array(
                'status' => 'error',
                'message' => 'This email is already in use within the system.'
            );
            echo json_encode($response);
            return;
        }
    }

    // Directory to store uploaded files
    $upload_dir = '../data/proofofparticipation/';

    // Handle file upload if a new file is provided
    $prove_letter_filename = $current_prove_letter_filename;
    if ($participationproof && $participationproof['error'] == UPLOAD_ERR_OK) {
        $prove_letter_tmp = $participationproof['tmp_name'];
        $prove_letter_original_name = basename($participationproof['name']);
        $prove_letter_extension = pathinfo($prove_letter_original_name, PATHINFO_EXTENSION);
        $prove_letter_filename = $ic . '_proof.' . $prove_letter_extension; // Rename file as ic_number_proof.extension
        $prove_letter_destination = $upload_dir . $prove_letter_filename;

        // Overwrite the old file with the new file
        if (!move_uploaded_file($prove_letter_tmp, $prove_letter_destination)) {
            // Handle upload error if necessary
            $response = array(
                'status' => 'error',
                'message' => 'Failed to upload proof of participation file.'
            );
            echo json_encode($response);
            return;
        }
    }

    // Update the registration table
    $sql_registration = "UPDATE registration SET 
                         name = '$fullname', 
                         gender = '$gender', 
                         race = '$race', 
                         religion = '$religion', 
                         birthdate = '$birthdate', 
                         birthplace = '$birthplace', 
                         address = '$homeaddress', 
                         email = '$email', 
                         phone_number = '$phonenumber', 
                         prove_letter = '$prove_letter_filename'
                         WHERE ic_number = '$current_ic'";

    if ($conn->query($sql_registration) === TRUE) {
        // Update the member table
        $sql_member = "UPDATE member SET 
                       username = '$fullname', 
                       status = '$userstatus'
                       WHERE ic_number = '$current_ic'";

        if ($conn->query($sql_member) === TRUE) {
            // Success response
            $response = array(
                'status' => 'success',
                'message' => 'Member information updated successfully!'
            );
            echo json_encode($response);
        } else {
            // Error response for member table update
            $response = array(
                'status' => 'error',
                'message' => 'Error updating member table: ' . $conn->error
            );
            echo json_encode($response);
        }
    } else {
        // Error response for registration table update
        $response = array(
            'status' => 'error',
            'message' => 'Error updating registration table: ' . $conn->error
        );
        echo json_encode($response);
    }
}



function AdminDeleteMember($icNumber) {
    global $conn; // Assuming $conn is your database connection object

    // Perform deletion based on IC number
    $sql_delete_registration = "DELETE FROM registration WHERE ic_number = ?";
    $sql_delete_member = "DELETE FROM member WHERE ic_number = ?";

    try {
        // Disable foreign key checks
        $conn->query('SET FOREIGN_KEY_CHECKS = 0');

        // Use prepared statements to prevent SQL injection
        $stmt_registration = $conn->prepare($sql_delete_registration);
        $stmt_registration->bind_param('s', $icNumber);
        
        $stmt_member = $conn->prepare($sql_delete_member);
        $stmt_member->bind_param('s', $icNumber);

        // Execute SQL queries
        $success = true;
        $conn->autocommit(false); // Start a transaction
        $stmt_registration->execute();
        if ($stmt_registration->errno) {
            $success = false;
            error_log('Error deleting from registration table: ' . $stmt_registration->error);
        }
        $stmt_member->execute();
        if ($stmt_member->errno) {
            $success = false;
            error_log('Error deleting from member table: ' . $stmt_member->error);
        }

        if ($success) {
            $conn->commit(); // Commit the transaction
            $response = array(
                'status' => 'success',
                'message' => 'Member deleted successfully!'
            );
        } else {
            $conn->rollback(); // Rollback the transaction
            $response = array(
                'status' => 'error',
                'message' => 'Error deleting member'
            );
        }
    } catch (Exception $e) {
        $conn->rollback(); // Rollback the transaction on exception
        $response = array(
            'status' => 'error',
            'message' => 'Exception: ' . $e->getMessage()
        );
    } finally {
        // Re-enable foreign key checks
        $conn->query('SET FOREIGN_KEY_CHECKS = 1');

        // Close statements and restore autocommit mode
        $stmt_registration->close();
        $stmt_member->close();
        $conn->autocommit(true); // Restore autocommit mode
    }

    echo json_encode($response);
}


function AdminGrabMemberMeritPrograms($ic_number) {
    global $conn;

    // Step 1: Retrieve user_id and total_merit from the member table
    $stmt = $conn->prepare("SELECT user_id, total_merit FROM member WHERE ic_number = ?");
    $stmt->bind_param("s", $ic_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();

    if (!$member) {
        return null; // Return null if member not found
    }

    $user_id = $member['user_id'];
    $total_merit = $member['total_merit'];

    // Step 2: Retrieve allocated merits for the user
    $stmt = $conn->prepare("SELECT * FROM allocated_merits WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $allocated_merits = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $meritPrograms = [];
    
    foreach ($allocated_merits as $allocated_merit) {
        $event_id = $allocated_merit['event_id'];

        // Step 3: Retrieve event name from abmevent table
        $stmt = $conn->prepare("SELECT event_name FROM abmevent WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        $stmt->close();

        if (!$event) {
            continue; // Skip if event not found
        }

        // Step 4: Retrieve merit information from merit table
        $stmt = $conn->prepare("SELECT merit_id, merit_point FROM merit WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $merit = $result->fetch_assoc();
        $stmt->close();

        if (!$merit) {
            continue; // Skip if merit not found
        }

        // Combine all data into a single array for this merit program
        $meritPrograms[] = [
            'event_name' => $event['event_name'],
            'merit_id' => $merit['merit_id'],
            'merit_point' => $merit['merit_point'],
            'allocation_date' => $allocated_merit['allocation_date'],
        ];
    }

    return [
        'total_merit' => $total_merit,
        'meritPrograms' => $meritPrograms
    ];
}


function exportMemberListToCSV($members) {
    // Define CSV file name
    $fileName = 'member_list_' . date('Ymd') . '.csv';
    
    // Set headers to force download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
    // Open output stream
    $output = fopen('php://output', 'w');
    
    // Output CSV headers
    fputcsv($output, ['#', 'Name', 'Email', 'Member Status']);
    
    // Counter for numbering rows
    $counter = 1;
    
    // Output each member's data
    foreach ($members as $member) {
        $rowData = [
            $counter++,
            $member['username'],
            $member['email'],
            'Member' // Placeholder for member status
        ];
        fputcsv($output, $rowData);
    }
    
    // Close output stream
    fclose($output);
    exit; // Terminate script after download
}


function GrabParticipationProof($filename) {
    $proof_folder = '../data/proofofparticipation/';
    $full_path = $proof_folder . $filename;

    if (file_exists($full_path)) {
        return 'exist';
    } else {
        return 'not_exist';
    }
}



// activity records


function grabEventRecords()
{
    global $conn;

    $records = array();

    // Query to fetch records from abmevent table where event_status is 'running', 'drafted', or 'ended'
    $sql = "SELECT * FROM abmevent WHERE event_status IN ('running', 'drafted', 'ended')";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch associative array of each row
        while ($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    }

    return $records;
}



function getEventAttendees($event_id) {
    global $conn;

    // Ensure $conn is initialized
    if (!$conn) {
        throw new Exception('Database connection not established.');
    }

    // Array to store results
    $results = [];

    // Query to get event details
    $eventQuery = "
        SELECT event_name, event_session, event_date
        FROM abmevent
        WHERE event_id = ?
    ";
    

    // Prepare and execute the event query
    $stmt = $conn->prepare($eventQuery);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $eventDetails = $stmt->get_result()->fetch_assoc();

    // Query to get member details
    $memberQuery = "
    SELECT m.user_id, m.username AS Name, m.ic_number, r.phone_number, m.status AS Member_Status 
    FROM joinevent j
    JOIN member m ON j.user_id = m.user_id
    JOIN registration r ON m.ic_number = r.ic_number
    WHERE j.event_id = ? AND j.user_id IS NOT NULL
";

    // Prepare and execute the member query
    $stmt = $conn->prepare($memberQuery);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Query to get nonmember details
    $nonmemberQuery = "
        SELECT n.name AS Name, n.phone_number, 'public' AS Member_Status 
        FROM joinevent j
        JOIN nonmember n ON j.nonMember_event_reg_id = n.nonMember_event_reg_id
        WHERE j.event_id = ? AND j.nonMember_event_reg_id IS NOT NULL
    ";

    // Prepare and execute the nonmember query
    $stmt = $conn->prepare($nonmemberQuery);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $nonmembers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Combine member and nonmember results
    $attendees = array_merge($members, $nonmembers);

    // Combine event details and attendees in an associative array
    $results['event_details'] = $eventDetails;
    $results['attendees'] = $attendees;

    return $results;
}

// Function to grab event information
function grabEventInfo($event_id)
{
    global $conn;

    $sql = "SELECT * FROM abmevent WHERE event_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function AdminAddNewEvent($event_name, $banner, $event_description, $total_participation, $event_category, $event_date, $event_session, $event_start_time, $event_end_time, $event_location, $event_price, $event_status)
{
    global $conn;

    // Define the log file path
    $logFile = __DIR__ . '/admin-functions-error-log.txt';

    // Function to log errors
    function logError($message, $logFile)
    {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    // Handle file upload
    $target_dir = "../img/banner/";
    $target_file = $target_dir . 'banner_' . basename($banner["name"]);

    if (move_uploaded_file($banner["tmp_name"], $target_file)) {
        // Set event_price to NULL if it's a Free Event
        if ($event_price === null) {
            $event_price_sql = "NULL";
        } else {
            $event_price_sql = "'" . $conn->real_escape_string($event_price) . "'";
        }

        // Insert new event into events table
        $sql_event = "INSERT INTO abmevent (event_name, banner, event_description, total_participation, event_category, event_date, event_session, event_start_time, event_end_time, event_location, event_price, event_status)
                      VALUES ('$event_name', '$target_file', '$event_description', '$total_participation', '$event_category', '$event_date', '$event_session', '$event_start_time', '$event_end_time', '$event_location', $event_price_sql, '$event_status')";

        if ($conn->query($sql_event) === true) {
            // Success response
            $response = array(
                'status' => 'success',
                'message' => 'New event added successfully!',
            );
        } else {
            // Log the error
            logError("Error inserting into abmevent table: " . $conn->error, $logFile);
            // Error response for events table insertion
            $response = array(
                'status' => 'error',
                'message' => 'Error inserting into abmevent table: ' . $conn->error,
            );
        }
    } else {
        logError("Sorry, there was an error uploading your file.", $logFile);
        $response = array(
            'status' => 'error',
            'message' => 'Sorry, there was an error uploading your file.',
        );
    }

    // Output JSON response
    echo json_encode($response);
}




// Update Event Information
function AdminUpdateEvent($event_id, $event_name, $banner, $event_description, $total_participation, $event_category, $event_date, $event_session, $event_start_time, $event_end_time, $event_location, $event_price, $event_status)
{
    global $conn;

    // Define the log file path
    $logFile = __DIR__ . '/admin-functions-error-log.txt';

    // Function to log errors
    function logError1($message, $logFile)
    {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    $target_dir = "../img/banner/";
    $uploadOk = 1;

    // Handle file upload and replace the existing file
    if ($banner["error"] == UPLOAD_ERR_OK) {
        $target_file = $target_dir . 'banner_' . basename($banner["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($banner["tmp_name"]);
        if ($check === false) {
            logError1("File is not an image.", $logFile);
            $uploadOk = 0;
        }

        // Check file size
        if ($banner["size"] > 5000000) {
            logError1("Sorry, your file is too large.", $logFile);
            $uploadOk = 0;
            $response = array(
                'status' => 'error',
                'message' => 'The uploaded file exceeds the maximum allowed size of 5MB.'
            );
            echo json_encode($response);
            return;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            logError1("Sorry, only JPG, JPEG, PNG & GIF files are allowed.", $logFile);
            $uploadOk = 0;
            $response = array(
                'status' => 'error',
                'message' => 'Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.'
            );
            echo json_encode($response);
            return;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $target_file = $target_dir . 'banner_' . time() . '_' . basename($banner["name"]);
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $response = array(
                'status' => 'error',
                'message' => 'File upload failed. Please check the error log for details.'
            );
            echo json_encode($response);
            return;
        } else {
            if (!move_uploaded_file($banner["tmp_name"], $target_file)) {
                logError1("Sorry, there was an error uploading your file.", $logFile);
                $response = array(
                    'status' => 'error',
                    'message' => 'Sorry, there was an error uploading your file.'
                );
                echo json_encode($response);
                return;
            }
        }
    } else {
        // If no new file is uploaded, retrieve the existing file path from the database
        $sql_select = "SELECT banner FROM abmevent WHERE event_id = '$event_id'";
        $result = $conn->query($sql_select);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target_file = $row['banner'];
        } else {
            logError1("Error retrieving existing banner path: " . $conn->error, $logFile);
            $response = array(
                'status' => 'error',
                'message' => 'Error retrieving existing banner path.'
            );
            echo json_encode($response);
            return;
        }
    }

    // Update event information in events table
    $sql_event = "UPDATE abmevent SET
                  event_name = '$event_name',
                  banner = '$target_file',
                  event_description = '$event_description',
                  total_participation = '$total_participation',
                  event_category = '$event_category',
                  event_date = '$event_date',
                  event_session = '$event_session',
                  event_start_time = '$event_start_time',
                  event_end_time = '$event_end_time',
                  event_location = '$event_location',
                  event_price = '$event_price',
                  event_status = '$event_status'
                  WHERE event_id = '$event_id'";

    if ($conn->query($sql_event) === true) {
        // Success response
        $response = array(
            'status' => 'success',
            'message' => 'Event information updated successfully!'
        );
    } else {
        // Log the error
        logError1("Error updating abmevent table: " . $conn->error, $logFile);
        // Error response for events table update
        $response = array(
            'status' => 'error',
            'message' => 'Error updating abmevent table: ' . $conn->error
        );
    }

    // Output JSON response
    echo json_encode($response);
}




// Function to delete an event by changing the event_status existing value to "deleted"
function AdminDeleteEvent($event_id)
{
    global $conn;

    // Define the log file path
    $logFile = __DIR__ . '/admin-functions-error-log.txt';

    // Update event status to 'deleted'
    $sql = "UPDATE abmevent SET event_status = 'deleted' WHERE event_id = '$event_id'";

    if ($conn->query($sql) === true) {
        // Success response
        $response = array(
            'status' => 'success',
            'message' => 'Event deleted successfully!',
        );
    } else {
        // Log the error
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Error deleting event: " . $conn->error . "\n", FILE_APPEND);
        
        // Error response
        $response = array(
            'status' => 'error',
            'message' => 'Error deleting event: ' . $conn->error,
        );
    }

    // Output JSON response
    echo json_encode($response);
}


function grabMeritRecords()
{
    global $conn;

    $records = array();

    // Query to fetch records from merit table
    $sql = "SELECT abmevent.*, merit.merit_point AS merit_point, merit.merit_id, merit.person_in_charge_name AS person_in_charge_name, merit.allocation_date, merit.person_in_charge_phone_number
            FROM abmevent AS abmevent
            JOIN merit AS merit ON abmevent.event_id = merit.event_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch associative array of each row
        while ($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    }

    return $records;
}


// Function to grab merit information based on merit_id
function grabMeritInfo($merit_id)
{
    global $conn;

    $sql = "SELECT m.person_in_charge_name, m.person_in_charge_phone_number, m.merit_point, m.allocation_date, e.event_name 
            FROM merit m 
            JOIN abmevent e ON m.event_id = e.event_id 
            WHERE m.merit_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $merit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}



// admin
// Function to add a new merit
function AdminAddNewMerit($activity, $merit_points, $pic, $activity_date, $phone_number)
{
    global $conn;

    // Check if a similar merit already exists
    $sql_check_merit_existence = "SELECT * FROM merit WHERE event_id = '$activity'";
    $result_check_merit_existence = $conn->query($sql_check_merit_existence);

    if ($result_check_merit_existence === false) {
        // Log the error
        error_log("Error executing query: " . $conn->error);
        $response = array(
            'status' => 'error',
            'message' => 'Database error. Please try again later.',
        );
        echo json_encode($response);
        return;
    }

    if ($result_check_merit_existence->num_rows > 0) {
        // Merit already exists
        $response = array(
            'status' => 'error',
            'message' => 'The merit for the selected activity already exists.',
        );
        echo json_encode($response);
        return;
    }

    // Insert new merit into merit table
    $sql_merit = "INSERT INTO merit (event_id, merit_point, person_in_charge_name, person_in_charge_phone_number, allocation_date)
                  VALUES ('$activity', '$merit_points', '$pic', '$phone_number', '$activity_date')";

    if ($conn->query($sql_merit) === true) {
        // Success response
        $response = array(
            'status' => 'success',
            'message' => 'New merit added successfully!',
        );
    } else {
        // Log the error
        error_log("Error inserting into merit table: " . $conn->error);
        // Error response for merit table insertion
        $response = array(
            'status' => 'error',
            'message' => 'Error inserting into merit table: ' . $conn->error,
        );
    }

    // Output JSON response
    echo json_encode($response);
}


// Function to update merit information $merit_points, $pic, $activity_date, $phone_number ONLY  - no need log error
function AdminUpdateMerit($event_id, $merit_points, $pic, $activity_date, $phone_number)
{
    global $conn;

    $sql = "UPDATE merit SET merit_point = ?, person_in_charge_name = ?, allocation_date = ?, person_in_charge_phone_number = ? WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $merit_points, $pic, $activity_date, $phone_number, $event_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


function AdminDeleteMerit($merit_id)
{
    global $conn;

    // Define the log file path
    $logFile = __DIR__ . '/admin-functions-error-log.txt';

    // Delete merit record from merit table
    $sql = "DELETE FROM merit WHERE merit_id = '$merit_id'";

    if ($conn->query($sql) === true) {
        // Success response
        $response = array(
            'status' => 'success',
            'message' => 'Merit deleted successfully!',

        );
    } else {
        // Log the error
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Error deleting merit: " . $conn->error . "\n", FILE_APPEND);
        
        // Error response
        $response = array(
            'status' => 'error',
            'message' => 'Error deleting merit: ' . $conn->error,
        );
    }

    // Output JSON response
    echo json_encode($response);
}


function grabUserAccounts()
{
    global $conn;

    $records = array();

    $sql = "SELECT m.user_id, m.username, m.status, m.role, r.email, m.ic_number
            FROM member m
            INNER JOIN registration r ON m.ic_number = r.ic_number
            UNION
            SELECT a.user_id, a.username, a.status, a.role, r.email, a.ic_number
            FROM admin a
            INNER JOIN registration r ON a.ic_number = r.ic_number";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Fetch associative array of each row
        while ($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    }

    return $records;
}

function getUserInfo($user_id, $role) {
    global $conn; // Use the global connection

    // Sanitize input
    $user_id = $conn->real_escape_string($user_id);

    // Initialize the SQL query variable
    $sql = "";

    // Check the role and construct the SQL query accordingly
    if ($role === 'member') {
        // Query to get member information
        $sql = "SELECT m.*, r.*
                FROM member m
                JOIN registration r ON m.ic_number = r.ic_number
                WHERE m.user_id='$user_id'";
    } elseif ($role === 'admin') {
        // Query to get admin information
        $sql = "SELECT a.*, r.*
                FROM admin a
                JOIN registration r ON a.ic_number = r.ic_number
                WHERE a.user_id='$user_id'";
    } else {
        return null; // Return null if the role is not recognized
    }

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user information
        $userInfo = $result->fetch_assoc();
        return $userInfo; // Directly return user information
    } else {
        return null; // Return null if user not found
    }
}




function grabUserInfo($ic_number)
{
    global $conn; // Access the $conn variable

    // SQL query to select user info from both member and admin tables
    $sql = "SELECT r.email, r.ic_number,
                COALESCE(m.username, a.username) AS username,
                COALESCE(m.password, a.password) AS password,
                COALESCE(m.role, a.role) AS role,
                COALESCE(m.status, a.status) AS status,
                CASE WHEN m.ic_number IS NOT NULL THEN 'member' ELSE 'admin' END AS user_type
                FROM registration AS r
                LEFT JOIN member AS m ON r.ic_number = m.ic_number
                LEFT JOIN admin AS a ON r.ic_number = a.ic_number
                WHERE r.ic_number = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ic_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}



function AdminUpdateUser($username, $password, $role, $status, $ic_number, $email, $current_email)
{
    global $conn;

    // Initialize response array
    $response = array();

    // Check if the email already exists in the registration table with approved status but is not the current member's email
    if ($email !== $current_email) {
        $sql_check_email_existence = "SELECT * FROM registration WHERE email = '$email'";
        $result_check_email_existence = $conn->query($sql_check_email_existence);

        if ($result_check_email_existence->num_rows > 0) {
            // Another member already exists with this email address
            $response = array(
                'status' => 'error',
                'message' => 'Another member is already registered with this email address. Please use a different email address.'
            );
            echo json_encode($response);
            return;
        }
    }

    // Update the registration table
    $sql_registration = "UPDATE registration SET 
                         email = '$email'
                         WHERE ic_number = '$ic_number'";

    // Execute registration table update
    if ($conn->query($sql_registration) === TRUE) {
        // Update the appropriate user table based on role
        if ($role == 'User') {
            $sql_update_member = "UPDATE member SET 
                               username = '$username',
                               password = '$password',
                               status = '$status'
                               WHERE ic_number = '$ic_number'";

            if ($conn->query($sql_update_member) === TRUE) {
                // Success response for member table update
                $response = array(
                    'status' => 'success',
                    'message' => 'Member information updated successfully!'
                );
                echo json_encode($response);
            } else {
                // Error response for member table update
                $response = array(
                    'status' => 'error',
                    'message' => 'Error updating member table: ' . $conn->error
                );
                echo json_encode($response);
            }
        } elseif ($role == 'Admin') {
            $sql_update_admin = "UPDATE admin SET 
                                username = '$username',
                                password = '$password',
                                status = '$status'
                                WHERE ic_number = '$ic_number'";

            if ($conn->query($sql_update_admin) === TRUE) {
                // Success response for admin table update
                $response = array(
                    'status' => 'success',
                    'message' => 'Admin information updated successfully!'
                );
                echo json_encode($response);
            } else {
                // Error response for admin table update
                $response = array(
                    'status' => 'error',
                    'message' => 'Error updating admin table: ' . $conn->error
                );
                echo json_encode($response);
            }
        }
    } else {
        // Error response for user table update
        $response = array(
            'status' => 'error',
            'message' => 'Error updating registration table: ' . $conn->error
        );
        echo json_encode($response);
    }
}


function grabPaymentCollections()
{
    global $conn; // Access the $conn variable from server.php

    $records = array();

    // Query to fetch all records from the payment table
    $sql = "SELECT * FROM payment";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch associative array of each row
        while ($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    }

    return $records;
}


function grabFeeInfo($payment_id)
{
    global $conn; // Access the $conn variable from server.php

    // Query to fetch the required records from the payment and abmevent tables
    $sql = "SELECT  p.payment_id, p.payment_name, p.payment_fee, p.payment_date,
                    ae.event_name, ae.event_id
            FROM payment p
            LEFT JOIN abmevent ae ON p.payment_name = ae.event_name
            WHERE p.payment_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}




function grabFeeCollectionReports($payment_id)
{
    global $conn; // Access the $conn variable from server.php

    $records = array();

    // Query to fetch all records from the payment table
    $sql = "SELECT pr.receipt_id, pr.receipt_date, pr.receipt_time, 
                p.payment_id,
                r.name 
            FROM paymentreceipt pr
            JOIN payment p ON pr.payment_id = p.payment_id
            JOIN registration r ON pr.ic_number = r.ic_number
            WHERE p.payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    } else {
        return null;
    }

    return $records;
}




function allocateMeritMember($userId, $eventId) {
    global $conn;

    // Step 1: Check if merit already allocated to user for the event and retrieve event_name from abmevent table
    $stmtCheck = $conn->prepare("
        SELECT COUNT(*), (SELECT event_name FROM abmevent WHERE event_id = ?) AS event_name 
        FROM allocated_merits 
        WHERE user_id = ? AND event_id = ?");
    $stmtCheck->bind_param("iii", $eventId, $userId, $eventId);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count, $eventName);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        $error_message = 'Error: Merit already allocated for user ' . $userId . ' in event ' . $eventName;
        recordError($error_message);
        return ['message' => $error_message, 'event_name' => $eventName];
    }

    // Step 2: Retrieve `merit_id` using `event_id` from `merit` table
    $stmt1 = $conn->prepare("SELECT merit_id FROM merit WHERE event_id = ?");
    $stmt1->bind_param("i", $eventId);
    $stmt1->execute();
    $stmt1->bind_result($meritId);
    $stmt1->fetch();
    $stmt1->close();

    if (!$meritId) {
        $error_message = 'Error: No merit record found for the event ' . $eventName;
        recordError($error_message);
        return ['message' => $error_message, 'event_name' => $eventName];
    }

    // Step 3: Retrieve `merit_point` from `merit` table using `merit_id`
    $stmt2 = $conn->prepare("SELECT merit_point FROM merit WHERE merit_id = ?");
    $stmt2->bind_param("i", $meritId);
    $stmt2->execute();
    $stmt2->bind_result($meritPoint);
    $stmt2->fetch();
    $stmt2->close();

    if (!$meritPoint) {
        $error_message = 'Error: No merit point found for the merit ' . $meritId;
        recordError($error_message);
        return ['message' => $error_message, 'event_name' => $eventName];
    }

    // Step 4: Update `total_merit` for the member in `member` table
    $stmt3 = $conn->prepare("UPDATE member SET total_merit = total_merit + ? WHERE user_id = ?");
    $stmt3->bind_param("ii", $meritPoint, $userId);
    if ($stmt3->execute()) {
        $stmt3->close();

        // Record the allocation in allocated_merits table
        $stmtRecord = $conn->prepare("INSERT INTO allocated_merits (user_id, event_id, merit_id, merit_point) VALUES (?, ?, ?, ?)");
        $stmtRecord->bind_param("iiii", $userId, $eventId, $meritId, $meritPoint);
        $stmtRecord->execute();
        $stmtRecord->close();

        $success_message = 'Success: Merit allocated for user ' . $userId . ' in event ' . $eventName;
        recordSuccess($success_message);

        return ['message' => 'Merit allocated successfully', 'event_name' => $eventName];
    } else {
        $stmt3->close();

        $error_message = 'Error updating total_merit for user ' . $userId;
        recordError($error_message);
        return ['message' => $error_message, 'event_name' => $eventName];
    }
}



function recordSuccess($message) {
    $file = fopen('failedallocatemerit.txt', 'a'); // Open or create file for appending
    fwrite($file, date('Y-m-d H:i:s') . ': ' . $message . "\n"); // Write success message with timestamp
    fclose($file); // Close the file
}

function recordError($message) {
    $file = fopen('failedallocatemerit.txt', 'a'); // Open or create file for appending
    fwrite($file, date('Y-m-d H:i:s') . ': ' . $message . "\n"); // Write error message with timestamp
    fclose($file); // Close the file
}


function CheckIfAllocatedMember($userId, $eventId) {
    global $conn; // Use global connection object, or create a new connection if needed

    // Fetch the allocation status
    $query = "SELECT COUNT(*) as count FROM allocated_merits WHERE user_id = ? AND event_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $userId, $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        // Fetch the event name
        $eventQuery = "SELECT event_name FROM abmevent WHERE event_id = ?";
        if ($stmt = $conn->prepare($eventQuery)) {
            $stmt->bind_param("i", $eventId);
            $stmt->execute();
            $result = $stmt->get_result();
            $eventRow = $result->fetch_assoc();
            $stmt->close();

            $eventName = $eventRow['event_name'];

            return ['allocated' => $row['count'] > 0, 'event_name' => $eventName];
        } else {
            return ['allocated' => false, 'event_name' => '']; // Or handle error appropriately
        }
    } else {
        return ['allocated' => false, 'event_name' => '']; // Or handle error appropriately
    }
}

function grabFeePayments()
{
    global $conn; // Access the $conn variable from server.php

    $records = array();

    // Query to fetch all records from the payment table
    $sql = "SELECT * FROM abmevent WHERE event_price IS NOT NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch associative array of each row
        while ($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    }

    return $records;
}


function grabFeePaymentInfo($event_id)
{
    global $conn; // Access the $conn variable from server.php

    // Query to fetch all records from the payment table
    $sql = "SELECT  *
            FROM abmevent
            WHERE event_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


function grabFeePaymentReports($event_id)
{
    global $conn; // Access the $conn variable from server.php

    $records = array();

    // Query to fetch all records from the payment table
    $sql = "SELECT m.user_id, m.username, p.payment_id, p.payment_time, p.payment_date, p.payment_status, p.event_id
            FROM payment p
            INNER JOIN joinevent j ON p.user_id = j.user_id AND p.event_id = j.event_id
            INNER JOIN member m ON p.user_id = m.user_id
            WHERE p.event_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $records[] = $row; // Append each row to $records array
        }
    } else {
        return null;
    }

    return $records;
}

// Main entry point
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if action is set and handle accordingly
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'AANM':
                // Retrieve form data for adding new member
                $fullname = $_POST['fullname'];
                $ic = $_POST['ic'];
                $gender = $_POST['gender'];
                $race = $_POST['race'];
                $age = $_POST['age'];
                $religion = $_POST['religion'];
                $birthdate = $_POST['birthdate'];
                $birthplace = $_POST['birthplace'];
                $homeaddress = $_POST['homeaddress'];
                $email = $_POST['email'];
                $phonenumber = $_POST['phonenumber'];
                $cohortyear = $_POST['cohortyear'];
                $kemwawasan = $_POST['kemwawasan'];
                $userstatus = $_POST['userstatus'];

                // Placeholder for handling file upload (not shown)
                $participationproof = '';

                // Call function to add new members
                AdminAddNewMembers($fullname, $ic, $gender, $race, $age, $religion, $birthdate, $birthplace, $homeaddress, $email, $phonenumber, $cohortyear, $kemwawasan, $participationproof, $userstatus);
                break;

            case 'exportMemberList':
                // Call function to grab member records
                $members = grabMemberRecords();

                // Call function to export member list to CSV
                exportMemberListToCSV($members);
                break;

            case 'GPP':
                // Handle 'Grab Participation Proof' action
                if (isset($_POST['filename'])) {
                    $filename = $_POST['filename'];
                    $result = GrabParticipationProof($filename);
                    $response = array(
                        'status' => $result,
                    );
                    echo json_encode($response);
                } else {
                    echo json_encode(array(
                        'status' => 'error',
                        'message' => 'Filename parameter not provided.',
                    ));
                }
                break;

            case 'AUM':
                // Retrieve form data for updating member
                $fullname = $_POST['fullname'];
                $ic = $_POST['ic'];
                $gender = $_POST['gender'];
                $race = $_POST['race'];
                $religion = $_POST['religion'];
                $birthdate = $_POST['birthdate'];
                $birthplace = $_POST['birthplace'];
                $homeaddress = $_POST['homeaddress'];
                $email = $_POST['email'];
                $phonenumber = $_POST['phonenumber'];
                $userstatus = $_POST['userstatus'];

                // Retrieve current member info
                $current_ic = $_POST['current_ic'];
                $current_email = $_POST['current_email'];
                $current_prove_letter_filename = isset($_POST['current_prove_letter_filename']) ? $_POST['current_prove_letter_filename'] : '';

                // Handle file upload for participation proof (not shown)
                $participationproof = isset($_FILES['participationproof']) ? $_FILES['participationproof'] : null;

                // Call function to update member information
                AdminUpdateMember($fullname, $ic, $gender, $race, $religion, $birthdate, $birthplace, $homeaddress, $email, $phonenumber, $participationproof, $userstatus, $current_ic, $current_email, $current_prove_letter_filename);
                break;

            case 'ADM':
                // Handle 'Admin Delete Member' action
                if (isset($_POST['ic_number'])) {
                    $icNumber = $_POST['ic_number'];
                    AdminDeleteMember($icNumber);
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'IC number not provided.',
                    );
                    echo json_encode($response);
                }
                break;

            case 'AAM':

                // Retrieve form data for adding new merit
                $activity = $_POST['activity'];
                $merit_points = $_POST['merit_points'];
                $pic = $_POST['pic'];
                $activity_date = $_POST['activity_date'];
                $phone_number = $_POST['phone_number'];

                // Call function to add new merit
                AdminAddNewMerit($activity, $merit_points, $pic, $activity_date, $phone_number);
                break;

            case 'AUME':
                // Retrieve form data for updating merit
                $event_id = $_POST['event_id'];
                $merit_points = $_POST['merit_points'];
                $pic = $_POST['pic'];
                $activity_date = $_POST['activity_date'];
                $phone_number = $_POST['phone_number'];

                // Call function to update merit information
                if (AdminUpdateMerit($event_id, $merit_points, $pic, $activity_date, $phone_number)) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update merit information.']);
                }
                break;

            case 'ADEM':
                // Retrieve merit ID for deletion
                $merit_id = $_POST['merit_id'];
                // Call function to delete merit
                AdminDeleteMerit($merit_id);
                break;


            case 'AAE':

                // Retrieve form data for adding new event
                $event_name = $_POST['event_name'];
                $event_description = $_POST['event_description'];
                $total_participation = $_POST['total_participation'];
                $event_category = $_POST['event_category'];
                $event_date = $_POST['event_date'];
                $event_session = $_POST['event_session'];
                $event_start_time = $_POST['start_time'];
                $event_end_time = $_POST['end_time'];
                $event_location = $_POST['event_location'];
                $event_price = isset($_POST['event_price']) ? $_POST['event_price'] : null; // Set event_price to null if not set
                $event_status = $_POST['event_status'];

                // Call function to add new event
                AdminAddNewEvent($event_name, $_FILES['banner'], $event_description, $total_participation, $event_category, $event_date, $event_session, $event_start_time, $event_end_time, $event_location, $event_price, $event_status);
                break;

                case 'AUE':
                    // Retrieve form data for updating event
                    $event_id = $_POST['event_id'];
                    $event_name = $_POST['event_name'];
                    $event_description = $_POST['event_description'];
                    $event_category = $_POST['event_category'];
                    $event_status = $_POST['event_status'];
                    $event_session = $_POST['event_session'];
                    $event_location = $_POST['event_location'];
                    $event_start_time = $_POST['event_start_time'];
                    $event_end_time = $_POST['event_end_time'];
                    $event_date = $_POST['event_date'];
                    $event_price = $_POST['event_price'];
                    $banner_file = $_FILES['banner'];
                    $total_participation = $_POST['total_participation'];
                    // Call function to update event details
                    AdminUpdateEvent($event_id, $event_name, $banner_file, $event_description, $total_participation, $event_category, $event_date, $event_session, $event_start_time, $event_end_time, $event_location, $event_price, $event_status);
                    break;

                case 'ADE':
                    // Retrieve event ID for deletion
                    $event_id = $_POST['event_id'];
                    // Call function to delete event
                    AdminDeleteEvent($event_id);
                    break;

                    case 'AUU':
                        // Retrieve form data for updating user
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        $role = $_POST['role'];
                        $status = $_POST['status'];
                        $ic_number = $_POST['ic_number'];
                        $email = $_POST['email'];
                        $current_email = $_POST['current_email'];
        
                        // Call function to update user information
                        AdminUpdateUser($username, $password, $role, $status, $ic_number, $email, $current_email);
                        break;


                        case 'allocateMerit':
                            // Main POST Request Handler for Allocating Merit
                            if (isset($_POST['attendees'])) {
                                $attendees = $_POST['attendees'];
                                    
                                $responses = [];
                                    foreach ($attendees as $attendee) {
                                        if (isset($attendee['user_id']) && isset($attendee['event_id'])) {
                                            $userId = $attendee['user_id'];
                                            $eventId = $attendee['event_id'];
                                            $responses[] = allocateMeritMember($userId, $eventId);
                                        }
                                    }
                                    echo json_encode($responses);
                                } else {
                                    // Respond with a JSON-encoded error message for missing parameters
                                    echo json_encode(['message' => 'Invalid request method or missing parameters']);
                                }
                        break;
        
                        case 'checkAllocation':
                            // Check if user is already allocated merits for the event
                            if (isset($_POST['user_id']) && isset($_POST['event_id'])) {
                                $userId = $_POST['user_id'];
                                $eventId = $_POST['event_id'];
                                $result = CheckIfAllocatedMember($userId, $eventId);
                                echo json_encode($result);
                            } else {
                                echo json_encode(['error' => 'Missing user_id or event_id']);
                            }
                            break;


            default:
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid action: ' . $action,
                );
                echo json_encode($response);
                break;
        }
    } else {

    }
}





?>
