<?php

namespace Belca\Support;

/**
 * Static functions to handle arrays.
 */
class Arr
{
    /**
     * Удаляет лишние пробелы, табуляции, переносы в строковых значениях массива.
     * Ключи массива остаются в неизменном виде.
     * Если в качестве аргумента передан не массив, то будет возвращен пустой
     * массив.
     * Значения вложенных массивов не обрабатываются этой функцией.
     *
     * @param  array $array
     * @return array
     */
    public static function trim($array)
    {
        if (is_array($array)) {
            return array_map(function ($val) {
                return is_string($val) ? trim($val) : $val;
            }, $array);
        }

        return [];
    }

    /**
     * Удаляет пустые значения массива проверяя значения функции empty().
     * Ключи массива остаются в неизменном виде.
     * Если в качестве аргумента передан не массив, то будет возвращен пустой
     * массив.
     *
     * @param  array $array
     * @return array
     */
    public static function removeEmpty($array)
    {
        if (is_array($array)) {
            return array_filter($array, function ($value) {
                return (! empty($value));
            });
        }

        return [];
    }

    /**
     * Удаляет элементы массива со значением 'null' с помощью функции is_null().
     * Ключи массива остаются в неизменном виде.
     * Если в качестве аргумента передан не массив, то будет возвращен пустой
     * массив.
     *
     * @param  array $array
     * @return array
     */
    public static function removeNull($array)
    {
        if (is_array($array)) {
            return array_filter($array, function ($value) {
                return (! is_null($value));
            });
        }

        return [];
    }

    /**
     * Удаляет значения массива со значением 'null' или с другими значениями
     * не являющимися скалярными (скалярные значения: integer, float, string,
     * boolean). Ключи массива остаются в неизменном виде.
     * Если в качестве аргумента передан не массив, то в качестве ответа будет
     * возвращен пустой массив.
     *
     * @param  array $array
     * @return array
     */
    public static function removeNotScalar($array)
    {
        if (is_array($array)) {
            return array_filter($array, 'is_scalar');
        }

        return [];
    }

