<?php

require_once __DIR__ . '/../../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = Database::getDriver();
$usersTable = Database::table('usuarios');
$tokensTable = Database::table('password_reset_tokens');

$sql = match ($driver) {
    'mysql' => "
        CREATE TABLE IF NOT EXISTS $tokensTable (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            expires_at DATETIME NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_token (token),
            INDEX idx_expires_at (expires_at),
            CONSTRAINT fk_password_reset_user FOREIGN KEY (user_id) REFERENCES $usersTable(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",
    'sqlite' => "
        CREATE TABLE IF NOT EXISTS $tokensTable (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            token TEXT NOT NULL UNIQUE,
            expires_at TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES $usersTable(id) ON DELETE CASCADE
        );
        CREATE INDEX IF NOT EXISTS idx_token ON $tokensTable(token);
        CREATE INDEX IF NOT EXISTS idx_expires_at ON $tokensTable(expires_at);
    ",
    default => throw new Exception("Unsupported database driver: $driver")
};

if ($driver === 'sqlite') {
    foreach (explode(';', $sql) as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
} else {
    $pdo->exec($sql);
}

echo "Migration 006 completed successfully!\n";
