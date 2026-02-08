<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\MiddlewareInterface;

class TermsMiddleware implements MiddlewareInterface
{
    /**
     * Verifica se o usuário já aceitou os termos.
     * Aqui usamos sessão (exemplo). Adapte para checar em tabela/coluna específica.
     */
    public static function handle(mixed $param = null): bool
    {
        if (!empty($_SESSION['accepted_terms'])) {
            return true;
        }

        $_SESSION['after_terms_redirect'] = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: /termos');
        return false;
    }
}
