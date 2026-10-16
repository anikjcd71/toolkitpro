<?php
require_once __DIR__ . '/../../src/bootstrap.php';
if (admin_user()) { header('Location: /admin/index.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check();
  $email=filter_var(trim((string)($_POST['email']??'')), FILTER_VALIDATE_EMAIL);
  $stmt=db()->prepare('SELECT id,email,password_hash FROM admins WHERE email=? LIMIT 1'); $stmt->execute([$email ?: '']);
  $a=$stmt->fetch();
  if($a && password_verify((string)($_POST['password']??''),$a['password_hash'])) {
    session_regenerate_id(true); $_SESSION['admin_id']=(int)$a['id']; header('Location: /admin/index.php'); exit;
  }
  $error='ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।';
}
?><!doctype html><html lang="bn"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login — ToolkitPro</title><link rel="stylesheet" href="/assets/style.css"></head><body><main class="login"><h1>Admin login</h1><?php if($error):?><p class="error"><?=e($error)?></p><?php endif;?><form method="post"><?=csrf_field()?><label>Email<input type="email" name="email" required autocomplete="username"></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button>Sign in</button></form><a href="/">← Public site</a></main></body></html>
