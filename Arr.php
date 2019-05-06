<?php

namespace Belca\Support;

/**
 * Статические функции для обработки массивов.
 */
class Arr
{
    /**
     * Соединяет значения двумерного массива с помощью разных соединителей.
     *
     * @param array  $array           Двумерный массив
     * @param array $chainLinks       Соединители внутренних значений массива
     * @param string $glue            Соединитель конечных значений массива
     * @return string
     */
    public static function doubleImplode($array = [], $chainLinks = ["", "", ""], $glue = "")
    {
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $key => $value) {
              if (!is_bool($value) && !is_array($value)) {
                $atrs[] = $chainLinks[0]. $key . $chainLinks[1] . $value . $chainLinks[2];
              }
              elseif ($value) {
                $atrs[] = $key;
              }
            }
        }

        return implode($glue, $atrs ?? []);
    }

    /**
     * Извлекает значение из многомерного массива с помощью разделителя.
     *
     * Пример использования.
     *
     * Исходный массив:
     * $array = [
     *    'finfo' => [
     *        'size' => 998,
     *        'mime' => 'image/png',
     *    ],
     * ];
     *
     * echo Arr::pullThroughSeparator($array, 'finfo.size'); // output: 998
     *
     *
     * @param  mixed  $array     Ассоциативный массив
     * @param  string $target    Путь к извлекаемому значению с разделителем
     * @param  string $separator Разделитель (по умолчанию '.')
     * @return mixed
     */
    public static function pullThroughSeparator($array, $target, $separator = '.')
    {
        if (! empty($array) && is_array($array) && is_string($target)) {

            $keys = explode($separator, $target);

            if (count($keys) > 0) {

                $itemKey = $array;

                foreach ($keys as $key) {
                    $itemKey = $itemKey[$key] ?? null;
                }

                return $itemKey;
            }
        }

        return null;
    }

    /**
     * Возвращает оригинальные ключи массива из двумерного массива, при условии,
     * что передан массив ключей.
     *
     * Пример №1.
     *
     * Исходный массив:
     * $properties = ['finfo.created', 'finfo.size' => 'filesize', 'finfo.mime' => 'mime'];
     *
     * $keys = Arr::originalKeys($properties);
     *
     * Output: ['finfo.created', 'finfo.size', 'finfo.mime']
     *
     *
     * Пример №2.
     *
     * Исходный массив:
     * $properties = ['finfo.created', 'finfo.size', 'finfo.mime'];
     *
     * $keys = Arr::originalKeys($properties);
     *
     * Output: ['finfo.created', 'finfo.size', 'finfo.mime']
     *
     * @param  mixed $array
     * @return array
     */
    public static function originalKeys($array)
    {
        if (is_array($array)) {
            $result = [];

            while (key($array) !== null) {
                $result[] = is_integer(key($array)) ? current($array) : key($array);

                next($array);
            }

            return $result;
        }

        return [];
    }
}
