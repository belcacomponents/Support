<?php

namespace Belca\Support;

use Illuminate\Config\Repository as IlluminateConfig;

// WARNING: deprecated !!!

class Config
{
    /**
     * Get array of values from configuration (Illuminate\Config\Repository &
     * config/<file_name>.php) by configuration keys.
     *
     * @param  array $keys       Ассоциативный массив с именем возвращаемого ключа
     * и запрашиваемым ключем из файла конфигурации.
     * @param  array  $default   Значения по умолчанию, в случае отсутствия значений
     * конфигурации. Данные передаются в виде ассоциативного массива:
     * ключ - значение по умолчанию.
     * @return array
     */
    public static function getValuesFromConfigByConfigKeys($keys, $default = [])
    {
        $settings = [];

        if (is_array($keys) && count($keys)) {

            $config = new IlluminateConfig;

            foreach ($keys as $key => $value) {
                if ($config->has($value)) {
                    $settings[$key] = $config->get($value);
                } elseif (isset($default[$key])) {
                    $settings[$key] = $default[$key];
                }
            }
        } else {
            $settings = $default;
        }

        return $settings;
    }
}
