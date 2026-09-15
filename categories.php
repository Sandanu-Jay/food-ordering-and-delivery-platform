<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 $pageTitle='Categories'; require '_layout_top.php';$pdo=db();if(is_post()&&$pdo){verify_csrf();$name=trim($_POST['name']??'');if($name){try{$st=$pdo->prepare('INSERT INTO categories(name) VALUES(?)');$st->execute([$name]);}catch(Throwable $e){}}redirect('categories.php');}$cats=$pdo?$pdo->query('SELECT c.*,COUNT(mi.id) item_count FROM categories c LEFT JOIN menu_items mi ON mi.category_id=c.id GROUP BY c.id ORDER BY c.name')->fetchAll():[];?>
<div class="card">
<h3>Add Category</h3>
<form method="post" class="inline-actions">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input name="name" required placeholder="Category name" style="max-width:360px">
<button class="btn btn-primary">Add</button>
</form>
</div>
<div class="card" style="margin-top:20px">
<table class="admin-table">
<tr>
<th>ID</th>
<th>Category</th>
<th>Items</th>
</tr><?php foreach($cats as $c): ?><tr>
<td><?= $c['id'] ?></td>
<td><?= e($c['name']) ?></td>
<td><?= $c['item_count'] ?></td>
</tr><?php endforeach; ?></table>
</div><?php require '_layout_bottom.php'; ?>
