<?php

declare(strict_types=1);

namespace App\Core;

class Flash
{
    private const KEY = '_flash';

    public static function set(string $type, string $message): void
    {
        $_SESSION[self::KEY] = ['type' => $type, 'message' => $message];
    }

    public static function get(): ?array
    {
        if (!isset($_SESSION[self::KEY])) {
            return null;
        }
        $data = $_SESSION[self::KEY];
        unset($_SESSION[self::KEY]);
        return $data;
    }
}
