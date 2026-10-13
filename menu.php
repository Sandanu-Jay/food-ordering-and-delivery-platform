<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 require 'config.php'; $pageTitle='Menu'; $items=menu_items(); $initial=$_GET['category']??'All'; $cats=['All','Pizza','Burgers','Rice','Pasta','Sides','Drinks','Desserts']; require 'includes/header.php'; ?>
<section class="page-hero">
<div class="shell">
<span class="eyebrow">SANOVA RESTAURANT</span>
<h1>Our Menu</h1>
<p>Choose a category or search for your favourite meal.</p>
</div>
</section>
<section class="section">
<div class="shell menu-layout">
<aside class="sidebar"><?php foreach($cats as $cat): ?><button type="button" class="filter-btn <?= $initial===$cat?'active':'' ?>" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button><?php endforeach; ?></aside>
<div>
<div class="backbar">
<a class="backlink" href="index.php">← Back</a>
<a href="cart.php">🛒 Your Cart (<?= cart_count() ?>)</a>
</div>
<div class="search-box">
<input id="menuSearch" type="search" placeholder="Search food by name or description...">
</div>
<div class="menu-list"><?php foreach($items as $item): ?><article class="menu-item" data-menu-item data-category="<?= e($item['category']) ?>" data-id="<?= (int)$item['id'] ?>" data-name="<?= e($item['name']) ?>" data-description="<?= e($item['description']) ?>" data-image="<?= e($item['image']) ?>" data-price="<?= e(money((float)$item['price'])) ?>" data-rating="<?= e((string)$item['rating']) ?>" <?= ($initial!=='All'&&$initial!==$item['category'])?'hidden':'' ?> tabindex="0" role="button" aria-label="View <?= e($item['name']) ?> details">
<img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>">
<div>
<small class="eyebrow"><?= e($item['category']) ?></small>
<h3><?= e($item['name']) ?></h3>
<p><?= e($item['description']) ?></p>
<small>★ <?= e((string)$item['rating']) ?></small>
</div>
<div class="menu-action">
<strong><?= money((float)$item['price']) ?></strong>
<form method="post" action="cart.php">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="add">
<input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
<button class="small-btn">Add</button>
</form>
</div>
</article><?php endforeach; ?></div>
</div>
</div>
</section>
<div class="food-modal" id="foodModal" aria-hidden="true">
<div class="food-modal-backdrop" data-modal-close>
</div>
<div class="food-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalFoodName">
<button class="food-modal-close" type="button" data-modal-close aria-label="Close">×</button>
<div class="food-modal-image-wrap">
<img id="modalFoodImage" src="" alt="">
<span class="food-modal-category" id="modalFoodCategory">
</span>
</div>
<div class="food-modal-content">
<div class="food-modal-title-row">
<div>
<span class="food-modal-rating" id="modalFoodRating">
</span>
<h2 id="modalFoodName">
</h2>
</div>
<strong id="modalFoodPrice">
</strong>
</div>
<p id="modalFoodDescription">
</p>
<form method="post" action="cart.php" class="food-modal-form">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="add">
<input type="hidden" name="id" id="modalFoodId" value="">
<button class="btn btn-primary" type="submit">Add to Cart <span>→</span>
</button>
</form>
</div>
</div>
</div>
<?php require 'includes/footer.php'; ?>
