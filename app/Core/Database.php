<?php
// /app/Core/Database.php
// Classe de conexão com o banco (SQLite ou MySQL), usando Singleton.

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static ?array $cachedConfig = null;
    private static ?string $cachedTablePrefix = null;

    /**
     * Permite limpar cache e reconectar usando novo config (ex: após instalador).
     */
    public static function refresh(): void
    {
        self::$instance = null;
        self::$cachedConfig = null;
        self::$cachedTablePrefix = null;
    }

    /**
     * Retorna a instância única de PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }

        return self::$instance;
    }

    public static function tablePrefix(): string
    {
        if (self::$cachedTablePrefix !== null) {
            return self::$cachedTablePrefix;
        }

        $config = self::getConfig();
        $prefix = (string)($config['database']['table_prefix'] ?? '');
        if ($prefix !== '' && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $prefix)) {
            $prefix = '';
        }

        self::$cachedTablePrefix = $prefix;
        return self::$cachedTablePrefix;
    }

    public static function table(string $name): string
    {
        $prefix = self::tablePrefix();
        return $prefix . $name;
    }

    public static function getDriver(): string
    {
        $config = self::getConfig();
        return $config['database']['driver'] ?? 'sqlite';
    }

    /**
     * Faz a conexão de acordo com o driver configurado
     */
    private static function connect(): PDO
    {
        $config = self::getConfig();
        $db     = $config['database'];

        $driver = $db['driver'] ?? 'sqlite';

        try {
            if ($driver === 'sqlite') {
                return self::connectSQLite($db['sqlite']);
            }

            if ($driver === 'mysql') {
                return self::connectMySQL($db['mysql']);
            }

            throw new \Exception("Driver de banco inválido: {$driver}");

        } catch (PDOException $e) {
            error_log('Erro ao conectar ao banco: ' . $e->getMessage());
            http_response_code(500);
            exit('Erro ao conectar ao banco.');
        }
    }

    private static function getConfig(): array
    {
        if (self::$cachedConfig !== null) {
            return self::$cachedConfig;
        }
        self::$cachedConfig = require BASE_PATH . '/config.php';
        return self::$cachedConfig;
    }

    /**
     * Conexão SQLite
     */
    private static function connectSQLite(array $config): PDO
    {
        $dsn = "sqlite:" . $config['database'];

        $pdo = new PDO($dsn);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec("PRAGMA busy_timeout = 5000;");
        $pdo->exec("PRAGMA journal_mode = DELETE;");

        return $pdo;
    }


    /**
     * Conexão MySQL
     */
    private static function connectMySQL(array $config): PDO
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        $pdo = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        // Collation opcional
        if (isset($config['collation'])) {
            $pdo->exec("SET NAMES {$config['charset']} COLLATE {$config['collation']}");
        }

        return $pdo;
    }
}
