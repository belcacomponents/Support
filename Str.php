<?php

namespace Belca\Support;

/**
 * Статические функции для обработки строк.
 */
class Str
{
    /**
     * Удаляет повторяющиеся символы из строки.
     *
     * @param  string  $string Исходная строка
     * @param  string  $symbol Проверяемый символ
     * @param  integer $number Число повторений
     * @param  boolean $stict  Если строгое сравнение, то удалит только конкретное число повторений
     * @return string
     */
    public static function removeDuplicateSymbols($string, $symbol, $number = 2, $stict = false)
    {
        return $string;
    }

    /**
     * Возвращает первый элемент указанной цепи.
     *
     * @param  string $value     Строка со значениями объединенными через разделитель
     * @param  string $separator Разделитель
     * @return string
     */
    public static function firstElementChain($chain, $separator = '.')
    {
        $array = explode($separator, $chain);

        return is_string($chain) ? reset($array) : null;
    }

    /**
     * Возвращает последний элемент указанной цепи.
     *
     * @param  string $value     Строка со значениями объединенными через разделитель
     * @param  string $separator Разделитель
     * @return string
     */
    public static function lastElementChain($chain, $separator = '.')
    {
        $array = explode($separator, $chain);

        return is_string($chain) ? end($array) : null;
    }
}
