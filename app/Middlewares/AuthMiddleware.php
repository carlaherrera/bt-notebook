<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Auth;
use App\Core\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Garante que o usuário está autenticado. Se não, redireciona para /entrar.
     */
    public static function handle(mixed $param = null): bool
    {
        if (Auth::check()) {
            return true;
        }

        header('Location: /entrar');
        return false;
    }
}
