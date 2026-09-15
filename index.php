<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 require 'config.php'; $pageTitle='Home'; $items=array_slice(menu_items(),0,8); require 'includes/header.php'; ?>
<main>
<section class="hero">
<div class="shell hero-grid">
<div>
<span class="promo">20% OFF ON YOUR FIRST ORDER</span>
<h1>Delicious Food,<br>
<span>Delivered to Your Door</span>
</h1>
<p>Order your favourite meals from Sanova and enjoy fresh, delicious food delivered fast.</p>
<div class="hero-actions">
<a class="btn btn-primary" href="menu.php">Order Now →</a>
<a class="btn btn-outline" href="menu.php">Explore Menu</a>
</div>
<div class="hero-stats">
<div>
<strong>4.8 ★</strong>
<small>Customer Rating</small>
</div>
<div>
<strong>30 min</strong>
<small>Average Delivery</small>
</div>
<div>
<strong>100%</strong>
<small>Fresh Food</small>
</div>
</div>
</div>
<div class="hero-photo">
<img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1200&q=85" alt="Sanova meal">
<div class="float-card float-one">
<b>🛵 Fast Delivery</b>At your doorstep</div>
<div class="float-card float-two">
<b>★ 4.8 Rating</b>Excellent food</div>
</div>
</div>
</section>
<section class="section">
<div class="shell">
<div class="section-head">
<div>
<span class="eyebrow">OUR MENU CATEGORIES</span>
<h2>What are you craving?</h2>
</div>
<a href="menu.php">View all →</a>
</div>
<div class="categories-grid"><?php foreach([['🍕','Pizza'],['🍔','Burgers'],['🍚','Rice'],['🍝','Pasta'],['🥤','Drinks'],['🍰','Desserts']] as $c): ?><a class="category-card" href="menu.php?category=<?= urlencode($c[1]) ?>">
<span class="category-icon"><?= $c[0] ?></span>
<b><?= e($c[1]) ?></b>
</a><?php endforeach; ?></div>
</div>
</section>
<section class="section" style="padding-top:0">
<div class="shell">
<div class="section-head">
<div>
<span class="eyebrow">CUSTOMER FAVOURITES</span>
<h2>Popular Food Items</h2>
</div>
<a href="menu.php">View All →</a>
</div>
<div class="food-grid"><?php foreach($items as $item): ?><article class="food-card">
<img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>">
<div class="food-body">
<h3><?= e($item['name']) ?></h3>
<p><?= e($item['description']) ?></p>
<div class="price-row">
<strong><?= money((float)$item['price']) ?></strong>
<form method="post" action="cart.php">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="add">
<input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
<button class="small-btn">Add +</button>
</form>
</div>
</div>
</article><?php endforeach; ?></div>
</div>
</section>
<section class="section features" id="about">
<div class="shell">
<div class="feature-grid">
<div class="feature">
<span>🛵</span>
<h3>Fast Delivery</h3>
<p>Hot meals delivered quickly to your door.</p>
</div>
<div class="feature">
<span>🥗</span>
<h3>Fresh & Delicious</h3>
<p>Quality ingredients prepared for every order.</p>
</div>
<div class="feature">
<span>📱</span>
<h3>Easy Ordering</h3>
<p>Simple menu, cart, checkout and tracking.</p>
</div>
<div class="feature">
<span>🔒</span>
<h3>Secure Demo Payment</h3>
<p>Payment selection is simulated for this project.</p>
</div>
</div>
</div>
</section>
<section class="section" id="offers">
<div class="shell">
<div class="offer-strip">
<div>
<small>LIMITED OFFER</small>
<h2>Free delivery on orders above <b>Rs 5,000</b>
</h2>
</div>
<a class="btn btn-primary" href="menu.php">Order Now</a>
</div>
</div>
</section>
</main><?php require 'includes/footer.php'; ?>
