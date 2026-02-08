<?php

// /database/migrations/004_create_attachments.php

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$table = Database::table('attachments');
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

if ($driver === 'mysql') {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        path VARCHAR(512) NOT NULL,
        filename VARCHAR(255) NOT NULL,
        mime_type VARCHAR(191) NOT NULL,
        size BIGINT UNSIGNED NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    SQL;
} else {
    // SQLite padrão
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        path TEXT NOT NULL,
        filename TEXT NOT NULL,
        mime_type TEXT NOT NULL,
        size INTEGER NOT NULL,
        created_at TEXT DEFAULT (datetime('now'))
    );
    SQL;
}

$pdo->exec($sql);
