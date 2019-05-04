<?php

namespace Belca\Support;

class Config
{
    /**
     * Возвращает массив значений конфигурации по ключам конфигураци.
     *
     * @param  array $keys       Ассоциативный массив с именем возвращаемого ключа
     * и запрашиваемым ключем из файла конфигурации.
     * @param  array  $default   Значения по умолчанию, в случае отсутствия значений
     * конфигурации. Данные передаются в виде ассоциативного массива:
     * ключ - значение по умолчанию.
     * @return array
     */
    public static function getConfigArrayByConfigKeys($keys, $default = [])
    {
        $settings = [];

        foreach ($keys as $key => $value) {
            if (config($value)) {
                $settings[$key] = config($value);
            } elseif (isset($default[$key])) {
                $settings[$key] = $default[$key];
            }
        }

        return $settings;
    }
}
