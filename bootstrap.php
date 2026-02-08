<?php
// bootstrap.php
// Responsável por inicializar a aplicação (sessão, erros, autoload, paths).

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

// Inicia a sessão (necessário para autenticação)
if (session_status() === PHP_SESSION_NONE) {
    $sessionLifetime = 60 * 60 * 24 * 3; // 3 dias
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    // Garante diretório de sessão válido (evita erro em C:/Windows/Temp)
    $sessionPath = BASE_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'sessions';
    if (!is_dir($sessionPath)) {
        @mkdir($sessionPath, 0777, true);
    }
    if (is_dir($sessionPath) && is_writable($sessionPath)) {
        ini_set('session.save_path', $sessionPath);
    }

    ini_set('session.gc_maxlifetime', (string) $sessionLifetime);
    session_set_cookie_params([
        'lifetime' => $sessionLifetime,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps, // em produção, forçar HTTPS
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// Content Security Policy para mitigar XSS
if (!headers_sent()) {
    header(
        "Content-Security-Policy: "
        . "default-src 'self'; "
        . "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://unpkg.com; "
        . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
        . "img-src 'self' data: blob: https:; "
        . "font-src 'self' https://fonts.gstatic.com data:; "
        . "connect-src 'self' https://unpkg.com; "
        . "object-src 'none'; "
        . "base-uri 'self'; "
        . "frame-ancestors 'self'; "
        . "upgrade-insecure-requests"
    );
}

// Exibir todos os erros em ambiente de desenvolvimento
// Em produção, você pode trocar para não exibir diretamente
$appEnv = getenv('APP_ENV') ?: 'development';
$isProduction = in_array(strtolower($appEnv), ['prod', 'production'], true);

error_reporting(E_ALL);
ini_set('display_errors', $isProduction ? '0' : '1');
ini_set('log_errors', '1');
if (!ini_get('error_log')) {
    ini_set('error_log', BASE_PATH . '/php-error.log');
}

// Carrega .env simples (KEY=VALUE) se existir
$envFile = BASE_PATH . '/.env';
if (is_file($envFile) && is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }
            $key = trim(substr($line, 0, $pos));
            $val = trim(substr($line, $pos + 1));
            $val = trim($val, " \t\n\r\0\x0B\"'");
            if ($key === '') {
                continue;
            }
            if (getenv($key) === false) {
                putenv($key . '=' . $val);
                $_ENV[$key] = $val;
            }
        }
    }
}

// Definições de caminhos principais
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/Views');
define('DB_SQLITE_PATH', BASE_PATH . '/database/database.sqlite');

// Autoloader simples para classes com namespace "App\"
// Ex: App\Core\Router → /app/Core/Router.php
spl_autoload_register(function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = APP_PATH . '/';
    $len     = strlen($prefix);

    // Se a classe não começa com "App\", ignora
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Remove o prefixo "App\" do nome da classe
    $relativeClass = substr($class, $len);

    // Converte namespace em caminho de arquivo
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Handlers globais de erro/exception para páginas amigáveis
set_exception_handler(function ($exception): void {
    try {
        \App\Core\Logger::error('Uncaught Exception', ['error' => (string) $exception]);
        if (!defined('CLI_MODE') || !CLI_MODE) {
            \App\Core\ErrorPage::render(500, 'Algo inesperado aconteceu. Estamos trabalhando nisso.');
        } else {
            echo "Error: " . $exception->getMessage() . "\n";
        }
    } catch (\Throwable $fallback) {
        if (!defined('CLI_MODE') || !CLI_MODE) {
            http_response_code(500);
        }
        echo 'Erro interno do servidor';
    }
});

set_error_handler(function (int $severity, string $message, ?string $file = null, ?int $line = null): bool {
    // Converte para exception para unificar fluxo
    throw new \ErrorException($message, 0, $severity, $file ?? '', $line ?? 0);
});

register_shutdown_function(function (): void {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        try {
            \App\Core\Logger::error('Fatal error', $error);
            if (!defined('CLI_MODE') || !CLI_MODE) {
                \App\Core\ErrorPage::render(500, 'Erro fatal.');
            } else {
                echo "Fatal error: " . $error['message'] . "\n";
            }
        } catch (\Throwable $fallback) {
            if (!defined('CLI_MODE') || !CLI_MODE) {
                http_response_code(500);
            }
            echo 'Erro fatal.';
        }
    }
});
