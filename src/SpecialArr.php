<?php

namespace Belca\Support;

use Belca\Support\Sorting\OrderingByIndexRules;

/**
 * Static functions for handling special arrays.
 */
class SpecialArr
{
    /**
     * Возвращает оригинальные ключи массива, при условии, что передан массив
     * ключей, в том числе состоящий из ассоциативного массива,
     * где в качестве ключа используется оригинальный ключ, а в качестве
     * значения - подменный ключ (например, алиас).
     *
     * @param  array $array
     * @return array
     */
    public static function originalKeys($array)
    {
        if (is_array($array) && count($array)) {
            $result = [];

            foreach ($array as $key => $value) {
                $result[] = is_integer($key) ? $value : $key;
            }

            return $result;
        }

        return [];
    }

    /**
     * Извлекает значение из многомерного массива с помощью разделителя.
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

            if (count($keys)) {
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
     * Соединяет два массива по специальным правилам. Первый аргумент - основные
     * значения атрибутов, второй аргумент - значения атрибутов по умолчанию.
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
     * Упорядочивает и возвращает полученные ключи в соответствии с правилами
     * индекса.
     *
     * @param  array $keys
     * @param  array $indexes
     * @return array
     */
    public static function orderKeysByIndexRules($keys, $indexes)
    {
        $orderingByIndex = new OrderingByIndexRules();

        return $orderingByIndex->orderKeys($keys, $indexes);
    }
}
