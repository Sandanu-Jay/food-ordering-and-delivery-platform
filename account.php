<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.

require 'config.php';
require_login();
$pageTitle='My Profile';
$pdo=db();
$error='';
$success=flash('profile_success');

if (is_post()) {
    verify_csrf();
    $action=$_POST['action'] ?? 'profile';
    if (!$pdo) {
        $error='Database is not connected.';
    } elseif ($action==='profile') {
        $name=trim($_POST['name'] ?? '');
        $email=strtolower(trim($_POST['email'] ?? ''));
        if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $error='Please enter a valid name and email address.';
        } else {
            try {
                $st=$pdo->prepare('UPDATE users SET name=?, email=? WHERE id=?');
                $st->execute([$name,$email,current_user()['id']]);
                $_SESSION['user']['name']=$name;
                $_SESSION['user']['email']=$email;
                flash('profile_success','Profile details updated successfully.');
                redirect('account.php');
            } catch (Throwable $e) {
                $error='That email address is already used by another account.';
            }
        }
    } elseif ($action==='password') {
        $current=$_POST['current_password'] ?? '';
        $new=$_POST['new_password'] ?? '';
        $confirm=$_POST['confirm_password'] ?? '';
        $st=$pdo->prepare('SELECT password_hash FROM users WHERE id=?');
        $st->execute([current_user()['id']]);
        $row=$st->fetch();
        if (!$row || !password_verify($current,$row['password_hash'])) $error='Current password is incorrect.';
        elseif (strlen($new)<6) $error='New password must contain at least 6 characters.';
        elseif ($new!==$confirm) $error='New passwords do not match.';
        else {
            $st=$pdo->prepare('UPDATE users SET password_hash=? WHERE id=?');
            $st->execute([password_hash($new,PASSWORD_DEFAULT),current_user()['id']]);
            flash('profile_success','Password changed successfully.');
            redirect('account.php');
        }
    }
}

$orders=[];
if($pdo){
    $st=$pdo->prepare('SELECT * FROM orders WHERE user_id=? ORDER BY id DESC');
    $st->execute([current_user()['id']]);
    $orders=$st->fetchAll();
}
require 'includes/header.php';
$u=current_user();
$initial=strtoupper(substr(trim($u['name']),0,1));
?>
<section class="profile-hero">
<div class="shell profile-hero-inner">
<div class="profile-avatar-lg"><?= e($initial) ?></div>
<div>
<span class="eyebrow">CUSTOMER PROFILE</span>
<h1><?= e($u['name']) ?></h1>
<p><?= e($u['email']) ?></p>
</div>
</div>
</section>
<section class="section profile-section">
<div class="shell profile-layout">
<aside class="profile-side card">
<div class="profile-mini">
<div class="profile-avatar"><?= e($initial) ?></div>
<div>
<b><?= e($u['name']) ?></b>
<small>Customer account</small>
</div>
</div>
<nav class="profile-nav">
<a href="#details" class="active">👤 Profile Details</a>
<a href="#security">🔒 Password & Security</a>
<a href="#orders">🧾 My Orders</a>
<a href="menu.php">🍽️ Continue Shopping</a>
<a class="logout-item" href="logout.php">↪ Logout</a>
</nav>
</aside>
<div class="profile-content">
        <?php if($success): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
        <?php if($error): ?><div class="notice danger"><?= e($error) ?></div><?php endif; ?>
        <div class="card profile-card" id="details">
<div class="profile-card-head">
<div>
<span class="eyebrow">PERSONAL INFORMATION</span>
<h2>Edit Profile</h2>
</div>
<span class="profile-head-icon">✎</span>
</div>
<form method="post" class="profile-form">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="profile">
<div class="two-col">
<div>
<label>Full Name</label>
<input name="name" required value="<?= e($u['name']) ?>" placeholder="Your full name">
</div>
<div>
<label>Email Address</label>
<input type="email" name="email" required value="<?= e($u['email']) ?>" placeholder="you@example.com">
</div>
</div>
<div class="profile-form-actions">
<button class="btn btn-primary" type="submit">Save Changes</button>
</div>
</form>
</div>
<div class="card profile-card" id="security">
<div class="profile-card-head">
<div>
<span class="eyebrow">ACCOUNT SECURITY</span>
<h2>Change Password</h2>
</div>
<span class="profile-head-icon">🔐</span>
</div>
<form method="post" class="profile-form">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="action" value="password">
<label>Current Password</label>
<input type="password" name="current_password" required placeholder="Enter current password">
<div class="two-col">
<div>
<label>New Password</label>
<input type="password" name="new_password" required minlength="6" placeholder="Minimum 6 characters">
</div>
<div>
<label>Confirm New Password</label>
<input type="password" name="confirm_password" required minlength="6" placeholder="Repeat new password">
</div>
</div>
<div class="profile-form-actions">
<button class="btn btn-primary" type="submit">Update Password</button>
</div>
</form>
</div>
<div class="card profile-card" id="orders">
<div class="profile-card-head">
<div>
<span class="eyebrow">ORDER HISTORY</span>
<h2>Recent Orders</h2>
</div>
<span class="profile-head-icon">🛍️</span>
</div>
            <?php if(!$orders): ?><div class="empty">You have no database orders yet. <a href="menu.php" style="color:var(--orange);font-weight:700">Start ordering →</a>
</div><?php else: ?><div class="table-scroll">
<table class="admin-table">
<tr>
<th>Order</th>
<th>Date</th>
<th>Total</th>
<th>Status</th>
<th>
</th>
</tr><?php foreach($orders as $o): ?><tr>
<td>#<?= $o['id'] ?></td>
<td><?= e($o['created_at']) ?></td>
<td><?= money((float)$o['total']) ?></td>
<td>
<span class="badge orange"><?= e($o['status']) ?></span>
</td>
<td>
<a href="track.php?id=<?= $o['id'] ?>" style="color:var(--orange);font-weight:700">Track →</a>
</td>
</tr><?php endforeach; ?></table>
</div><?php endif; ?>
        </div>
</div>
</div>
</section>
<?php require 'includes/footer.php'; ?>
