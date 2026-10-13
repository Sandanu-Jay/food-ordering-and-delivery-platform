<?php
require 'config.php';

$merchantId = $_POST['merchant_id'] ?? '';
$orderId = $_POST['order_id'] ?? '';
$amount = $_POST['payhere_amount'] ?? '';
$currency = $_POST['payhere_currency'] ?? '';
$statusCode = $_POST['status_code'] ?? '';
$md5sig = $_POST['md5sig'] ?? '';

$localSig = strtoupper(md5(
    $merchantId . $orderId . $amount . $currency . $statusCode . strtoupper(md5(PAYHERE_MERCHANT_SECRET))
));

if ($merchantId === PAYHERE_MERCHANT_ID && hash_equals($localSig, $md5sig)) {
    // PayHere status_code 2 = successful payment.
    // The current Sanova schema has no payment_status column, so this endpoint verifies the callback only.
    // Add a payment_status column later if you want to store Success/Pending/Failed separately.
    http_response_code(200);
    exit;
}

http_response_code(400);
