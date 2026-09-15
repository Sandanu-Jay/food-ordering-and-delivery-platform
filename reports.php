<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 $pageTitle='Reports'; require '_layout_top.php';$pdo=db();$daily=$pdo?$pdo->query("SELECT DATE(created_at) day,COUNT(*) orders,SUM(total) sales FROM orders GROUP BY DATE(created_at) ORDER BY day DESC LIMIT 14")->fetchAll():[];$popular=$pdo?$pdo->query("SELECT item_name,SUM(quantity) qty,SUM(price*quantity) revenue FROM order_items GROUP BY item_name ORDER BY qty DESC LIMIT 10")->fetchAll():[];?>
<div class="two-col">
<div class="card">
<h3>Daily Sales</h3>
<table class="admin-table">
<tr>
<th>Date</th>
<th>Orders</th>
<th>Sales</th>
</tr><?php foreach($daily as $r): ?><tr>
<td><?= e($r['day']) ?></td>
<td><?= $r['orders'] ?></td>
<td><?= money((float)$r['sales']) ?></td>
</tr><?php endforeach; ?></table>
</div>
<div class="card">
<h3>Popular Items</h3>
<table class="admin-table">
<tr>
<th>Item</th>
<th>Qty</th>
<th>Revenue</th>
</tr><?php foreach($popular as $r): ?><tr>
<td><?= e($r['item_name']) ?></td>
<td><?= $r['qty'] ?></td>
<td><?= money((float)$r['revenue']) ?></td>
</tr><?php endforeach; ?></table>
</div>
</div><?php require '_layout_bottom.php'; ?>
