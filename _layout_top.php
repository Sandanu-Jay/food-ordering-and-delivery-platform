<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 require_once __DIR__.'/../config.php'; require_admin(); $base='../'; $pageTitle=$pageTitle??'Dashboard'; ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($pageTitle) ?> | Sanova Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard-shell">
<aside class="admin-side">
<a class="brand" href="index.php">
<span class="brand-mark">S</span>
<span>
<b>SANOVA</b>
<small>Restaurant</small>
</span>
</a>
<nav class="admin-nav">
<a href="index.php">⌂ Dashboard</a>
<a href="orders.php">▣ Orders</a>
<a href="menu_items.php">▤ Menu Items</a>
<a href="categories.php">▦ Categories</a>
<a href="customers.php">♙ Customers</a>
<a href="reports.php">◫ Reports</a>
<a href="settings.php">⚙ Settings</a>
<a href="../index.php">← Website</a>
<a href="../logout.php">↪ Logout</a>
</nav>
</aside>
<main class="admin-main">
<div class="admin-top">
<div>
<small>Sanova Restaurant</small>
<h1 style="margin:2px 0"><?= e($pageTitle) ?></h1>
</div>
<div><?= e(current_user()['name']) ?></div>
</div>
