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
        if (! is_int($number) || $number < 1) {
            $number = 2;
        }

        return preg_replace('/(\\'.$symbol.'){'.($number).($stict ? '' : ',').'}/', '$1', $string);
    }

    /**
     * Уменьшает количество повторений подряд идущих символов до указанного
     * значения.
     *
     * @param  string  $string
     * @param  string  $symbol
     * @param  integer $number
     * @return string
     */
    public static function reduceDuplicateSymbols($string, $symbol, $number = 1)
    {
        if (! is_int($number) || $number < 1) {
            $number = 1;
        }

        return preg_replace('/(\\'.$symbol.'){'.($number + 1).',}/', '$1', $string);
    }

    /**
     * Удаляет лишние '/' из строки и возвращает новую строку.
     *
     * @param  string $string
     * @return string
     */
    public static function normalizeFilePath($string)
    {
        return Str::reduceDuplicateSymbols($string, '/');
    }

    /**
     * Возвращает разницу подстроки, если строка является подстрокой другой
     * строки. Иначе выводит false.
     * Порядок передачи строк не имеет значения.
     * Если передано значение отличное от строки, то возвращает false.
     *
     * Пример:
     * $sourceString = '/home/dios/directory/';
     * $secondString = '/home/dios/directory/subdirecotry/filename';
     * // Output: subdirecotry/filename
     *
     * @param  string $sourceString
     * @param  string $secondString
     * @return string|boolean
     */
    public static function differenceSubstring($sourceString, $secondString, $startLeft = true)
    {
        if (! (is_string($sourceString) || is_string($secondString))) {
            return false;
        }

        if (mb_strlen($sourceString) > mb_strlen($secondString)) {
            $haystack = $sourceString;
            $needle = $secondString;
        } else {
            $haystack = $secondString;
            $needle = $sourceString;
        }

        $pos = mb_strpos($haystack, $needle);

        if ($pos === false) {
            return false;
        }

        return mb_substr($haystack, (mb_strlen($needle)));
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
