<?php

namespace Belca\Support;

/**
 * Static functions to handle strings.
 */
class Str
{
    /**
     * Deletes repeated characters from a given string.
     *
     * @param  string  $string  A source string.
     * @param  string  $symbols A finding set of characters or a substring.
     * @param  int     $number  A minial number or a exact number of repeated.
     * @param  bool    $strict  If it is 'true', then deletes the exact number of repeated.
     * @return string
     */
    public static function removeDuplicateSymbols(string $string, string $symbols,
        int $number = 1, bool $strict = false): string
    {
        if (! is_int($number) || $number < 0) {
            $number = 1;
        }

        $pattern = '/('.preg_quote($symbols, '/').'){'.($number + 1).($strict ? '' : ',').'}/';

        return preg_replace($pattern, '$1', $string);
    }

    /**
     * Reduces a number of consecutive repeated characters to a given number.
     *
     * @param  string  $string
     * @param  string  $symbols
     * @param  int     $number
     * @return string
     */
    public static function reduceDuplicateSymbols(string $string, string $symbols, int $number = 1)
    {
        $number = $number < 1 ? 1 : $number;

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
    public static function normalizeFilePath(string $string)
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
     * @return string|null
     */
    public static function differenceSubstring(string $sourceString, string $secondString)
    {
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
     * @param  string   $haystack A source string.
     * @param  string   $needle   A value to find.
     * @param  int      $offset   A offset of the start position
     *                            from the end of the source string.
     * @param  bool     $fromEnd  If it is 'true', then returns the position
     *                            from the end of the source string.
     * @return int|null
     */
    public static function findPositionFromEnd(string $haystack, string $needle,
        int $offset = 0, bool $fromEnd = false)
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
     * Returns the first item a given chain.
     *
     * @param  string $chain     The chain of values joined by means of a separator.
     * @param  string $separator
     * @param  bool   $strict
     * @return string
     */
    public static function firstElementOfChain(string $chain,
        string $separator = '.', bool $strict = false)
    {
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
     * Returns the last item a given chain.
     *
     * @param  string $chain     The chain of values joined by means of a separator.
     * @param  string $separator
     * @param  bool   $strict
     * @return string
     */
    public static function lastElementOfChain(string $chain,
        string $separator = '.', bool $strict = false)
    {
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

    /**
     * Returns a given string wrapped into a given wrapper.
     *
     * @param  string $str
     * @param  string $wrapper
     * @return string
     */
    public function wrap(string $str, string $wrapper = '\''): string
    {
        return $wrapper.$str.$wrapper;
    }
}
