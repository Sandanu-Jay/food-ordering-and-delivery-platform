<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 require 'config.php'; if(!cart_count())redirect('cart.php'); $error='';
if(is_post()){verify_csrf();$name=trim($_POST['name']??'');$phone=trim($_POST['phone']??'');$address=trim($_POST['address']??'');$payment=$_POST['payment']??'cod';$allowed=['card','cod','online'];if(!$name||!$phone||!$address||!in_array($payment,$allowed,true))$error='Please complete all required checkout details.';else{$pdo=db();$tot=cart_totals();if($pdo){try{$pdo->beginTransaction();$st=$pdo->prepare('INSERT INTO orders(user_id,customer_name,phone,address,payment_method,subtotal,delivery_fee,total,status) VALUES(?,?,?,?,?,?,?,?,\'Order Placed\')');$st->execute([current_user()['id']??null,$name,$phone,$address,$payment,$tot['subtotal'],$tot['delivery'],$tot['total']]);$orderId=(int)$pdo->lastInsertId();$oi=$pdo->prepare('INSERT INTO order_items(order_id,menu_item_id,item_name,price,quantity) VALUES(?,?,?,?,?)');foreach(cart() as $row)$oi->execute([$orderId,$row['id'],$row['name'],$row['price'],$row['qty']]);$pdo->commit();$_SESSION['cart']=[];redirect('track.php?id='.$orderId.'&new=1');}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error='Could not place the order. Please check the database setup.';}}else{$orderId=random_int(12000,99999);$_SESSION['demo_orders'][$orderId]=['id'=>$orderId,'customer_name'=>$name,'phone'=>$phone,'address'=>$address,'payment_method'=>$payment,'total'=>$tot['total'],'status'=>'Order Placed','created_at'=>date('Y-m-d H:i:s')];$_SESSION['cart']=[];redirect('track.php?id='.$orderId.'&new=1');}}}
$pageTitle='Checkout';$tot=cart_totals();require 'includes/header.php';?>
<section class="page-hero">
<div class="shell">
<h1>Checkout</h1>
<p>Confirm delivery and choose a payment option.</p>
</div>
</section>
<section class="section">
<div class="shell checkout-layout">
<form method="post" class="card">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>"><?php if($error): ?><div class="notice danger"><?= e($error) ?></div><?php endif; ?><div class="form-section">
<h3>Delivery Address</h3>
<div class="two-col">
<input name="name" required placeholder="Full name" value="<?= e(current_user()['name']??'') ?>">
<input name="phone" required placeholder="Phone number">
</div>
<textarea name="address" rows="3" required placeholder="Delivery address" style="margin-top:12px">
</textarea>
</div>
<div class="form-section" style="margin-top:24px">
<h3>Payment Method</h3>
<label class="radio-card">
<input data-payment type="radio" name="payment" value="card" checked>
<span>
<b>Credit / Debit Card</b>
<br>
<small>Visa / Mastercard — demo only</small>
</span>
</label>
<div id="pay-card" class="payment-extra notice">
<div class="two-col">
<input placeholder="Card number (demo only)" inputmode="numeric">
<input placeholder="MM / YY">
</div>
<input style="margin-top:10px" placeholder="CVV (demo only)">
<small>No card details are submitted or stored.</small>
</div>
<label class="radio-card">
<input data-payment type="radio" name="payment" value="cod">
<span>
<b>Cash on Delivery</b>
<br>
<small>Pay when your food arrives.</small>
</span>
</label>
<div id="pay-cod" class="payment-extra notice" hidden>Cash will be collected by the delivery rider.</div>
<label class="radio-card">
<input data-payment type="radio" name="payment" value="online">
<span>
<b>Online Payment</b>
<br>
<small>PayPal / digital wallet simulation</small>
</span>
</label>
<div id="pay-online" class="payment-extra notice" hidden>This demo simulates a successful online payment.</div>
</div>
<button class="btn btn-primary" style="width:100%;margin-top:20px">Place Order</button>
</form>
<aside class="card summary">
<h3>Order Summary</h3><?php foreach(cart() as $row): ?><div class="summary-row">
<span><?= e($row['name']) ?> × <?= $row['qty'] ?></span>
<b><?= money($row['price']*$row['qty']) ?></b>
</div><?php endforeach; ?><div class="summary-row">
<span>Subtotal</span>
<b><?= money($tot['subtotal']) ?></b>
</div>
<div class="summary-row">
<span>Delivery Fee</span>
<b><?= $tot['delivery']?money($tot['delivery']):'FREE' ?></b>
</div>
<div class="summary-row total">
<span>Total</span>
<span><?= money($tot['total']) ?></span>
</div>
</aside>
</div>
</section><?php require 'includes/footer.php'; ?>