    /**
     * Рекурсивно удаляет пустые значения многомерного массива.
     * Если $resetIndex - true, то сбрасывает числовые ключи массива, в т.ч.,
     * которые были заданы вручную, а не автоматически присвоены при
     * инициализации массива.
     * Если в качестве значения будет указан не массив, то это значение
     * будет возвращено в неизменном виде.
     *
     * @param  mixed   $array
     * @param  boolean $resetIndex
     * @return mixed
     */
    public static function removeEmptyRecurcive($array, $resetIndex = true)
    {
        if (is_array($array) && count($array)) {
            foreach ($array as $key => &$value) {
                if (empty($value)) {
                    unset($array[$key]);
                } else {
                    if (is_array($value)) {
                        $value = self::removeEmptyRecurcive($value, $resetIndex);

                        if (empty($value)) {
                            unset($array[$key]);
                        }

                        // Если в массиве числовые ключи и необходимо сбросить их,
                        // то сбрасываем их.
                        elseif (self::isIntKeys($value) && $resetIndex) {
                            $value = array_values($value);
                        }
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Рекурсивно удаляет значения равные null в многомерном массиве.
     * Если $resetIndex - true, то сбрасывает числовые ключи во всех
     * внутренних массивах. Если обрабатываемом массиве есть хотя бы один
     * нечисловой ключ, то все ключи, в т.ч. числовые ключи обрабатываемого
     * массива, остаются неизменными.
     *
     * @param  array   $array
     * @param  boolean $resetIndex
     * @return array
     */
    public static function removeNullRecurcive($array, $resetIndex = true)
    {
        if (is_array($array) && count($array)) {
            foreach ($array as $key => &$value) {
                if (is_null($value)) {
                    unset($array[$key]);
                } else {
                    if (is_array($value)) {
                        $value = self::removeNullRecurcive($value, $resetIndex);

                        if (is_null($value)) {
                            unset($array[$key]);
                        }

                        // Если в массиве числовые ключи и необходимо сбросить их,
                        // то сбрасываем их.
                        elseif (self::isIntKeys($value) && $resetIndex) {
                            $value = array_values($value);
                        }
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Проверяет, числовые ли ключи в массиве. Может служить для проверки массива
     * на ассоциативность.
     * Возвращает true, только если все ключи являются числовыми. Пустой массив
     * не является числовым, т.к. его ключи и значения еще не определены.
     *
     * @param  array   $array
     * @return boolean
     */
    public static function isArrayWithIntKeys($array)
    {
        if (is_array($array) && count($array)) {
            for (reset($array); is_int(key($array)); next($array));

            return is_null(key($array));
        }

        return false;
    }

    /**
     * Синоним функции isArrayWithIntKey().
     *
     * @param  string  $array
     * @return boolean
     */
    public static function isIntKeys($array)
    {
        return self::isArrayWithIntKeys($array);
    }

    /**
     * Проверяет, является ли первое и последнее значения в массиве с
     * числовыми ключами.
     * Этот прстой алгоритм помогает определить ассоциативность массива.
     * Фактически это аналог функции isArrayWithIntKeys(), но в этом случае
     * все ключи переданного массива должны принадлежать одному или другому
     * типу (т.е. быть либо числовыми, либо строковыми).
     * При передачи пустого массива или не массива, результатом функции будет
     * false.
     *
     * @param  array    $array
     * @return boolean
     */
    public static function isFirstLastWithIntKeys($array)
    {
        if (! (is_array($array) && count($array))) {
            return false;
        }

        reset($array);
        $array_first_key = key($array);

        end($array);
        $array_last_key = key($array);

        return is_int($array_first_key) && is_int($array_last_key);
    }

    /**
     * Добавляет к указанному массиву массив новых ключей и значений в конец
     * массива. Если $replace - true, то все существующие значения с одинаковыми
     * ключами будут заменены на новые.
     * В отличие от array_merge() функция не создает и не возвращает новый
     * массив, а работает с исходным массивом.
     * В отличие от array_merge(), которая бы добавила значения массива к первому
     * массиву, если бы использовались числовые ключи, Arr::pushArray() будет
     * заменять и числовые значения с одинаковыми ключами, как если бы они были
     * ассоциативными значениями.
     *
     * @param  array   &$source
     * @param  array   $array
     * @param  boolean $replace
     * @return void
     */
    public static function concatArray(&$source, $array, $replace = true)
    {
        if (is_array($source) && is_array($array) && count($array)) {
            if ($replace) {
                $source = $array + $source;
            } else {
                $source += $array;
            }
        }
    }

    /**
     * Присоединяет к базовому массиву значения других массивов. Значения
     * со строковыми ключами будут заменяться, в случае совпадения,
     * а значения с числовыми ключами будут добавляться.
     *
     * @param  array &$source
     * @param  array ...$arrays
     * @return void
     */
    public static function pushArray(&$source, ...$array)
    {
        $source = array_merge($source, ...$array);
    }

    /**
     * Удаляет из массива вложенные массивы (подмассивы).
     * Если $resetIndex - true, то сбрасывает ключи массива.
     *
     * @param  array   $array
     * @param  boolean $resetIndex
     * @return array
     */
    public static function removeArrays($array, $resetIndex = false)
    {
        if (! (is_array($array) && count($array) >= 0)) {
            return [];
        }

        $array = array_filter($array, function ($value) {
            return ! is_array($value);
        });

        return $resetIndex ? array_values($array) : $array;
    }

    /**
     * Функция возвращает последний элемент массива. Не смотря на то, что в
     * функцию передается ссылка на массив, внутренний указатель массива
     * не сбивается.
     *
     * @param  array &$array
     * @return mixed
     */
    public static function last(&$array)
    {
        if (! is_array($array) && empty($array)) {
            return null;
        }

        return array_slice($array, -1, 1)[0];
    }

    /**
     * Удаляет указанные индексы и возвращает измененный массив с сокранением
     * индексов.
     *
     * @param  array $array
     * @param  mixed ...$indexes
     * @return array
     */
    public static function unset($array, ...$indexes)
    {
        if (empty($array)) {
            return [];
        }

        foreach ($indexes as $index) {
            if (is_array($index)) {
                self::unsetByReference($array, ...$index);
            } elseif (is_scalar($index)) {
                unset($array[$index]);
            }
        }

        return $array;
    }

    /**
     * Удаляет значения из массива по их индексам. Функция ничего не возвращает,
     * т.к. массив передается по ссылке.
     *
     * @param array &$array
     * @param mixed ...$indexes
     */
    public static function unsetByReference(&$array, ...$indexes) {
        if (is_array($array) && count($array) > 0) {
            foreach ($indexes as $index) {
                if (is_array($index)) {
                    self::unsetByReference($array, ...$index);
                } elseif (is_scalar($index)) {
                    unset($array[$index]);
                }
            }
        }
    }

    /**
     * Returns the first existing value or returns 'null'.
     *
     * @param  array ...$values
     * @return mixed|null
     */
    public function firstExists(...$values)
    {
        foreach ($values as $value) {
            if (isset($value)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Returns the first value which is not empty or returns 'null'.
     *
     * @param  array ...$values
     * @return mixed|null
     */
    public function firstNotEmpty(...$values)
    {
        foreach ($values as $value) {
            if (empty($value) === false) {
                return $value;
            }
        }

        return null;
    }
}
