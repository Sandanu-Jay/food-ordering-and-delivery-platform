<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.
 require 'config.php';
$mode=($_GET['mode']??'login')==='register'?'register':'login';$error='';
if(is_post()){verify_csrf();$mode=$_POST['mode']??'login';$pdo=db();
 if($mode==='register'){$name=trim($_POST['name']??'');$email=strtolower(trim($_POST['email']??''));$pass=$_POST['password']??'';if(!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<6)$error='Enter a valid name, email and password (minimum 6 characters).';elseif(!$pdo)$error='Database is not connected. Import sql/sanova.sql in phpMyAdmin first.';else{try{$st=$pdo->prepare('INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,\'customer\')');$st->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT)]);$_SESSION['user']=['id'=>(int)$pdo->lastInsertId(),'name'=>$name,'email'=>$email,'role'=>'customer'];redirect('account.php');}catch(Throwable $e){$error='That email is already registered.';}}}
 else{$email=strtolower(trim($_POST['email']??''));$pass=$_POST['password']??'';if(!$pdo)$error='Database is not connected. Import sql/sanova.sql in phpMyAdmin first.';else{$st=$pdo->prepare('SELECT id,name,email,password_hash,role FROM users WHERE email=?');$st->execute([$email]);$u=$st->fetch();if($u&&password_verify($pass,$u['password_hash'])){$_SESSION['user']=['id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']];redirect($u['role']==='admin'?'admin/index.php':'account.php');}else $error='Incorrect email or password.';}}
}
$pageTitle='Login';require 'includes/header.php';?>
<div class="auth-wrap">
<div class="auth-card">
<div class="tabs">
<a class="<?= $mode==='login'?'active':'' ?>" href="login.php">Login</a>
<a class="<?= $mode==='register'?'active':'' ?>" href="login.php?mode=register">Register</a>
</div>
<h2><?= $mode==='login'?'Welcome back':'Create your account' ?></h2><?php if($error): ?><div class="notice danger"><?= e($error) ?></div><?php endif; ?><form method="post">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="mode" value="<?= e($mode) ?>"><?php if($mode==='register'): ?><label>Full Name</label>
<input name="name" required placeholder="John Doe"><?php endif; ?><label>Email</label>
<input type="email" name="email" required placeholder="you@example.com">
<label>Password</label>
<input type="password" name="password" required placeholder="••••••••">
<button class="btn btn-primary" style="width:100%;margin-top:18px"><?= $mode==='login'?'Login':'Register' ?></button>
</form>
<div class="or">or</div>
<button class="btn btn-outline" style="width:100%" type="button" onclick="alert('Google sign-in is a UI demo in this university project.')">G &nbsp; Continue with Google</button><?php if($mode==='login'): ?><p style="font-size:12px;color:var(--muted);margin-top:18px">Demo admin: <b>admin@sanova.test</b> / <b>admin123</b>
</p><?php endif; ?></div>
</div><?php require 'includes/footer.php'; ?>
