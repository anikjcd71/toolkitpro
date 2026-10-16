<?php
declare(strict_types=1);
require_once __DIR__ . '/../../src/bootstrap.php';
header('Cache-Control: no-store');
$token = envv('ADMIN_SETUP_TOKEN');
if (!$token || strlen($token) < 24) {
    http_response_code(404);
    exit('Admin setup is disabled.');
}
try {
    if ((int)db()->query('SELECT COUNT(*) FROM admins')->fetchColumn() > 0) {
        http_response_code(410);
        exit('An admin already exists. Setup is permanently closed.');
    }
} catch (Throwable $e) {
    error_log('ToolkitPro admin setup: '.$e->getMessage());
    http_response_code(503);
    exit('Database is not ready yet. Please retry after deployment.');
}
$error = '';
$done = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $provided = (string)($_POST['setup_token'] ?? '');
    $email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
    $password = (string)($_POST['password'] ?? '');
    if (!hash_equals($token, $provided)) {
        $error = 'Setup token is incorrect.';
    } elseif (!$email || strlen($password) < 12) {
        $error = 'Valid email and a password of at least 12 characters are required.';
    } else {
        try {
            $pdo = db();
            $pdo->beginTransaction();
            if ((int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn() > 0) {
                $pdo->rollBack();
                http_response_code(410);
                exit('An admin already exists. Setup is closed.');
            }
            $stmt = $pdo->prepare('INSERT INTO admins(email,password_hash) VALUES(?,?)');
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
            $pdo->commit();
            $done = true;
        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            error_log('ToolkitPro admin setup failed: '.$e->getMessage());
            $error = 'Could not create admin. Check the server logs.';
        }
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Initial Admin Setup — ToolkitPro</title><link rel="stylesheet" href="/assets/style.css"></head><body><main class="login"><h1>Initial admin setup</h1><?php if($done):?><p class="notice">Admin account created. Remove ADMIN_SETUP_TOKEN from Render Environment now, then sign in.</p><a href="/admin/login.php">Go to admin login</a><?php else:?><?php if($error):?><p class="error"><?=e($error)?></p><?php endif;?><p>One-time setup. Use the token configured in Render Environment.</p><form method="post"><?=csrf_field()?><label>Setup token<input type="password" name="setup_token" required autocomplete="off"></label><label>Admin email<input type="email" name="email" required autocomplete="username"></label><label>Strong password (12+ characters)<input type="password" name="password" minlength="12" required autocomplete="new-password"></label><button>Create admin</button></form><?php endif;?></main></body></html>