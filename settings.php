<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 $pageTitle='Settings'; require '_layout_top.php';$msg='';if(is_post()){verify_csrf();$msg='Demo settings saved for this session.';}?>
<div class="card">
<h3>Restaurant Settings</h3><?php if($msg): ?><div class="notice success"><?= e($msg) ?></div><?php endif; ?><form method="post" class="admin-form">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<label>Restaurant Name</label>
<input value="Sanova Restaurant">
<label>Delivery Fee</label>
<input type="number" value="350">
<label>Free Delivery Above</label>
<input type="number" value="5000">
<label>Contact Number</label>
<input value="011 234 5678">
<button class="btn btn-primary">Save Settings</button>
</form>
</div><?php require '_layout_bottom.php'; ?>
