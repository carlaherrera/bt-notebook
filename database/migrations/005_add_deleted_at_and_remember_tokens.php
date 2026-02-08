<?php

// /database/migrations/005_add_deleted_at_and_remember_tokens.php

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

$usuarios = Database::table('usuarios');
$remember = Database::table('remember_tokens');

// Adiciona deleted_at em usuarios
try {
    if ($driver === 'mysql') {
        $pdo->exec("ALTER TABLE {$usuarios} ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL");
    } else {
        // SQLite: ADD COLUMN simples
        $pdo->exec("ALTER TABLE {$usuarios} ADD COLUMN deleted_at TEXT NULL");
    }
} catch (\Throwable $e) {
    // ignora se já existe
}

// Tabela remember_tokens
if ($driver === 'mysql') {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$remember} (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        selector CHAR(16) NOT NULL UNIQUE,
        token_hash CHAR(64) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    SQL;
} else {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$remember} (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        selector TEXT NOT NULL UNIQUE,
        token_hash TEXT NOT NULL,
        expires_at TEXT NOT NULL,
        created_at TEXT DEFAULT (datetime('now'))
    );
    SQL;
}

$pdo->exec($sql);
