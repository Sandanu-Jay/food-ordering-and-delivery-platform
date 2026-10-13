<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 require 'config.php'; $id=(int)($_GET['id']??0);$order=null;$pdo=db();if($id&&$pdo){$st=$pdo->prepare('SELECT * FROM orders WHERE id=?');$st->execute([$id]);$order=$st->fetch();}if(!$order&&$id&&isset($_SESSION['demo_orders'][$id]))$order=$_SESSION['demo_orders'][$id];$pageTitle='Track Order';require 'includes/header.php';
$steps=['Order Placed','Confirmed','Preparing Your Food','Out for Delivery','Delivered'];$index=0;if($order){$found=array_search($order['status'],$steps,true);$index=$found===false?0:$found;}?>
<section class="page-hero">
<div class="shell">
<h1>Track Order</h1>
<p>Follow the status of your Sanova delivery.</p>
</div>
</section>
<section class="section">
<div class="shell track-wrap"><?php if(isset($_GET['new'])): ?><div class="notice success">Order placed successfully. Your tracking number is #<?= $id ?>.</div><?php endif; ?><?php if(!$order): ?><div class="card">
<h3>Find your order</h3>
<form method="get" class="search-box">
<input type="number" name="id" required placeholder="Enter order number">
<button class="btn btn-primary">Track</button>
</form>
<p style="color:var(--muted);font-size:13px">You can also open the tracking page from My Account after logging in.</p>
</div><?php else: ?><div class="card">
<div class="order-box">
<div>
<small>Order</small>
<h2 style="margin:3px 0">#<?= $id ?></h2>
<span><?= e($order['created_at']) ?></span>
</div>
<div style="text-align:right">
<small>Total</small>
<h3><?= money((float)$order['total']) ?></h3>
</div>
</div>
<div class="timeline"><?php foreach($steps as $i=>$step): ?><div class="step <?= $i<=$index?'done':'' ?>">
<div class="step-dot"><?= $i<=$index?'✓':($i+1) ?></div>
<div>
<h4><?= e($step) ?></h4>
<p><?= $i<=$index?'Status completed or current':'Waiting for previous step' ?></p>
</div>
</div><?php endforeach; ?></div>
<div class="notice">
<b>Estimated Delivery Time</b>
<br>Approximately 30–45 minutes from confirmation.</div>
</div><?php endif; ?></div>
</section><?php require 'includes/footer.php'; ?>
