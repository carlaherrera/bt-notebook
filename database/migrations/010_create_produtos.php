<?php
// /database/migrations/010_create_produtos.php
// Cria tabela de produtos (estoque próprio + consignado consolidado)

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();
$table = $prefix . 'produtos';

if ($driver === 'mysql') {
    $pdo->exec("CREATE TABLE IF NOT EXISTS {$table} (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        sku VARCHAR(100) NOT NULL UNIQUE,
        categoria VARCHAR(100) NULL,
        preco DECIMAL(10,2) NOT NULL DEFAULT 0,
        estoque_loja INT NOT NULL DEFAULT 0,
        estoque_consignado INT NOT NULL DEFAULT 0,
        minimo INT NOT NULL DEFAULT 0,
        status VARCHAR(30) NOT NULL DEFAULT 'ativo',
        foto VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
} else {
    $pdo->exec("CREATE TABLE IF NOT EXISTS {$table} (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        sku TEXT NOT NULL UNIQUE,
        categoria TEXT NULL,
        preco REAL NOT NULL DEFAULT 0,
        estoque_loja INTEGER NOT NULL DEFAULT 0,
        estoque_consignado INTEGER NOT NULL DEFAULT 0,
        minimo INTEGER NOT NULL DEFAULT 0,
        status TEXT NOT NULL DEFAULT 'ativo',
        foto TEXT NULL,
        created_at TEXT DEFAULT (datetime('now')),
        updated_at TEXT DEFAULT (datetime('now'))
    );");
}
