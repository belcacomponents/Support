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
     * @param  string  $string  Исходная строка
     * @param  string  $symbols Проверяемые символы
     * @param  integer $number  Минимальное число повторений или точное число повторений
     * @param  boolean $strict  Если true, то удалит точное число повторений.
     * @return string
     */
    public static function removeDuplicateSymbols($string, $symbols, $number = 1, $strict = false)
    {
        if (! is_int($number) || $number < 0) {
            $number = 1;
        }

        $pattern = '/('.preg_quote($symbols, '/').'){'.($number + 1).($strict ? '' : ',').'}/';

        return preg_replace($pattern, '$1', $string);
    }

    /**
     * Уменьшает количество повторений подряд идущих символов до указанного
     * значения.
     *
     * @param  string  $string
     * @param  string  $symbols
     * @param  integer $number
     * @return string
     */
    public static function reduceDuplicateSymbols($string, $symbols, $number = 1)
    {
        if (! is_int($number) || $number < 1) {
            $number = 1;
        }

        $pattern = '/('.preg_quote($symbols, '/').'){'.($number + 1).',}/';

        return preg_replace_callback($pattern, function ($matches) use ($symbols, $number) {
            if (is_string(current($matches))) {
                return str_pad($symbols, $number * mb_strlen($symbols), $symbols);
            }
        }, $string);
    }

    /**
     * Удаляет лишние слеши (символ '/') из строки и возвращает новую строку.
     *
     * @param  string $string
     * @return string
     */
    public static function normalizeFilePath($string)
    {
        return Str::reduceDuplicateSymbols($string, '/');
    }

    /**
     * Возвращает разницу строк, если строка является подстрокой другой
     * строки, иначе выводит null.
     * Порядок передачи строк не имеет значения.
     * Если передано значение отличное от строки, то возвращает null.
     *
     * @param  string $sourceString
     * @param  string $secondString
     * @return string
     */
    public static function differenceSubstring($sourceString, $secondString)
    {
        if (! (is_string($sourceString) || is_string($secondString))) {
            return null;
        }

        if (mb_strlen($sourceString) > mb_strlen($secondString)) {
            $haystack = $sourceString;
            $needle = $secondString;
        } else {
            $haystack = $secondString;
            $needle = $sourceString;
        }

        // The string $haystack always is longer than $needle.
        if (mb_strlen($haystack) == 0 || mb_strlen($needle) == 0) {
            return $haystack;
        }

        $pos = mb_strpos($haystack, $needle);

        if ($pos === false) {
            return null;
        }

        return mb_substr($haystack, (mb_strlen($needle)));
    }

    /**
     * Ищет с конца строки и возвращает позицию вхождения подстроки. Если
     * подстрока не найдена, то возвращается 'null'.
     *
     * @param  string   $haystack Исходная строка.
     * @param  string   $needle   Значение для поиска.
     * @param  integer  $offset   Смещение позиции поиска от конца строки.
     * @param  boolean  $fromEnd  Если 'true', то возвращает позицию отсчитывая с конца (значение -1 - последний элемент).
     * @return integer|null
     */
    public static function findPositionFromEnd($haystack, $needle, $offset = 0, $fromEnd = false)
    {
        $haystackLength = mb_strlen($haystack);
        $needleLength = mb_strlen($needle);

        if ($haystackLength < $needleLength || $needleLength == 0) {
            return null;
        }

        $position = $haystackLength - $offset - $needleLength;

        do {
            $substring = mb_substr($haystack, $position, $needleLength);

            if (strcmp($substring, $needle) == 0) {
                return $fromEnd ? ($haystackLength - $position) * (-1): $position;
            }

            $position--;
        } while ($position >= 0);

        return null;
    }

    /**
     * Возвращает первый элемент указанной цепочки.
     *
     * @param  string $chain     Строка со значениями объединенными через разделитель (цепочка значений)
     * @param  string $separator Разделитель
     * @return string
     */
    public static function firstElementOfChain($chain, $separator = '.', $strict = false)
    {
        // Deprecated
        /*$array = explode($separator, $chain);

        if (empty($array[0]) && isset($array[1])) {
            return $array[1];
        }

        return isset($array[0]) ? $array[0] : null;*/

        $finishPosition = null;
        $length = mb_strlen($chain);
        $element = null;

        do {
            $startPosition = $finishPosition === null ? 0 : $finishPosition + mb_strlen($separator);
            $finishPosition = mb_strpos($chain, $separator, $startPosition);

            // If the first element not found, then the last element
            // of the chain will be returned.
            if ($finishPosition === false) {
                return mb_substr($chain, $startPosition, $length);
            }

            if ($startPosition < $finishPosition) {
                $element = mb_substr($chain, $startPosition, $finishPosition - $startPosition);
            }

            // If the strict is active, then the first element of the chain
            // or 'null' well be returned.
            if ($strict) {
                return $element;
            }

        } while ($element == null && $length > $finishPosition);

        return $element;
    }

    /**
     * Возвращает последний элемент указанной цепочки.
     *
     * @param  string $value     Строка со значениями объединенными через разделитель
     * @param  string $separator Разделитель
     * @return string
     */
    public static function lastElementOfChain($chain, $separator = '.', $strict = false)
    {
        // Deprecated
        /*$array = explode($separator, $chain);

        return is_string($chain) ? end($array) : null;*/

        $length = mb_strlen($chain);
        $separatorLength = mb_strlen($separator);

        do {
            $offset = isset($offset) ? $offset + $separatorLength : 0;
            $startPosition = Str::findPositionFromEnd($chain, $separator, $offset);

            // If the first element not found, then the length of substring
            // calced without the position of start of string
            // and the length of separator. The position of start is zero.
            if ($startPosition === null) {
                $substringLength = $length - $offset;
                $startPosition = 0;
            } else {
                $substringLength = $length - $offset - $startPosition - $separatorLength;
                $startPosition += $separatorLength;
            }

            $element = mb_substr($chain, $startPosition, $substringLength);

            // If the strict is active, then the first element of the chain
            // or 'null' well be returned.
            if ($strict) {
                return $element;
            }

        } while (($element == null || $element == $separator) && $offset <= $length);

        return $element;

    }
}
