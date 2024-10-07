<?php
include 'server.php';
// Ensure you set the timezone at the beginning of your script
date_default_timezone_set('Asia/Kuala_Lumpur');

function getActiveEvents($conn) {
    // SQL query to select running events that have not been joined
    $sql = "SELECT *, 
                DATEDIFF(e.event_date, CURDATE()) AS days_before_event
            FROM abmevent e
            WHERE e.event_status = 'running' 
            AND e.event_category = 'public'
            AND e.event_date >= CURDATE()
            ORDER BY days_before_event ASC
            ";
            
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Fetch events into an array
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    
    // Close statement
    $stmt->close();
    
    return $events;
}



function countActiveEvents($conn) {
    $sql = "SELECT COUNT(*) as total FROM abmevent WHERE event_status = 'running'";
    $result = $conn->query($sql);
    $count = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['total'];
    }

    return $count;
}

function getEventById($conn, $event_id) {
    $stmt = $conn->prepare("SELECT * FROM abmevent WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}





function reserveSlot($conn, $event_id) {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update events table to reserve the slot
        $sql = "UPDATE events SET slot_reserved = 1 WHERE event_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $event_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to reserve slot for event");
        }

        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaction on failure
        $conn->rollback();
        error_log("Slot reservation failed: " . $e->getMessage());
        return false;
    }
}

function releaseSlot($conn, $event_id) {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update events table to release the reserved slot
        $sql = "UPDATE events SET slot_reserved = 0 WHERE event_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $event_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to release slot for event");
        }

        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaction on failure
        $conn->rollback();
        error_log("Slot release failed: " . $e->getMessage());
        return false;
    }
}


