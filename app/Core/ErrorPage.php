<?php
// app/Core/ErrorPage.php
// Renderiza páginas de erro customizadas sem depender de layout ou banco.

declare(strict_types=1);

namespace App\Core;

class ErrorPage
{
    public static function render(int $code, string $message = ''): void
    {
        http_response_code($code);

        $errorCode = $code;
        $errorMessage = $message;

        $file = BASE_PATH . '/app/Views/errors/' . $code . '.php';

        if (is_file($file) && is_readable($file)) {
            require $file;
            return;
        }

        // Fallback simples para garantir resposta
        echo htmlspecialchars($message !== '' ? $message : "Erro {$code}", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
