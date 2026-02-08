<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Auth;
use App\Core\MiddlewareInterface;

class RoleMiddleware implements MiddlewareInterface
{
    /**
     * Verifica se o usuário autenticado possui o papel exigido (string ou pipe "admin|super").
     * Se não estiver autenticado ou não tiver a role, responde 403.
     */
    public static function handle(mixed $roles = null): bool
    {
        if (!Auth::check()) {
            header('Location: /entrar');
            return false;
        }

        $rolesString = is_string($roles) ? $roles : '';
        $required = array_filter(array_map('trim', explode('|', $rolesString)));
        $userRole = Auth::role() ?? '';

        if (in_array($userRole, $required, true)) {
            return true;
        }

        http_response_code(403);
        echo 'Acesso negado';
        return false;
    }
}
