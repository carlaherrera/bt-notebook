<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    public static function required(string $value): bool
    {
        return trim($value) !== '';
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function maxLength(string $value, int $length): bool
    {
        return mb_strlen($value) <= $length;
    }

    public static function oneOf(string $value, array $options): bool
    {
        return in_array($value, $options, true);
    }
}
