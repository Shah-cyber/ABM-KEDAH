<?php
include 'server.php';
// Ensure you set the timezone at the beginning of your script
date_default_timezone_set('Asia/Kuala_Lumpur');

function getActiveEvents($conn, $user_id) {
    // Assuming you have a table named `event_participation` that records which users have joined which events
    // Adjust table and column names as per your actual database schema
    $sql = "SELECT * 
            FROM abmevent e
            WHERE e.event_status = 'running'
            AND NOT EXISTS (
                SELECT 1 
                FROM joinevent ep 
                WHERE ep.event_id = e.event_id 
                AND ep.user_id = ?
            )";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("i", $user_id); // Assuming user_id is an integer
    
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


// Fetch joined events for a specific user
function getJoinedEvents($conn, $user_id) {
    $sql = "SELECT e.* FROM abmevent e 
            JOIN joinevent j ON e.event_id = j.event_id 
            WHERE j.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }

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










function insertPayment($user_id, $event_id, $transaction_id, $payment_fee, $payment_status, $payment_name, $nonMember_event_reg_id = null, $billcode = null, $payment_date = null, $payment_time = null) {
    global $conn; // Assuming $conn is declared and initialized globally in server.php

    // If payment_date and payment_time are not provided, set them to current date and time
    if (!$payment_date) {
        $payment_date = date('Y-m-d');
    }
    if (!$payment_time) {
        $payment_time = date('H:i:s');
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Check if the payment already exists
        $sql_check_payment = "SELECT payment_id FROM payment WHERE payment_id = ?";
        $stmt_check_payment = $conn->prepare($sql_check_payment);
        $stmt_check_payment->bind_param('s', $billcode);
        $stmt_check_payment->execute();
        $stmt_check_payment->store_result();

        if ($stmt_check_payment->num_rows === 0) {
            // Prepare the SQL statement for inserting into payment
            $sql_payment = "INSERT INTO payment (payment_fee, payment_time, payment_date, user_id, nonMember_event_reg_id, payment_status, transaction_id, payment_id, payment_name, event_id)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Bind parameters and execute the query
            $stmt_payment = $conn->prepare($sql_payment);
            $stmt_payment->bind_param('dssiissssi', $payment_fee, $payment_time, $payment_date, $user_id, $nonMember_event_reg_id, $payment_status, $transaction_id, $billcode, $payment_name, $event_id);

            if (!$stmt_payment->execute()) {
                throw new Exception("Failed to insert into payment");
            }

            // Check if payment_status is not "fail" before inserting into joinevent
            if ($payment_status !== "fail") {
                // Prepare the SQL statement for inserting into joinevent
                $sql_joinevent = "INSERT INTO joinevent (event_id, user_id, nonMember_event_reg_id) VALUES (?, ?, ?)";
                
                // Bind parameters and execute the query
                $stmt_joinevent = $conn->prepare($sql_joinevent);
                $stmt_joinevent->bind_param('iii', $event_id, $user_id, $nonMember_event_reg_id);

                if (!$stmt_joinevent->execute()) {
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
        }

        // Commit the transaction
        $conn->commit();

        return true; // Both insertions successful

    } catch (Exception $e) {
        // Roll back the transaction in case of error
        $conn->rollback();
        return false; // Insertion failed
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


function CheckIfEventJoined($conn, $event_id, $user_id) {
    $count = 0; // Initialize count to zero
    
    // SQL query to count how many times the user has joined the event
    $sql = "SELECT COUNT(*) AS count
            FROM joinevent
            WHERE user_id = ? AND event_id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        // Handle SQL prepare error (optional)
        return $count;
    }
    
    // Bind parameters
    $stmt->bind_param("ii", $user_id, $event_id); // Assuming both IDs are integers
    
    // Execute the query
    $stmt->execute();
    
    // Bind the result variables
    $stmt->bind_result($count);
    
    // Fetch the result
    $stmt->fetch();
    
    // Close statement
    $stmt->close();
    
    // Return the count of rows found
    return $count;
}


function retrieveFeePayment($conn, $user_id, $status) {
    $sql = "SELECT * FROM payment WHERE user_id = ? AND payment_status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $payments = array();

    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }

    $stmt->close();
    return $payments;
}


function retrievePaymentReceiptDetails($payment_id, $user_id) {
    global $conn;

    // Prepare the query to get payment details
    $paymentQuery = "
        SELECT p.payment_id, p.payment_name, p.payment_fee, p.payment_date, p.payment_time, p.payment_status, p.transaction_id, 
               e.event_name, r.name, r.email, r.address
        FROM payment p
        LEFT JOIN joinevent j ON p.user_id = j.user_id
        LEFT JOIN abmevent e ON j.event_id = e.event_id
        LEFT JOIN member m ON p.user_id = m.user_id
        LEFT JOIN registration r ON m.ic_number = r.ic_number
        WHERE p.payment_id = ? AND p.user_id = ?
    ";

    $stmt = $conn->prepare($paymentQuery);
    $stmt->bind_param('si', $payment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function MemberJoinFreeEvent($event_id, $user_id) {
    global $conn;

    // Check if the user has already joined the event
    $sql = "SELECT * FROM joinevent WHERE event_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return array(
            'status' => 'error',
            'message' => 'You have already joined this event.'
        );
    }

    // Insert the user into the joinevent table
    $sql = "INSERT INTO joinevent (event_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $user_id);
    
    if ($stmt->execute()) {
        // Reduce the total participation slots by 1
        $sql_update = "UPDATE abmevent SET total_participation = total_participation - 1 WHERE event_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $event_id);
        $stmt_update->execute();

        return array(
            'status' => 'success',
            'message' => 'Successfully joined the event.'
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Failed to join the event. Please try again.'
        );
    }
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

            case 'joinFreeEvent':
                // Retrieve user ID and event ID from POST request
                $user_id = $_POST['user_id'];
                $event_id = $_POST['event_id'];
                
                // Call the function to join the free event
                $response = MemberJoinFreeEvent($event_id, $user_id);
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


?>
