<?php
// /database/migrations/002_create_settings.php
// Cria a tabela settings

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();
$table = $prefix . 'settings';

if ($driver === 'mysql') {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        org_name VARCHAR(255) NOT NULL,
        cep VARCHAR(20) NOT NULL,
        rua VARCHAR(255) NOT NULL,
        numero VARCHAR(50) NOT NULL,
        cidade VARCHAR(255) NOT NULL,
        estado VARCHAR(100) NOT NULL,
        telefone VARCHAR(50) NOT NULL,
        whatsapp VARCHAR(50) NOT NULL,
        email VARCHAR(255) NOT NULL,
        cnpj VARCHAR(50) NULL,
        logo_light_path VARCHAR(255) NULL,
        logo_dark_path VARCHAR(255) NULL,
        favicon_path VARCHAR(255) NULL,
        primary_color VARCHAR(50) NULL,
        secondary_color VARCHAR(50) NULL,
        accent_color VARCHAR(50) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    SQL;
} else {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS {$table} (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        org_name TEXT NOT NULL,
        cep TEXT NOT NULL,
        rua TEXT NOT NULL,
        numero TEXT NOT NULL,
        cidade TEXT NOT NULL,
        estado TEXT NOT NULL,
        telefone TEXT NOT NULL,
        whatsapp TEXT NOT NULL,
        email TEXT NOT NULL,
        cnpj TEXT NULL,
        logo_light_path TEXT NULL,
        logo_dark_path TEXT NULL,
        favicon_path TEXT NULL,
        primary_color TEXT NULL,
        secondary_color TEXT NULL,
        accent_color TEXT NULL,
        created_at TEXT DEFAULT (datetime('now')),
        updated_at TEXT DEFAULT (datetime('now'))
    );
    SQL;
}

$pdo->exec($sql);
