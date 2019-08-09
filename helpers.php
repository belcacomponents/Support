<?php

use Belca\Support\Arr;
use Belca\Support\Str;

if (! function_exists('html_tag_attributes')) {
    /**
     * Объединяет два массива атрибутов (новый и по умолчанию) с помощью
     * функции \Belca\Suppurt\Arr::mergeByRules() и конвертирует массив
     * атрибутов HTML тега в строку атрибутов HTML тега.
     *
     * @param  array   $attributes   Массив атрибутов в виде "ключ => значение"
     * @param  array   $modifiers    Массив значений по умолчанию, переопределяющих значений и/или дополняющих значений
     * @param  boolean $trim         Удаляет лишние пробелы в значениях массива
     * @param  boolean $removeEmpty  Удаляет нулевые значения массива
     * @return string
     */
    function html_tag_attributes($attributes = [], $modifiers = [], $trim = true, $removeEmpty = true)
    {
        $atrs = Arr::mergeByRules($attributes, $modifiers);

        // Убираем все лишние пробелы, чтобы значения атрибутов не имели лишних пробелов
        if ($trim) {
            $atrs = Arr::trim($atrs);
        }

        // Удаляем все пустые значения, чтобы не было пустых атрибутов в тегах
        if ($removeEmpty) {
            $atrs = Arr::removeNull($atrs);
        }

        return Arr::doubleImplode($atrs, ["", "=\"", "\""], " ");;
    }
}

if (! function_exists('js_array')) {
    /**
     * Объединяет два массива атрибутов (новый и по умолчанию) с помощью
     * функции \Belca\Suppurt\Arr::mergeByRules() и конвертирует массив
     * атрибутов HTML тега в строку атрибутов HTML тега.
     *
     * @param  array   $attributes   Массив атрибутов в виде "ключ => значение"
     * @param  boolean $removeEmpty  Удаляет нулевые значения массива
     * @return string
     */
    function js_array($attributes = [], $removeEmpty = true) {

        // Удаляем все пустые значения, чтобы не было пустых атрибутов в тегах
        if ($removeEmpty) {
            $attributes = Arr::removeEmpty($attributes);
        }

        foreach ($attributes as $value) {
            $result[] = "\"".$value."\"";
        }

        return htmlspecialchars_decode('['.implode(',', $result).']');
    }
}

if (! function_exists('merge_attributes')) {
    /**
     * Сливает два массива атрибутов HTML тега с помощью функции \Belca\Suppurt\Arr::mergeByRules().
     *
     * @param  array  $attributes Основной массив
     * @param  array  $modifiers  Значения по умолчанию
     * @return array
     */
    function merge_attributes($attributes = [], $modifiers = [])
    {
        return Arr::mergeByRules($attributes, $modifiers);
    }
}
