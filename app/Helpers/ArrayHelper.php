<?php

namespace App\Helpers;

class ArrayHelper
{
    /**
     * Acesso seguro a arrays com valor padrão
     * 
     * @param array $array
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(array $array, $key, $default = null)
    {
        return $array[$key] ?? $default;
    }

    /**
     * Acesso seguro a arrays aninhados
     * 
     * @param array $array
     * @param string $path (ex: 'user.profile.name')
     * @param mixed $default
     * @return mixed
     */
    public static function getPath(array $array, string $path, $default = null)
    {
        $keys = explode('.', $path);
        $value = $array;

        foreach ($keys as $key) {
            if (!is_array($value) || !isset($value[$key])) {
                return $default;
            }
            $value = $value[$key];
        }

        return $value;
    }

    /**
     * Garante que array tem todas as chaves esperadas
     * 
     * @param array $data
     * @param array $expectedKeys
     * @param mixed $defaultValue
     * @return array
     */
    public static function ensureKeys(array $data, array $expectedKeys, $defaultValue = null): array
    {
        $template = array_fill_keys($expectedKeys, $defaultValue);
        return array_merge($template, $data);
    }

    /**
     * Filtra array para manter apenas chaves esperadas
     * 
     * @param array $data
     * @param array $allowedKeys
     * @return array
     */
    public static function filterKeys(array $data, array $allowedKeys): array
    {
        return array_intersect_key($data, array_flip($allowedKeys));
    }
}
