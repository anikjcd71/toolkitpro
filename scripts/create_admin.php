<?php
require_once __DIR__.'/../src/bootstrap.php';
if(PHP_SAPI!=='cli'){http_response_code(403);exit("CLI only\n");}
$email=$argv[1]??'';$password=$argv[2]??'';
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<12){fwrite(STDERR,"Usage: php scripts/create_admin.php valid@email.com 'password-at-least-12-chars'\n");exit(1);}
$hash=password_hash($password,PASSWORD_DEFAULT);
db()->prepare('INSERT INTO admins(email,password_hash) VALUES(?,?)')->execute([$email,$hash]);
echo "Admin created.\n";
