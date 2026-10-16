<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../src/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function api_json(int $status, array $payload): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    api_json(405, ['error' => 'method_not_allowed', 'message' => 'Use GET.']);
}

$auth = (string)($_SERVER['HTTP_AUTHORIZATION'] ?? '');
if (!preg_match('/^Bearer\s+([A-Za-z0-9_-]{32,})$/i', $auth, $m)) {
    api_json(401, ['error' => 'unauthorized', 'message' => 'Provide an API key as Authorization: Bearer YOUR_KEY.']);
}
$key = $m[1];
$keyHash = hash('sha256', $key);
$pdo = db();
$stmt = $pdo->prepare('SELECT id FROM api_keys WHERE key_hash=? AND is_active=1 LIMIT 1');
$stmt->execute([$keyHash]);
$apiKey = $stmt->fetch();
if (!$apiKey) api_json(401, ['error' => 'invalid_api_key', 'message' => 'The API key is invalid or inactive.']);

$keyId = (int)$apiKey['id'];
$endpointName = '/api/v1/tools';
$driver = strtolower(envv('DB_DRIVER','pgsql') ?? 'pgsql');
$sinceSql = $driver === 'pgsql' ? "CURRENT_TIMESTAMP - INTERVAL '1 minute'" : 'NOW() - INTERVAL 1 MINUTE';
$rate = $pdo->prepare('SELECT COUNT(*) FROM api_requests WHERE api_key_id=? AND created_at >= (' . $sinceSql . ')');
$rate->execute([$keyId]);
if ((int)$rate->fetchColumn() >= 60) {
    $log = $pdo->prepare('INSERT INTO api_requests(api_key_id,endpoint,http_status) VALUES(?,?,?)');
    $log->execute([$keyId,$endpointName,429]);
    api_json(429, ['error' => 'rate_limit_exceeded', 'message' => 'Maximum 60 requests per minute.']);
}

$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
$offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT);
$limit = ($limit === false || $limit === null) ? 20 : max(1, min(100, $limit));
$offset = ($offset === false || $offset === null) ? 0 : max(0, $offset);
$q = trim((string)($_GET['q'] ?? ''));
$sql = "SELECT t.id,t.name,t.slug,t.description,c.name AS category FROM tools t LEFT JOIN categories c ON c.id=t.category_id WHERE t.status='published'";
$args = [];
if ($q !== '') {
    $sql .= ' AND (t.name LIKE ? OR t.description LIKE ?)';
    $args[] = '%' . $q . '%';
    $args[] = '%' . $q . '%';
}
$sql .= ' ORDER BY t.id DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
$stmt = $pdo->prepare($sql);
$stmt->execute($args);
$items = $stmt->fetchAll();

$pdo->prepare('UPDATE api_keys SET last_used_at=CURRENT_TIMESTAMP WHERE id=?')->execute([$keyId]);
$pdo->prepare('INSERT INTO api_requests(api_key_id,endpoint,http_status) VALUES(?,?,200)')->execute([$keyId,$endpointName]);
api_json(200, [
    'data' => $items,
    'pagination' => ['limit' => $limit, 'offset' => $offset],
    'docs' => 'ToolkitPro API v1'
]);
