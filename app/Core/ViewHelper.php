<?php

namespace App\Core;

use App\Helpers\ArrayHelper;

/**
 * Helper para views com acesso seguro a dados
 */
class ViewHelper
{
    /**
     * Acesso seguro a variáveis de view
     */
    public static function get($key, $default = null)
    {
        global $__view_data;
        return $__view_data[$key] ?? $default;
    }

    /**
     * Acesso seguro com caminho aninhado
     */
    public static function getPath($path, $default = null)
    {
        global $__view_data;
        return ArrayHelper::getPath($__view_data, $path, $default);
    }

    /**
     * Função helper para usar em views: safe($array, 'key', 'default')
     */
    public static function safe($array, $key, $default = '')
    {
        if (!is_array($array)) {
            return $default;
        }
        return $array[$key] ?? $default;
    }

    /**
     * Função helper para htmlspecialchars seguro
     */
    public static function escape($value, $default = '')
    {
        if ($value === null || $value === false) {
            return $default;
        }
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Função helper para número formatado seguro
     */
    public static function number($value, $decimals = 2, $default = '0,00')
    {
        if ($value === null || !is_numeric($value)) {
            return $default;
        }
        return number_format((float)$value, $decimals, ',', '.');
    }
}
