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
     * @param  string $html        HTML контент
     * @param  array  $allowedTags Разрешенные теги
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

        // https://www.php.net/manual/ru/function.strip-tags.php + комментарий с регулярным выражением
        // https://stackoverflow.com/questions/45437773/php-remove-all-html-tags-and-keep-plain-text-with-dom-parser
        // https://www.php.net/manual/ru/class.domnode.php#domnode.props.textcontent - можно использовать для манипулированием обработки

        return strip_tags($html, $allowed);
    }

    /**
     * Сокращает указанный HTML контент до указанной длины строки. Если длина
     * контента превышает указанное значение, то добавляется значение $finish.
     *
     * @param  string  $html
     * @param  integer $limit
     * @param  string  $finish
     * @return string
     */
    public static function limit($html, $limit, $finish = '')
    {
        // TODO не учитываются теги и поэтому удаляется больше символов чем надо
        $trimmedHTML = str_limit($html, $limit, $finish);

        // Обрезать. Нормализовать. Добавить окончание внутри последнего тега.
        // Учитывать длину тегов или не учитывать

        $doc = new \DOMDocument();
        $doc->loadHTML($trimmedHTML);
        $doc->normalizeDocument();

        $childNodes = $doc->getElementsByTagName('body')->item(0)->childNodes;

        $innerHTML = '';

        for ($i = 0; $i < $childNodes->length; $i++) {
            $innerHTML .= $doc->saveHTML($childNodes->item($i));
        }

        return $innerHTML;
    }
}
