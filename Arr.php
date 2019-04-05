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
    static function doubleImplode($array = [], $chainLinks = ["", "", ""], $glue = "")
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
}
