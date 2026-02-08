<?php
// /database/migrations/001_create_usuarios.php
// Cria a tabela usuarios

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();
$table = $prefix . 'usuarios';

if ($driver === 'mysql') {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        sobrenome VARCHAR(255) NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL,
        status TINYINT(1) NOT NULL DEFAULT 1,
        whatsapp VARCHAR(50) NULL,
        imagem_perfil VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    SQL;
} else {
    // SQLite como padrão
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        sobrenome TEXT NULL,
        email TEXT NOT NULL UNIQUE,
        senha TEXT NOT NULL,
        role TEXT NOT NULL,
        status INTEGER NOT NULL DEFAULT 1,
        whatsapp TEXT NULL,
        imagem_perfil TEXT NULL,
        created_at TEXT DEFAULT (datetime('now'))
    );
    SQL;
}

$pdo->exec($sql);
