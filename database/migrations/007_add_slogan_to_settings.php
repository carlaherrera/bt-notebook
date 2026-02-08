<?php
// /database/migrations/007_add_slogan_to_settings.php
// Adiciona coluna slogan à tabela settings

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();
$table = $prefix . 'settings';

if ($driver === 'mysql') {
    $sql = "ALTER TABLE {$table} ADD COLUMN slogan VARCHAR(255) NULL AFTER org_name";
} else {
    // SQLite: adicionar coluna simples
    $sql = "ALTER TABLE {$table} ADD COLUMN slogan TEXT NULL";
}

$pdo->exec($sql);
