<?php
// /config.php
// Configurações globais do projeto

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    return require $local;
}

return [

    // driver do banco: sqlite ou mysql
    'database' => [
        'driver' => 'mysql', // usar 'sqlite' apenas se quiser o arquivo local

        // SQLite
        'sqlite' => [
            'database' => DB_SQLITE_PATH, // já definido no bootstrap
        ],

        // MySQL (produção)
        'mysql' => [
            'host'     => getenv('DB_HOST') ?: '',
            'port'     => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_NAME') ?: '',
            'username' => getenv('DB_USER') ?: '',
            'password' => getenv('DB_PASS') ?: '',
            'charset'  => 'utf8mb4',
            'collation'=> 'utf8mb4_unicode_ci',
        ],
    ],
];
