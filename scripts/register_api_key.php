<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
$label = trim((string)($argv[1] ?? ''));
$plainKey = trim((string)($argv[2] ?? ''));
if ($label === '' || strlen($plainKey) < 32) {
    fwrite(STDERR, "Usage: php scripts/register_api_key.php 'Label' 'A-random-secret-key-at-least-32-characters'\n");
    exit(1);
}
$prefix = substr($plainKey, 0, 10);
$keyHash = hash('sha256', $plainKey);
db()->prepare('INSERT INTO api_keys(label,key_prefix,key_hash) VALUES(?,?,?)')->execute([$label,$prefix,$keyHash]);
fwrite(STDOUT, "API key registered. Keep the original key private.\n");
