<?php
// /database/migrations/009_add_usuario_id_to_parceiros.php
// Adiciona vinculo de parceiro com usuario (role parceiro)

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();

$parceiros = $prefix . 'parceiros';
$usuarios = $prefix . 'usuarios';

if ($driver === 'mysql') {
    // adiciona coluna se não existir
    try {
        $pdo->exec("ALTER TABLE {$parceiros} ADD COLUMN usuario_id INT UNSIGNED NULL AFTER id");
    } catch (\Throwable $e) {
        // ignora se já existir
    }

    // remove FK/índice anteriores para evitar errno 121
    try {
        $pdo->exec("ALTER TABLE {$parceiros} DROP FOREIGN KEY fk_parceiro_usuario");
    } catch (\Throwable $e) {}
    try {
        $pdo->exec("ALTER TABLE {$parceiros} DROP INDEX uniq_parceiro_usuario");
    } catch (\Throwable $e) {}

    // cria FK e índice únicos
    try {
        $pdo->exec("ALTER TABLE {$parceiros} ADD CONSTRAINT fk_parceiro_usuario FOREIGN KEY (usuario_id) REFERENCES {$usuarios}(id) ON DELETE SET NULL");
    } catch (\Throwable $e) {
        // ignora se já existir
    }
    try {
        $pdo->exec("ALTER TABLE {$parceiros} ADD UNIQUE KEY uniq_parceiro_usuario (usuario_id)");
    } catch (\Throwable $e) {
        // ignora se já existir
    }
} else {
    // SQLite não suporta ALTER ADD COLUMN IF NOT EXISTS, mas é tolerante se já existir via try/catch
    try {
        $pdo->exec("ALTER TABLE {$parceiros} ADD COLUMN usuario_id INTEGER NULL");
    } catch (\Throwable $e) {
        // ignora se já existir
    }
    try {
        $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS uniq_parceiro_usuario ON {$parceiros}(usuario_id)");
    } catch (\Throwable $e) {
    }
    // SQLite não permite adicionar FK depois de criada sem recriar tabela; manteremos sem FK dura aqui.
}
