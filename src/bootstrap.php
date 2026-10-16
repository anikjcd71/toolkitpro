<?php
declare(strict_types=1);
$isHttps=(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || (($_SERVER['HTTP_X_FORWARDED_PROTO']??'')==='https');
session_name('toolkitpro_session');
session_set_cookie_params(['httponly'=>true,'secure'=>$isHttps,'samesite'=>'Lax','path'=>'/']);
if(session_status()!==PHP_SESSION_ACTIVE) session_start();
header('X-Content-Type-Options: nosniff'); header('X-Frame-Options: DENY'); header('Referrer-Policy: strict-origin-when-cross-origin');
function envv(string $key, ?string $default=null): ?string { $v=getenv($key); return $v===false?$default:$v; }
function db(): PDO {
 static $pdo=null; if($pdo instanceof PDO) return $pdo;
 $databaseUrl=envv('DATABASE_URL');
 $driver=strtolower(envv('DB_DRIVER',($databaseUrl && str_starts_with($databaseUrl,'mysql://'))?'mysql':'pgsql')??'pgsql');
 if($databaseUrl!==null && $databaseUrl!=='') {
  $parts=parse_url($databaseUrl);
  if(!$parts || empty($parts['host']) || empty($parts['path'])) throw new RuntimeException('Invalid DATABASE_URL.');
  $host=$parts['host']; $port=(string)($parts['port']??($driver==='pgsql'?'5432':'3306')); $name=ltrim($parts['path'],'/');
  $user=rawurldecode((string)($parts['user']??'')); $pass=rawurldecode((string)($parts['pass']??''));
 } else {
  $host=envv('DB_HOST','127.0.0.1'); $port=envv('DB_PORT',$driver==='pgsql'?'5432':'3306'); $name=envv('DB_NAME','toolkitpro');
  $user=envv('DB_USER','')??''; $pass=envv('DB_PASS','')??'';
 }
 if($driver==='pgsql') $dsn="pgsql:host=$host;port=$port;dbname=$name";
 elseif($driver==='mysql') $dsn="mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
 else throw new RuntimeException('Unsupported DB_DRIVER. Use pgsql or mysql.');
 $pdo=new PDO($dsn,$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
 if ($driver === 'pgsql' && envv('AUTO_DB_MIGRATE','1') === '1') {
  ensure_pg_schema($pdo);
 }
 return $pdo;
}
function ensure_pg_schema(PDO $pdo): void {
 static $checked=false;
 if($checked) return;
 $checked=true;
 // Fast path: tables already exist.
 $ready=(bool)$pdo->query("SELECT to_regclass('public.tools') IS NOT NULL AND to_regclass('public.api_keys') IS NOT NULL")->fetchColumn();
 if($ready) return;
 // Serialize first-run migration across concurrent web requests.
 $pdo->query("SELECT pg_advisory_lock(72619042)");
 try {
  $ready=(bool)$pdo->query("SELECT to_regclass('public.tools') IS NOT NULL AND to_regclass('public.api_keys') IS NOT NULL")->fetchColumn();
  if(!$ready) {
   $base=dirname(__DIR__).'/database/';
   $files=['schema.pgsql.sql','api_migration.pgsql.sql'];
   $pdo->beginTransaction();
   try {
    foreach($files as $file) {
     $path=$base.$file;
     if(!is_file($path)) throw new RuntimeException('Database migration file missing: '.$file);
     $sql=file_get_contents($path);
     if($sql===false || trim($sql)==='') throw new RuntimeException('Database migration file empty: '.$file);
     $pdo->exec($sql);
    }
    $pdo->commit();
   } catch(Throwable $e) {
    if($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
   }
  }
 } finally {
  $pdo->query("SELECT pg_advisory_unlock(72619042)");
 }
}
function e(?string $s): string { return htmlspecialchars($s??'',ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function csrf_token(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field(): string { return '<input type="hidden" name="_csrf" value="'.e(csrf_token()).'">'; }
function csrf_check(): void { if(!hash_equals(csrf_token(),(string)($_POST['_csrf']??''))) { http_response_code(403); exit('Invalid CSRF token'); } }
function admin_user(): bool { return !empty($_SESSION['admin_id']); }
function require_admin(): void { if(!admin_user()) { header('Location: /admin/login.php'); exit; } }
