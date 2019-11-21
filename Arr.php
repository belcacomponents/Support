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
     * @param  array   $array           Двумерный массив
     * @param  array   $chainLinks      Соединители внутренних значений массива
     * @param  string  $glue            Соединитель конечных значений массива
     * @return string
     */
    public static function doubleImplode($array = [], $chainLinks = ["", "", ""], $glue = "")
    {
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $key => $value) {
                if (! is_bool($value) && ! is_array($value)) {
                    $atrs[] = $chainLinks[0]. $key . $chainLinks[1] . $value . $chainLinks[2];
                } elseif ($value) {
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

    /**
     * Сливает два массива. Первый аргумент - основные значения атрибутов,
     * второй аргумент - значения атрибутов по умолчанию.
     *
     * Функция имеет возможности по установке значений по умолчанию,
     * дописыванию списков значений к текущему значению. Они применяются с
     * помощью второго атрибута ($modifiers).
     *
     * Особенности значений массива переменной $modifiers.
     *
     * Несмотря на то, что в массив можно передать любые значения, все же стоит
     * придерживаться реальных атрибутов тегов и их значений, которые могут
     * применяться к конкретным тегам.
     *
     *
     * Модификаторы ключей массива:
     *
     * 1. Префикс "_" (ключ _attributeName) - незименяемое значение атрибута тега.
     *
     * Указанное значение всегда устанавливается указанному атрибуту, если
     * не переопределено таким же входным параметром в списке значений
     * атрибутов (первый аргумент функции).
     *
     * Используется для задания значений по умолчанию, которые в 99% не будут
     * изменяться, но могут дополняться другими значениями с помощью других
     * модификаторов ключей.
     *
     * Например, при использовании методологии CSS БЭМ тег может иметь
     * следующий вид: <button class="uk-button uk-button-default"></button>,
     * где класс "uk-button" будет присутствовать во всех кнопках одного вида,
     * а класс "uk-button-default" всего лишь модификатор представления.
     *
     * Значение в массиве: ['_class' => 'uk-button ']
     *
     * Способ удаления значения: передать в первом аргументе массив со значением
     * '_tagName' => ''. В результате значение будет заменено пустой строкой,
     * но остальные правила будут применены.
     *
     * 2. Суффикс "_" (ключ attributeName_) - вычисляемое значение атрибута тега и
     * перечисляемые значения атрибутов тега.
     *
     * Используется для указания вычисляемых полей, т.е. поля зависят
     * от переменных используемых при вызове (вычисление значений происходит
     * до передачи в функцию).
     *
     * Также значением с таким ключом может быть массив перечисляемых значений,
     * которые будут объединяться после вычисления результата.
     *
     * Значения с таким атрибутом также могут иметь значения по умолчанию,
     * которые будут переопределены модификаторами или дополнены другими
     * значениями атрибутов тега.
     *
     * @param  array  $attributes Основной массив
     * @param  array  $modifiers  Массив по умолчанию
     * @return array
     */
    public static function mergeByRules($attributes, $modifiers = [])
    {
        // TODO данную функцию лучше вынести за пределы этого класса в ExtArr / SpecArr / HTML
        $atrs = [];

        if (! empty($attributes) && is_array($attributes)) {
            // Обработаем только атрибуты-модификаторы, чтобы переопределить
            // значения модификаторов по умолчанию
            foreach ($attributes as $key => $value) {
                if (substr($key, 0, 1) == '_' || substr($key, -1, 1) == '_') {
                    if (isset($modifiers[$key])) {
                        $modifiers[$key] = $value;
                    }

                    unset($attributes[$key]);
                }
            }
        }

        if (! empty($attributes) && is_array($attributes)) {
            // Обработаем только значения атрибутов и выполним обработку всех
            // модификаторов для конкретных ключей
            foreach ($attributes as $key => $value) {

                // Сохраняться будут только действительные значения. Если указан
                // null, то считается, что значение не должно быть установлено.
                if ($value != null) {

                    $atrs[$key] = '';

                    // Обязательные значения
                    if (isset($modifiers['_'.$key])) {
                        $atrs[$key] .= $modifiers['_'.$key];
                    }

                    // Вычисляемые и массовые значения
                    if (isset($modifiers[$key.'_'])) {
                        if (is_array($modifiers[$key.'_']) && count($modifiers[$key.'_'])) {
                            foreach ($modifiers[$key.'_'] as $value) {
                                if (is_string($value)) {
                                    $atrs[$key] .= $value;
                                }
                            }
                        } elseif (is_string($modifiers[$key.'_'])) {
                            $atrs[$key] .= $modifiers[$key.'_'];
                        }
                    }

                    // Присваиваем переданное значение, вместо значения по умолчанию
                    if (! array_key_exists($key, $modifiers) || (array_key_exists($key, $modifiers) && $modifiers[$key] != null)) {
                        $atrs[$key] .= $value; // WARNING: Значение должно быть массивом
                    }
                }

                // Удаляем все удаляемые значения, чтобы потом не выполнять их обработку
                unset($modifiers[$key], $modifiers[$key.'_'], $modifiers['_'.$key]);
            }
        }

        // Обработаем оставшиеся модификаторы
        if (! empty($modifiers) && count($modifiers) > 0) {
            foreach ($modifiers as $key => $value) {

                // Если это модификатор, то задаем значения модификатора.
                // Иначе это значение по умолчанию.
                if (substr($key, 0, 1) == '_' || substr($key, -1, 1) == '_') {

                    // В зависимости от типа модификатора выполняем обработку

                    // Модификатор обязательных значений
                    if (substr($key, 0, 1) == '_') {
                        if (empty($atrs[substr($key, 1)])) {
                            $atrs[substr($key, 1)] = '';
                        }

                        if (is_string($value)) {
                            $atrs[substr($key, 1)] .= $value;
                        }
                    }
                    // Другой - модификатор вычисляемых и массовых значений
                    else {
                        if (empty($atrs[substr($key, 0, -1)])) {
                            $atrs[substr($key, 0, -1)] = '';
                        }

                        if (is_array($value) && count($value)) {
                            foreach ($value as $val) {
                                if (is_string($val)) {
                                    $atrs[substr($key, 0, -1)] .= $val;
                                }
                            }
                        } elseif (is_string($value)) {
                            $atrs[substr($key, 0, -1)] .= $value;
                        }
                    }

                } else {

                    // Если это обычный атрибут, то значением может быть
                    // запрет на присвоение (используется при обработке входящих
                    // атрибутов) или значение по умолчанию.
                    if ($value != null) {
                        if (isset($atrs[$key])) {
                            $atrs[$key] .= $value;
                        } else {
                            $atrs[$key] = $value;
                        }

                    }
                }
            }
        }

        return $atrs;
    }

    /**
     * Удаляет лишние пробелы в строковых значениях массива.
     *
     * @param  array $array
     * @return array
     */
    public static function trim($array)
    {
        return array_map(function ($val) {
            return is_string($val) ? trim($val) : $val;
        }, $array);
    }

    /**
     * Удаляет пустые значения массива с помощью функции empty().
     *
     * @param  array $array
     * @return array
     */
    public static function removeEmpty($array)
    {
        return array_filter($array, function ($value) {
            return (! empty($value));
        });
    }

    /**
     * Удаляет значения массива null с помощью функции is_null().
     *
     * @param  array $array
     * @return array
     */
    public static function removeNull($array)
    {
        return array_filter($array, function ($value) {
            return (! is_null($value));
        });
    }

    /**
     * Удаляет значения массива со значением 'null' или пустой строкой.
     *
     * @param  array $array
     * @return array
     */
    public static function removeNotScalar($array)
    {
        return array_filter($array, 'is_scalar');
    }

    /**
     * Рекурсивно удаляет пустые значения многомерного массива.
     * Если $resetIndex - true, то сбрасывает числовые ключи массива.
     *
     * @param  array   $array
     * @param  boolean $resetIndex
     * @return array
     */
    public static function removeEmptyRecurcive($array, $resetIndex = true)
    {
        foreach ($array as $key => &$value) {
            if (empty($value)) {
                unset($array[$key]);
            } else {
                if (is_array($value)) {
                    $value = self::removeEmptyRecurcive($value);

                    if (empty($value)) {
                        unset($array[$key]);
                    }

                    // Если в массиве числовые ключи и необходимо сбросить их,
                    // то сбрасываем их.
                    elseif (self::isArrayWithIntKey($value) && $resetIndex) {
                        $value = array_values($value);
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Рекурсивно удаляет значения равные null в многомерном массиве.
     * Если $resetIndex - true, то сбрасывает числовые ключи массива.
     *
     * @param  array   $array
     * @param  boolean $resetIndex
     * @return array
     */
    public static function removeNullRecurcive($array, $resetIndex = true)
    {
        foreach ($array as $key => &$value) {
            if (is_null($value)) {
                unset($array[$key]);
            } else {
                if (is_array($value)) {
                    $value = self::removeNullRecurcive($value);

                    if (is_null($value)) {
                        unset($array[$key]);
                    }

                    // Если в массиве числовые ключи и необходимо сбросить их,
                    // то сбрасываем их.
                    elseif (self::isArrayWithIntKey($value) && $resetIndex) {
                        $value = array_values($value);
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Проверяет, числовые ли ключи в массиве.
     *
     * По текущему алгоритму числовым массивом является тот массив, где первый
     * и последние ключи элементов являются числом.
     *
     * @param  array  $array
     * @return boolean
     */
    public static function isArrayWithIntKey($array)
    {
        if (is_array($array)) {
            for (reset($array); is_int(key($array)); next($array));

            return is_null(key($array));
        }

        return false;
    }

    /**
     * Проверяет, является ли первое и последнее значение в массиве с
     * числовыми ключами. Данный простой алгоритм может послужить аналогом
     * функции isArrayWithIntKey(), при условии, что все ключи массива
     * будут принадлежать одному или другому типу.
     *
     * @param  array  $array
     * @return boolean
     */
    public static function isFirstLastWithIntKey($array)
    {
        reset($array);
        $array_first_key = key($array);

        end($array);
        $array_last_key = key($array);

        return is_int($array_first_key) && is_int($array_last_key);
    }

    /**
     * Добавляет к указанному массиву массив новых ключей и значений в конец
     * массива. Если $replace - true, то все существующие значения будут
     * заменены.
     * В отличии от array_merge() функция не создает и не возвращает новый
     * массив, а работает с исходным массивом.
     *
     * @param  array   $source
     * @param  array   $array
     * @param  boolean $replace
     * @return void
     */
    public static function pushArray(&$source, $array, $replace = true)
    {
        if (is_array($source) && is_array($array)) {
            foreach ($array as $key => $value) {

                if (! $replace) {
                    if (array_key_exists($key, $source)) {
                        break;
                    }
                }

                $source[$key] = $value;
            }
        }
    }

    /**
     * Удаляет в массиве значения, если они также являются массивами (вложенным
     * массивом или подмассивом). Если $resetIndex - true, то сбрасывает ключи
     * массива.
     *
     * @param  array   $array
     * @param  boolean $resetIndex
     * @return array
     */
    public static function removeArrays($array, $resetIndex = false)
    {
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                unset($array[$key]);
            }
        }

        return $resetIndex ? array_values($array) : $array;
    }
}
