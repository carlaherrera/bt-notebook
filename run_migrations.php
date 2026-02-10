<?php
// /run_migrations.php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use App\Core\Database;

// Pasta das migrations
$migrationsPath = __DIR__ . '/database/migrations';

// Lista e ordena todos os arquivos php
$files = array_filter(scandir($migrationsPath), static function ($file) {
    return substr($file, -4) === '.php';
});
sort($files, SORT_NATURAL);

// Executa cada migration com captura de erros
foreach ($files as $file) {
    $path = $migrationsPath . '/' . $file;
    try {
        require $path;
        echo "Migration executada: {$file}\n";
    } catch (Throwable $e) {
        fwrite(STDERR, "Erro na migration {$file}: " . $e->getMessage() . "\n");
        exit(1);
    }
}

echo "Todas as migrations foram executadas.\n";
