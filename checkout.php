<?php
require 'config.php';
if (!cart_count()) redirect('cart.php');
$error = '';

if (is_post()) {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $payment = $_POST['payment'] ?? 'cod';
    $allowed = ['cod', 'online'];

    if (!$name || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$phone || !$address || !$city || !in_array($payment, $allowed, true)) {
        $error = 'Please complete all required checkout details.';
    } else {
        $pdo = db();
        $tot = cart_totals();
        $orderId = null;

        if ($pdo) {
            try {
                $pdo->beginTransaction();
                $st = $pdo->prepare("INSERT INTO orders(user_id,customer_name,phone,address,payment_method,subtotal,delivery_fee,total,status) VALUES(?,?,?,?,?,?,?,?, 'Order Placed')");
                $st->execute([current_user()['id'] ?? null, $name, $phone, $address, $payment, $tot['subtotal'], $tot['delivery'], $tot['total']]);
                $orderId = (int)$pdo->lastInsertId();
                $oi = $pdo->prepare('INSERT INTO order_items(order_id,menu_item_id,item_name,price,quantity) VALUES(?,?,?,?,?)');
                foreach (cart() as $row) {
                    $oi->execute([$orderId, $row['id'], $row['name'], $row['price'], $row['qty']]);
                }
                $pdo->commit();
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $error = 'Could not place the order. Please check the database setup.';
            }
        } else {
            $orderId = random_int(12000, 99999);
            $_SESSION['demo_orders'][$orderId] = [
                'id'=>$orderId, 'customer_name'=>$name, 'phone'=>$phone, 'address'=>$address,
                'payment_method'=>$payment, 'total'=>$tot['total'], 'status'=>'Order Placed',
                'created_at'=>date('Y-m-d H:i:s')
            ];
        }

        if (!$error && $orderId) {
            if ($payment === 'online') {
                $parts = preg_split('/\s+/', trim($name), 2);
                $firstName = $parts[0] ?? $name;
                $lastName = $parts[1] ?? '-';
                $amount = number_format((float)$tot['total'], 2, '.', '');
                $currency = 'LKR';
                $hash = payhere_hash((string)$orderId, (float)$tot['total'], $currency);
                $base = base_url();
                $_SESSION['cart'] = [];
                ?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title>Redirecting to PayHere...</title></head>
<body>
<p>Redirecting to PayHere Sandbox...</p>
<form id="payhere-form" method="post" action="<?= e(PAYHERE_CHECKOUT_URL) ?>">
    <input type="hidden" name="merchant_id" value="<?= e(PAYHERE_MERCHANT_ID) ?>">
    <input type="hidden" name="return_url" value="<?= e($base . '/track.php?id=' . $orderId . '&payment=return') ?>">
    <input type="hidden" name="cancel_url" value="<?= e($base . '/track.php?id=' . $orderId . '&payment=cancel') ?>">
    <input type="hidden" name="notify_url" value="<?= e($base . '/payhere_notify.php') ?>">
    <input type="hidden" name="order_id" value="<?= e((string)$orderId) ?>">
    <input type="hidden" name="items" value="Sanova Order #<?= e((string)$orderId) ?>">
    <input type="hidden" name="currency" value="<?= e($currency) ?>">
    <input type="hidden" name="amount" value="<?= e($amount) ?>">
    <input type="hidden" name="first_name" value="<?= e($firstName) ?>">
    <input type="hidden" name="last_name" value="<?= e($lastName) ?>">
    <input type="hidden" name="email" value="<?= e($email) ?>">
    <input type="hidden" name="phone" value="<?= e($phone) ?>">
    <input type="hidden" name="address" value="<?= e($address) ?>">
    <input type="hidden" name="city" value="<?= e($city) ?>">
    <input type="hidden" name="country" value="Sri Lanka">
    <input type="hidden" name="hash" value="<?= e($hash) ?>">
</form>
<script>document.getElementById('payhere-form').submit();</script>
</body></html>
                <?php exit;
            }
            $_SESSION['cart'] = [];
            redirect('track.php?id=' . $orderId . '&new=1');
        }
    }
}

$pageTitle = 'Checkout';
$tot = cart_totals();
require 'includes/header.php';
?>
<section class="page-hero"><div class="shell"><h1>Checkout</h1><p>Confirm delivery and choose a payment option.</p></div></section>
<section class="section"><div class="shell checkout-layout">
<form method="post" class="card">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<?php if($error): ?><div class="notice danger"><?= e($error) ?></div><?php endif; ?>
<div class="form-section">
<h3>Delivery Address</h3>
<div class="two-col">
<input name="name" required placeholder="Full name" value="<?= e(current_user()['name'] ?? '') ?>">
<input name="email" type="email" required placeholder="Email" value="<?= e(current_user()['email'] ?? '') ?>">
</div>
<div class="two-col" style="margin-top:12px">
<input name="phone" required placeholder="Phone number">
<input name="city" required placeholder="City">
</div>
<textarea name="address" rows="3" required placeholder="Delivery address" style="margin-top:12px"></textarea>
</div>
<div class="form-section" style="margin-top:24px">
<h3>Payment Method</h3>
<label class="radio-card">
<input data-payment type="radio" name="payment" value="cod" checked>
<span><b>Cash on Delivery</b><br><small>Pay when your food arrives.</small></span>
</label>
<div id="pay-cod" class="payment-extra notice">Cash will be collected by the delivery rider.</div>
<label class="radio-card">
<input data-payment type="radio" name="payment" value="online">
<span><b>Online Payment</b><br><small>Secure payment via PayHere Sandbox</small></span>
</label>
<div id="pay-online" class="payment-extra notice" hidden>You will be redirected to PayHere Sandbox to complete the payment.</div>
</div>
<button class="btn btn-primary" style="width:100%;margin-top:20px">Place Order</button>
</form>
<aside class="card summary"><h3>Order Summary</h3>
<?php foreach(cart() as $row): ?><div class="summary-row"><span><?= e($row['name']) ?> × <?= $row['qty'] ?></span><b><?= money($row['price']*$row['qty']) ?></b></div><?php endforeach; ?>
<div class="summary-row"><span>Subtotal</span><b><?= money($tot['subtotal']) ?></b></div>
<div class="summary-row"><span>Delivery Fee</span><b><?= $tot['delivery'] ? money($tot['delivery']) : 'FREE' ?></b></div>
<div class="summary-row total"><span>Total</span><span><?= money($tot['total']) ?></span></div>
</aside></div></section>
<?php require 'includes/footer.php'; ?>
