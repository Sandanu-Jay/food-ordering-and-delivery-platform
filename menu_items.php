<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 $pageTitle='Menu Items'; require '_layout_top.php';$pdo=db();$msg='';if(is_post()&&$pdo){verify_csrf();$action=$_POST['action']??'add';if($action==='add'){$st=$pdo->prepare('INSERT INTO menu_items(category_id,name,description,price,rating,image_url,is_available) VALUES(?,?,?,?,?,?,1)');$st->execute([(int)$_POST['category_id'],trim($_POST['name']),trim($_POST['description']),(float)$_POST['price'],(float)$_POST['rating'],trim($_POST['image_url'])]);}elseif($action==='toggle'){$st=$pdo->prepare('UPDATE menu_items SET is_available=1-is_available WHERE id=?');$st->execute([(int)$_POST['id']]);}redirect('menu_items.php');}$cats=$pdo?$pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll():[];$items=$pdo?$pdo->query('SELECT mi.*,c.name category FROM menu_items mi JOIN categories c ON c.id=mi.category_id ORDER BY mi.id DESC')->fetchAll():[];?>
<div class="card">
<h3>Add Menu Item</h3>
<form method="post" class="admin-form">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="add">
<div class="two-col">
<input name="name" required placeholder="Item name">
<select name="category_id" required><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select>
</div>
<textarea name="description" required placeholder="Description">
</textarea>
<div class="two-col">
<input type="number" step="0.01" name="price" required placeholder="Price">
<input type="number" step="0.1" min="1" max="5" name="rating" value="4.5" required>
</div>
<input name="image_url" required placeholder="Image URL">
<button class="btn btn-primary">Add Item</button>
</form>
</div>
<div class="card" style="margin-top:20px">
<div class="table-scroll">
<table class="admin-table">
<tr>
<th>ID</th>
<th>Item</th>
<th>Category</th>
<th>Price</th>
<th>Available</th>
<th>
</th>
</tr><?php foreach($items as $i): ?><tr>
<td><?= $i['id'] ?></td>
<td><?= e($i['name']) ?></td>
<td><?= e($i['category']) ?></td>
<td><?= money((float)$i['price']) ?></td>
<td><?= $i['is_available']?'Yes':'No' ?></td>
<td>
<form method="post">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="toggle">
<input type="hidden" name="id" value="<?= $i['id'] ?>">
<button class="small-btn"><?= $i['is_available']?'Disable':'Enable' ?></button>
</form>
</td>
</tr><?php endforeach; ?></table>
</div>
</div><?php require '_layout_bottom.php'; ?>
