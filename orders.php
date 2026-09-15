<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 $pageTitle='Orders'; require '_layout_top.php';$pdo=db();if(is_post()&&$pdo){verify_csrf();$id=(int)$_POST['id'];$status=$_POST['status']??'Order Placed';$allowed=['Order Placed','Confirmed','Preparing Your Food','Out for Delivery','Delivered'];if(in_array($status,$allowed,true)){$st=$pdo->prepare('UPDATE orders SET status=? WHERE id=?');$st->execute([$status,$id]);}redirect('orders.php');}$orders=$pdo?$pdo->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll():[];?>
<div class="card">
<div class="table-scroll">
<table class="admin-table">
<tr>
<th>ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
<th>Update</th>
</tr><?php foreach($orders as $o): ?><tr>
<td>#<?= $o['id'] ?></td>
<td><?= e($o['customer_name']) ?></td>
<td><?= e($o['phone']) ?></td>
<td><?= money((float)$o['total']) ?></td>
<td><?= e(strtoupper($o['payment_method'])) ?></td>
<td>
<span class="badge orange"><?= e($o['status']) ?></span>
</td>
<td>
<form method="post" class="inline-actions">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="id" value="<?= $o['id'] ?>">
<select name="status" style="width:auto"><?php foreach(['Order Placed','Confirmed','Preparing Your Food','Out for Delivery','Delivered'] as $s): ?><option <?= $o['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select>
<button class="small-btn">Save</button>
</form>
</td>
</tr><?php endforeach; ?></table>
</div><?php if(!$orders): ?><div class="empty">No orders found.</div><?php endif; ?></div><?php require '_layout_bottom.php'; ?>
