<?php

// scripts/attachments_cleanup.php
// Lista e remove anexos órfãos (sem referência em tabelas conhecidas).

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$attachmentsTable = Database::table('attachments');
$usuariosTable = Database::table('usuarios');
$settingsTable = Database::table('settings');

// Colete referências de caminhos usados em tabelas conhecidas
$usedPaths = [];

// users.imagem_perfil
$stmt = $pdo->query("SELECT imagem_perfil FROM {$usuariosTable} WHERE imagem_perfil IS NOT NULL AND imagem_perfil <> ''");
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $path) {
    $usedPaths[$path] = true;
}

// settings.favicon_path
$stmt = $pdo->query("SELECT favicon_path FROM {$settingsTable} WHERE favicon_path IS NOT NULL AND favicon_path <> ''");
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $path) {
    $usedPaths[$path] = true;
}

// Futuras tabelas: adicione aqui, ex:
// $stmt = $pdo->query("SELECT banner_path FROM {$someTable} ...");

// Lista anexos
$stmt = $pdo->query("SELECT id, path, filename, mime_type, size, created_at FROM {$attachmentsTable}");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$orphans = [];
foreach ($rows as $row) {
    if (!isset($usedPaths[$row['path']])) {
        $orphans[] = $row;
    }
}

$action = $argv[1] ?? 'list';

if ($action === 'list') {
    echo "Anexos órfãos encontrados: " . count($orphans) . PHP_EOL;
    foreach ($orphans as $o) {
        echo "#{$o['id']} {$o['path']} ({$o['mime_type']}, {$o['size']} bytes, {$o['created_at']})" . PHP_EOL;
    }
    exit(0);
}

if ($action === 'delete') {
    $deleted = 0;
    foreach ($orphans as $o) {
        $abs = str_starts_with($o['path'], '/')
            ? BASE_PATH . '/public' . $o['path']
            : $o['path'];

        if (is_file($abs)) {
            @unlink($abs);
        }

        $del = $pdo->prepare("DELETE FROM {$attachmentsTable} WHERE id = :id");
        $del->execute(['id' => $o['id']]);
        $deleted++;
    }
    echo "Removidos {$deleted} anexos órfãos." . PHP_EOL;
    exit(0);
}

echo "Uso: php scripts/attachments_cleanup.php [list|delete]" . PHP_EOL;
exit(1);
