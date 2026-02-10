<?php

namespace App\Core;

use App\Helpers\ArrayHelper;

/**
 * Funções globais para uso em views
 * Incluir este arquivo no layout principal
 */

if (!function_exists('safe')) {
    /**
     * Acesso seguro a array com valor padrão
     * Uso: safe($data, 'key', 'default')
     */
    function safe($array, $key, $default = '')
    {
        if (!is_array($array)) {
            return $default;
        }
        return $array[$key] ?? $default;
    }
}

if (!function_exists('esc')) {
    /**
     * Escape seguro para HTML com null coalescing
     * Uso: esc($value, 'default')
     */
    function esc($value, $default = '')
    {
        if ($value === null || $value === false || $value === '') {
            return $default;
        }
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('num')) {
    /**
     * Formata número de forma segura
     * Uso: num($value, 2, '0,00')
     */
    function num($value, $decimals = 2, $default = '0,00')
    {
        if ($value === null || !is_numeric($value)) {
            return $default;
        }
        return number_format((float)$value, $decimals, ',', '.');
    }
}

if (!function_exists('path')) {
    /**
     * Acesso seguro a array aninhado
     * Uso: path($data, 'user.profile.name', 'default')
     */
    function path($array, $path, $default = '')
    {
        if (!is_array($array)) {
            return $default;
        }
        return ArrayHelper::getPath($array, $path, $default);
    }
}

if (!function_exists('ensure')) {
    /**
     * Garante que array tem todas as chaves esperadas
     * Uso: ensure($data, ['id', 'nome', 'email'])
     */
    function ensure($array, $expectedKeys, $defaultValue = null)
    {
        if (!is_array($array)) {
            return [];
        }
        return ArrayHelper::ensureKeys($array, $expectedKeys, $defaultValue);
    }
}
