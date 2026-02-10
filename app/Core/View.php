<?php
// /app/Core/View.php
// Responsável por renderizar Views, layouts e componentes.

declare(strict_types=1);

namespace App\Core;

// Carregar funções helper globais para acesso seguro a arrays
require_once __DIR__ . '/ViewFunctions.php';

class View
{
    /**
     * Renderiza uma view simples.
     * Exemplo: View::render('cliente/painel/index', ['nome' => 'Carla']);
     */
    public static function render(string $viewPath, array $data = []): void
    {
        // Transforma cada key do array em variável
        extract(self::withHelpers($data));

        // Caminho do arquivo da view
        $file = VIEW_PATH . '/' . $viewPath . '.php';

        if (!file_exists($file)) {
            http_response_code(500);
            error_log('View não encontrada: ' . $file);
            echo "Erro: View não encontrada";
            return;
        }

        require $file;
    }

    /**
     * Renderiza uma view usando um layout.
     * Exemplo:
     * View::layout('layouts/admin', 'admin/painel/index', $data);
     */
    public static function layout(string $layout, string $viewPath, array $data = []): void
    {
        // Transforma cada key do array em variável
        extract(self::withHelpers($data));

        // Layout principal
        $layoutFile = VIEW_PATH . '/' . $layout . '.php';
        // Conteúdo principal
        $viewFile = VIEW_PATH . '/' . $viewPath . '.php';

        if (!file_exists($layoutFile) || !file_exists($viewFile)) {
            http_response_code(500);
            $errorMsg = 'Layout ou View não encontrados: ' . $layoutFile . ' | ' . $viewFile;
            error_log($errorMsg);
            Logger::error('View layout não encontrado', ['layout' => $layoutFile, 'view' => $viewFile]);
            echo "Erro: Layout ou View não encontrados";
            return;
        }

        // Usa buffer para injetar view dentro do layout
        try {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            require $layoutFile;
        } catch (\Throwable $e) {
            ob_end_clean();
            http_response_code(500);
            Logger::error('Erro ao renderizar view', [
                'layout' => $layoutFile,
                'view' => $viewFile,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            echo "Erro ao renderizar página";
        }
    }

    /**
     * Escapa valor para saída HTML.
     */
    public static function esc(mixed $value): string
    {
        if ($value instanceof RawValue) {
            return (string) $value;
        }

        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Marca um valor como seguro para não ser escapado (ex.: HTML já sanitizado).
     */
    public static function raw(string $value): RawValue
    {
        return new RawValue($value);
    }

    /**
     * Injeta helpers nas variáveis da view: e()/esc() e raw().
     */
    private static function withHelpers(array $data): array
    {
        // Helpers disponíveis como variáveis ($e, $raw) e funções globais (e(), raw()).
        $data['e'] = $data['esc'] = fn(mixed $v) => self::esc($v);
        $data['raw'] = fn(string $v) => self::raw($v);

        // Funções globais idempotentes
        if (!function_exists('e')) {
            function e(mixed $v): string
            {
                return \App\Core\View::esc($v);
            }
        }
        if (!function_exists('raw')) {
            function raw(string $v): RawValue
            {
                return \App\Core\View::raw($v);
            }
        }

        return $data;
    }
}

/**
 * Wrapper para valores já sanitizados (saem sem escapar).
 */
class RawValue
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
