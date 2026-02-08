<?php

define('CLI_MODE', true);

require_once __DIR__ . '/bootstrap.php';

$migrationsDir = __DIR__ . '/database/migrations';

if (!is_dir($migrationsDir)) {
    echo "Migrations directory not found: $migrationsDir\n";
    exit(1);
}

$files = scandir($migrationsDir);
$migrations = array_filter($files, fn($f) => preg_match('/^\d+_.*\.php$/', $f));
sort($migrations);

echo "Running migrations...\n";

foreach ($migrations as $migration) {
    $filePath = $migrationsDir . '/' . $migration;
    echo "Executing: $migration\n";
    
    try {
        require_once $filePath;
        echo "✓ $migration completed\n\n";
    } catch (Exception $e) {
        echo "✗ $migration failed: " . $e->getMessage() . "\n\n";
    }
}

echo "All migrations completed!\n";
