<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 $pageTitle='Customers'; require '_layout_top.php';$pdo=db();$users=$pdo?$pdo->query("SELECT u.id,u.name,u.email,u.created_at,COUNT(o.id) orders,COALESCE(SUM(o.total),0) spent FROM users u LEFT JOIN orders o ON o.user_id=u.id WHERE u.role='customer' GROUP BY u.id ORDER BY u.id DESC")->fetchAll():[];?>
<div class="card">
<div class="table-scroll">
<table class="admin-table">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Orders</th>
<th>Total Spend</th>
<th>Joined</th>
</tr><?php foreach($users as $u): ?><tr>
<td><?= $u['id'] ?></td>
<td><?= e($u['name']) ?></td>
<td><?= e($u['email']) ?></td>
<td><?= $u['orders'] ?></td>
<td><?= money((float)$u['spent']) ?></td>
<td><?= e(substr($u['created_at'],0,10)) ?></td>
</tr><?php endforeach; ?></table>
</div>
</div><?php require '_layout_bottom.php'; ?>
