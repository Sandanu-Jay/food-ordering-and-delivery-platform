<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 require 'config.php';
if(is_post()){verify_csrf();$action=$_POST['action']??'';$id=(int)($_POST['id']??0);if($action==='add')add_cart($id,(int)($_POST['qty']??1));elseif($action==='update')update_cart($id,(int)($_POST['qty']??0));elseif($action==='clear')$_SESSION['cart']=[];redirect('cart.php');}
$pageTitle='Cart';$totals=cart_totals();require 'includes/header.php';?>
<section class="page-hero">
<div class="shell">
<h1>Your Cart</h1>
<p>Review your items before checkout.</p>
</div>
</section>
<section class="section">
<div class="shell">
<div class="backbar">
<a class="backlink" href="menu.php">← Back to Menu</a><?php if(cart_count()): ?><form method="post">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="clear">
<button class="btn btn-outline">Clear Cart</button>
</form><?php endif; ?></div>
<?php if(!cart_count()): ?><div class="card empty">
<h2>Your cart is empty</h2>
<p>Add something delicious from our menu.</p>
<a class="btn btn-primary" href="menu.php">Browse Menu</a>
</div><?php else: ?><div class="cart-layout">
<div class="card">
<table class="cart-table">
<thead>
<tr>
<th>Item</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
</tr>
</thead>
<tbody><?php foreach(cart() as $row): ?><tr>
<td>
<div class="cart-product">
<img src="<?= e($row['image']) ?>">
<div>
<b><?= e($row['name']) ?></b>
<small style="display:block;color:var(--muted)">Regular Size</small>
</div>
</div>
</td>
<td><?= money($row['price']) ?></td>
<td>
<div class="qty-box">
<form method="post">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="qty" value="<?= $row['qty']-1 ?>">
<button>−</button>
</form>
<span><?= $row['qty'] ?></span>
<form method="post">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="qty" value="<?= $row['qty']+1 ?>">
<button>+</button>
</form>
</div>
</td>
<td>
<b><?= money($row['price']*$row['qty']) ?></b>
</td>
</tr><?php endforeach; ?></tbody>
</table>
</div>
<aside class="card summary">
<h3>Order Summary</h3>
<div class="summary-row">
<span>Subtotal</span>
<b><?= money($totals['subtotal']) ?></b>
</div>
<div class="summary-row">
<span>Delivery Fee</span>
<b><?= $totals['delivery']?money($totals['delivery']):'FREE' ?></b>
</div>
<div class="summary-row total">
<span>Total</span>
<span><?= money($totals['total']) ?></span>
</div>
<a class="btn btn-primary" style="width:100%;margin-top:15px" href="checkout.php">Proceed to Checkout</a>
</aside>
</div><?php endif; ?></div>
</section><?php require 'includes/footer.php'; ?>
