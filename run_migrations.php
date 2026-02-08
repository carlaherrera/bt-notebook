<?php
// /run_migrations.php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use App\Core\Database;

// Pasta das migrations
$migrationsPath = __DIR__ . '/database/migrations';

// Lista todos os arquivos
$files = scandir($migrationsPath);

// Executa cada migration
foreach ($files as $file) {
    if (substr($file, -4) === '.php') {
        require $migrationsPath . '/' . $file;
        echo "Migration executada: {$file}\n";
    }
}

echo "Todas as migrations foram executadas.\n";
