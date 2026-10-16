<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
try {
    $pdo = db(); // Automatically installs idempotent PostgreSQL schema on first connection.
    $ok = (bool)$pdo->query("SELECT to_regclass('public.tools') IS NOT NULL AND to_regclass('public.api_keys') IS NOT NULL")->fetchColumn();
    if (!$ok) {
        http_response_code(503);
        echo json_encode(['status'=>'degraded','database'=>'connected','schema'=>'missing']);
        exit;
    }
    echo json_encode(['status'=>'ok','database'=>'connected','schema'=>'ready']);
} catch (Throwable $e) {
    error_log('ToolkitPro health check failed: '.$e->getMessage());
    http_response_code(503);
    echo json_encode(['status'=>'degraded','database'=>'unavailable']);
}