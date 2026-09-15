<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 $pageTitle='Dashboard'; require '_layout_top.php'; $pdo=db();$stats=['orders'=>0,'sales'=>0,'pending'=>0,'customers'=>0];$recent=[];if($pdo){$stats['orders']=(int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();$stats['sales']=(float)$pdo->query('SELECT COALESCE(SUM(total),0) FROM orders')->fetchColumn();$stats['pending']=(int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status <> 'Delivered'")->fetchColumn();$stats['customers']=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();$recent=$pdo->query('SELECT * FROM orders ORDER BY id DESC LIMIT 6')->fetchAll();}?>
<div class="stat-grid">
<div class="stat">
<small>Total Orders</small>
<strong><?= $stats['orders'] ?></strong>
</div>
<div class="stat">
<small>Total Sales</small>
<strong><?= money($stats['sales']) ?></strong>
</div>
<div class="stat">
<small>Pending Orders</small>
<strong><?= $stats['pending'] ?></strong>
</div>
<div class="stat">
<small>Customers</small>
<strong><?= $stats['customers'] ?></strong>
</div>
</div>
<div class="card" style="margin-top:22px">
<div class="section-head">
<div>
<h2 style="font-size:22px">Recent Orders</h2>
</div>
<a href="orders.php">View All Orders →</a>
</div>
<div class="table-scroll">
<table class="admin-table">
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Date</th>
<th>Amount</th>
<th>Status</th>
</tr><?php foreach($recent as $o): ?><tr>
<td>#<?= $o['id'] ?></td>
<td><?= e($o['customer_name']) ?></td>
<td><?= e(substr($o['created_at'],0,10)) ?></td>
<td><?= money((float)$o['total']) ?></td>
<td>
<span class="badge orange"><?= e($o['status']) ?></span>
</td>
</tr><?php endforeach; ?></table>
</div><?php if(!$recent): ?><div class="empty">No orders yet. Place a test order from the website.</div><?php endif; ?></div><?php require '_layout_bottom.php'; ?>
