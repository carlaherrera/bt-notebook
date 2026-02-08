<?php

declare(strict_types=1);

namespace App\Core;

interface MiddlewareInterface
{
    /**
     * Retorna true para continuar a requisição; false para interromper.
     * Pode receber parâmetro opcional (ex.: role exigida).
     */
    public static function handle(mixed $param = null): bool;
}
