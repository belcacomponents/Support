<?php

namespace Belca\Support;

/**
 * Статические функции для обработки HTML страницы и ее данных.
 */
class HTML
{
    /**
     * Удаляет теги указанного HTML контента. Данные внутри тегов остаются
     * нетронутыми. Разрешенные теги остаются нетронутыми.
     *
     * @param  string $html
     * @param  array  $allowedTags
     * @return string
     */
    public static function removeTags($html, $allowedTags = null)
    {
        $allowed = '';

        if (! empty($allowedTags) && is_array($allowedTags)) {
            foreach ($allowedTags as $key => $value) {
                $allowed .= '<'.$key.'>';
            }
        }

        // TODO необходимо очищать атрибуты тегов: функции, классы
        // Также необходимо закрывать открытие теги форматирования текста b, i и т.п.

        // https://www.php.net/manual/ru/function.strip-tags.php + комментарий с регулярным выражением
        // https://stackoverflow.com/questions/45437773/php-remove-all-html-tags-and-keep-plain-text-with-dom-parser
        // https://www.php.net/manual/ru/class.domnode.php#domnode.props.textcontent - можно использовать для манипулированием обработки

        return strip_tags($html, $allowed);
    }
}
