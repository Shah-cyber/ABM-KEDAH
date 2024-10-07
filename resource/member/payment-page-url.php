<?php
include '../sql/member-functions.php';

$userSecretKey = 'r4g0hz47-yrjp-f02o-cb2x-mpaw5vhrtiom';
$categoryCode = '8z16u61w';

header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $event = getEventById($conn, $event_id);

    if ($event && !$event['slot_reserved']) { // Check if slot is not already reserved
        session_start();
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $role = $_SESSION['role'];
            $userInfo = getUserInfo($user_id, $role);

            if ($userInfo) {
                $billName = $event['event_name'];
                $billDescription = 'Payment Process to settle.'; // Initial description
                $billAmount = $event['event_price'];
                $userEmail = $userInfo['email'];
                $userPhone = $userInfo['phone_number'];
                $userName = $userInfo['username'];

                // Truncate billDescription to 100 characters if necessary
                $billDescription = strlen($billDescription) > 100 ? substr($billDescription, 0, 100) : $billDescription;

                $url = 'https://dev.toyyibpay.com/index.php/api/createBill';

                // Set your billReturnUrl and billCallbackUrl here
                $billReturnUrl = 'http://localhost/abm-Kedah/resource/member/payment-receipt.php?event_id=' . $event_id . '&user_id=' . $user_id;
                $billCallbackUrl = 'http://localhost/abm-Kedah/resource/member/payment-callback.php';

                // Reserve the slot before processing payment
                reserveSlot($conn, $event_id);

                $data = [
                    'userSecretKey' => $userSecretKey,
                    'categoryCode' => $categoryCode,
                    'billName' => $billName,
                    'billDescription' => $billDescription,
                    'billPriceSetting' => 1,
                    'billPayorInfo' => 1,
                    'billAmount' => $billAmount * 100, // Convert to cents
                    'billTo' => $userName,
                    'billEmail' => $userEmail,
                    'billPhone' => $userPhone,
                    'billSplitPayment' => 0,
                    'billSplitPaymentArgs' => '',
                    'billPaymentChannel' => 0,
                    'billContentEmail' => '',
                    'billChargeToCustomer' => 1,
                    'billReturnUrl' => $billReturnUrl,
                    'billCallbackUrl' => $billCallbackUrl
                ];

                $options = [
                    'http' => [
                        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                        'method' => 'POST',
                        'content' => http_build_query($data)
                    ]
                ];

                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);

                if ($result === FALSE) {
                    error_log("Failed to fetch result from ToyibPay API: " . var_export(error_get_last(), true));
                    releaseSlot($conn, $event_id); // Release slot if payment request fails
                    echo json_encode(['status' => 'error', 'message' => 'Unable to create payment request. Please try again later.']);
                } else {
                    // Log the response for debugging
                    error_log("ToyibPay API Response: " . var_export($result, true));

                    $response = json_decode($result, true);

                    if ($response === null || !isset($response[0]['BillCode'])) {
                        error_log("Invalid response from ToyibPay API: " . var_export($response, true));
                        releaseSlot($conn, $event_id); // Release slot if payment request fails
                        echo json_encode(['status' => 'error', 'message' => 'Unable to create payment request. Please try again later.']);
                    } else {
                        $billCode = $response[0]['BillCode'];
                        echo json_encode(['status' => 'success', 'message' => 'Redirecting to payment page', 'billCode' => $billCode]);
                    }
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User information not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Event not found or slots are already reserved.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
