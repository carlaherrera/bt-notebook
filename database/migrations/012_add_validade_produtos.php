<?php
// /database/migrations/012_add_validade_produtos.php
// Adiciona campos de validade e lote aos produtos

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();
$table = $prefix . 'produtos';

if ($driver === 'mysql') {
    $pdo->exec("ALTER TABLE {$table} 
        ADD COLUMN fabricado_em DATE NULL AFTER foto,
        ADD COLUMN expira_em DATE NULL AFTER fabricado_em,
        ADD COLUMN lote VARCHAR(120) NULL AFTER expira_em,
        ADD COLUMN observacoes TEXT NULL AFTER lote");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_produtos_expira_em ON {$table} (expira_em)");
} else {
    // SQLite não suporta ALTER TABLE complexo; cria colunas se não existirem
    $cols = $pdo->query("PRAGMA table_info({$table})")->fetchAll(PDO::FETCH_ASSOC);
    $names = array_map(fn($c) => $c['name'], $cols);
    if (!in_array('fabricado_em', $names, true)) {
        $pdo->exec("ALTER TABLE {$table} ADD COLUMN fabricado_em TEXT NULL");
    }
    if (!in_array('expira_em', $names, true)) {
        $pdo->exec("ALTER TABLE {$table} ADD COLUMN expira_em TEXT NULL");
    }
    if (!in_array('lote', $names, true)) {
        $pdo->exec("ALTER TABLE {$table} ADD COLUMN lote TEXT NULL");
    }
    if (!in_array('observacoes', $names, true)) {
        $pdo->exec("ALTER TABLE {$table} ADD COLUMN observacoes TEXT NULL");
    }
}

