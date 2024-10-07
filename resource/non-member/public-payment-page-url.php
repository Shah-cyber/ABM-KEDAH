<?php
include '../sql/nonmember-functions.php';

$userSecretKey = 'r4g0hz47-yrjp-f02o-cb2x-mpaw5vhrtiom';
$categoryCode = '8z16u61w';

header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $event = getEventById($conn, $event_id);

    if ($event) {
        $billName = $event['event_name'];
        $billDescription = 'Payment Process to settle.'; // Initial description
        $billAmount = $event['event_price'];
        $userEmail = $_POST['email'];
        $userName = $_POST['name'];
        $userPhone = $_POST['phone_number'];
        $userIcNumber = $_POST['ic_number']; // Include ic_number from form data

        
        // Truncate billDescription to 100 characters if necessary
        $billDescription = strlen($billDescription) > 100 ? substr($billDescription, 0, 100) : $billDescription;

        $url = 'https://dev.toyyibpay.com/index.php/api/createBill';

        // Set your billReturnUrl and billCallbackUrl here
        $billReturnUrl = 'http://localhost/abm-Kedah/resource/non-member/public-payment-receipt.php?event_id=' . $event_id
                . '&name=' . urlencode($userName)
                . '&email=' . urlencode($userEmail)
                . '&phone_number=' . urlencode($userPhone)
                . '&ic_number=' . urlencode($userIcNumber);

        $billCallbackUrl = 'http://localhost/abm-Kedah/resource/member/payment-callback.php';

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
            echo json_encode(['status' => 'error', 'message' => 'Unable to create payment request. Please try again later.']);
        } else {
            // Log the response for debugging
            error_log("ToyibPay API Response: " . var_export($result, true));

            $response = json_decode($result, true);

            if ($response === null || !isset($response[0]['BillCode'])) {
                error_log("Invalid response from ToyibPay API: " . var_export($response, true));
                echo json_encode(['status' => 'error', 'message' => 'Unable to create payment request. Please try again later.']);
            } else {
                $billCode = $response[0]['BillCode'];
                echo json_encode(['status' => 'success', 'message' => 'Redirecting to payment page', 'billCode' => $billCode]);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Event not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
