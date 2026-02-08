<?php
// /database/migrations/003_create_user_preferences.php
// Cria a tabela user_preferences

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();
$table = $prefix . 'user_preferences';
$usersTable = $prefix . 'usuarios';

if ($driver === 'mysql') {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        theme_preference VARCHAR(50) NOT NULL DEFAULT 'system',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_user_preferences_user FOREIGN KEY (user_id) REFERENCES {$usersTable}(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    SQL;
} else {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        theme_preference TEXT NOT NULL DEFAULT 'system',
        created_at TEXT DEFAULT (datetime('now')),
        updated_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY(user_id) REFERENCES {$usersTable}(id) ON DELETE CASCADE
    );
    SQL;
}

$pdo->exec($sql);