function CheckTransactionExists($conn, $transaction_id) {
    $count = 0; // Initialize count to zero
    
    $sql = "SELECT COUNT(*) AS count FROM payment WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $transaction_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}


// Function to check if a user has already registered for an event with the same email
function isNonMemberRegistered($conn, $email, $event_id) {
    // Initialize nonMember_event_reg_id to null initially
    $nonMember_event_reg_id = null;

    // First, check if the email exists in the nonmember table
    $stmt = $conn->prepare("SELECT nonMember_event_reg_id FROM nonmember WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Email does not exist in nonmember table
        $stmt->close();
        return false;
    }

    // Get the nonMember_event_reg_id
    $stmt->bind_result($nonMember_event_reg_id);
    $stmt->fetch();
    $stmt->close();

    // Check if the nonMember_event_reg_id exists in the joinevent table for the given event_id
    $stmt = $conn->prepare("SELECT event_reg_id FROM joinevent WHERE nonMember_event_reg_id = ? AND event_id = ?");
    $stmt->bind_param('ii', $nonMember_event_reg_id, $event_id);
    $stmt->execute();
    $stmt->store_result();

    // If the count is greater than 0, the user is already registered
    $isRegistered = $stmt->num_rows > 0;

    // Print the result to a text file
    $file = fopen('isregistered.txt', 'a');
    if ($file) {
        $dateTime = date('Y-m-d H:i:s');
        $logMessage = "Checked on $dateTime - Email: $email, Event ID: $event_id, Is Registered: " . ($isRegistered ? 'Yes' : 'No') . "\n";
        fwrite($file, $logMessage);
        fclose($file);
    } else {
        error_log("Unable to open isregistered.txt for writing");
    }

    return $isRegistered;
}




function PublicJoinFreeEvent($name, $ic_number, $email, $phone_number, $event_id) {
    global $conn;

    // Check if the public has already registered for this event using the same email
    $stmt = $conn->prepare("
        SELECT je.event_reg_id 
        FROM joinevent je
        JOIN nonmember nm ON je.nonMember_event_reg_id = nm.nonMember_event_reg_id
        WHERE nm.email = ? AND je.event_id = ?
    ");
    $stmt->bind_param("si", $email, $event_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => "You've already joined this event!"]);
        $stmt->close();
        $conn->close();
        return;
    }
    $stmt->close();

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert into nonmember table
        $stmt = $conn->prepare("INSERT INTO nonmember (name, ic_number, email, phone_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $ic_number, $email, $phone_number);
        $stmt->execute();
        
        // Get the last inserted ID
        $nonMember_event_reg_id = $conn->insert_id;

        // Insert into joinevent table
        $stmt = $conn->prepare("INSERT INTO joinevent (event_id, nonMember_event_reg_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $event_id, $nonMember_event_reg_id);
        $stmt->execute();

        // Commit the transaction
        mysqli_commit($conn);

        echo json_encode(['status' => 'success', 'message' => 'Successfully registered for the event.']);
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_roll_back($conn);
        
        echo json_encode(['status' => 'error', 'message' => 'An error occurred during registration. Please try again later.']);
    } finally {
        $stmt->close();
        $conn->close();
    }
}



function PublicMakePayment($event_id, $billcode, $transaction_id, $payment_status, $name, $email, $phone_number, $ic_number) {
    global $conn;

    // Get event details
    $event = getEventById($conn, $event_id);
    if ($event === null) {
        throw new Exception("Event not found");
    }
    $payment_name = $event['event_name']; // Use event_name as payment_name
    $payment_fee = $event['event_price']; // Use event_price as payment_fee

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert into nonmember table
        $sql_insert_nonmember = "INSERT INTO nonmember (name, ic_number, email, phone_number) VALUES (?, ?, ?, ?)";
        $stmt_insert_nonmember = $conn->prepare($sql_insert_nonmember);
        $stmt_insert_nonmember->bind_param('ssss', $name, $ic_number, $email, $phone_number);

        if (!$stmt_insert_nonmember->execute()) {
            throw new Exception("Failed to insert into nonmember");
        }

        $nonMember_event_reg_id = $stmt_insert_nonmember->insert_id;

        // Insert into payment table
        $payment_time = date('H:i:s'); // Current time
        $payment_date = date('Y-m-d'); // Current date
        $user_id = null; // Assuming this remains null for public payments

        $sql_insert_payment = "INSERT INTO payment (payment_id, payment_name, payment_fee, payment_time, payment_date, user_id, nonMember_event_reg_id, payment_status, transaction_id, event_id)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_payment = $conn->prepare($sql_insert_payment);
        $stmt_insert_payment->bind_param('ssdssisssi', $billcode, $payment_name, $payment_fee, $payment_time, $payment_date, $user_id, $nonMember_event_reg_id, $payment_status, $transaction_id, $event_id);

        if (!$stmt_insert_payment->execute()) {
            throw new Exception("Failed to insert into payment");
        }

        // Insert into joinevent table
        if ($payment_status !== "fail") {
            $sql_insert_joinevent = "INSERT INTO joinevent (event_id, user_id, nonMember_event_reg_id) VALUES (?, ?, ?)";
            $stmt_insert_joinevent = $conn->prepare($sql_insert_joinevent);
            $stmt_insert_joinevent->bind_param('iii', $event_id, $user_id, $nonMember_event_reg_id);

            if (!$stmt_insert_joinevent->execute()) {
                throw new Exception("Failed to insert into joinevent");
            }

            // Deduct one from total_participation in abmevent table
            $sql_update_event = "UPDATE abmevent SET total_participation = total_participation - 1 WHERE event_id = ?";
            $stmt_update_event = $conn->prepare($sql_update_event);
            $stmt_update_event->bind_param('i', $event_id);

            if (!$stmt_update_event->execute()) {
                throw new Exception("Failed to update total_participation in abmevent");
            }
        }

        // Commit the transaction
        $conn->commit();

        return true; // Success

    } catch (Exception $e) {
        // Roll back the transaction in case of error
        $conn->rollback();
        error_log("Transaction failed: " . $e->getMessage());
        return false; // Failure
    }
}





if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'PublicJoinFreeEvent') {
        $name = $_POST['name'];
        $ic_number = $_POST['ic_number'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $event_id = $_POST['event_id'];

        PublicJoinFreeEvent($name, $ic_number, $email, $phone_number, $event_id);
    } elseif ($action === 'checkRegistration') {
        $email = $_POST['email'];
        $event_id = $_POST['event_id'];

        $isRegistered = isNonMemberRegistered($conn, $email, $event_id);

        echo json_encode(['isRegistered' => $isRegistered]);
    }
}


?>
