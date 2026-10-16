<?php
declare(strict_types=1);

/**
 * ToolkitPro PostgreSQL installer.
 * Run from the repository root: php database/install.php
 * CLI-only; never exposes a public web setup endpoint.
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit("Not found\n");
}

require_once __DIR__ . '/../src/bootstrap.php';

try {
    $pdo = db();
    $driver = (string) $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver !== 'pgsql') {
        fwrite(STDERR, "This installer is for PostgreSQL only.\n");
        exit(1);
    }

    foreach (['schema.pgsql.sql', 'api_migration.pgsql.sql'] as $file) {
        $path = __DIR__ . '/' . $file;
        if (!is_file($path)) {
            throw new RuntimeException("Required SQL file missing: {$file}");
        }

        $sql = file_get_contents($path);
        if ($sql === false || trim($sql) === '') {
            throw new RuntimeException("SQL file is empty or unreadable: {$file}");
        }

        $pdo->beginTransaction();
        try {
            $pdo->exec($sql);
            $pdo->commit();
            fwrite(STDOUT, "Applied {$file}\n");
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw new RuntimeException("Failed applying {$file}: " . $e->getMessage(), 0, $e);
        }
    }

    fwrite(STDOUT, "ToolkitPro PostgreSQL schema is ready.\n");
} catch (Throwable $e) {
    fwrite(STDERR, "ToolkitPro install failed: " . $e->getMessage() . "\n");
    exit(1);
}
