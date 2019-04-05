<?php

use Belca\Support\Arr;

if (! function_exists('html_tag_attributes')) {
    /**
     * Конвертирует массив в список атрибутов HTML тега.
     *
     * @param  array $attributes Массив атрибутов в виде "ключ => значение"
     * @return string
     */
    function html_tag_attributes($attributes = null)
    {
        return Arr::doubleImplode($attributes, ["", "=\"", "\""], " ");;
    }
}
