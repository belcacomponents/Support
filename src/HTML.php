<?php

namespace Belca\Support;

/**
 * Статические функции для обработки HTML страницы и ее данных.
 */
class HTML
{
    protected static $singleTags = [
        'area', 'base',
        'basefont', // deprecated
        'bgsound', // non-standard
        'br', 'col', 'command', 'doctype', 'embed', 'hr', 'img', 'input',
        'isindex', // deprecated
        'keygen', 'link', 'meta', 'param', 'source', 'track', 'xml', 'wbr',
    ];

    /**
     * Удаляет теги указанного HTML контента. Можно указывать разрешенные теги.
     * Если $clearTags - true, то атрибуты внутри тегов будут удалены, иначе
     * атрибуты будут нетронуты. Можно оставить только разрешенные атрибуты тегов.
     *
     * @param  string   $html
     * @param  array    $allowedTags
     * @param  boolean  $clearTags
     * @param  array    $allowedAttributes
     * @return string
     */
    public static function removeTags($html, $allowedTags = [], $clearTags = true, $allowedAttributes = [])
    {
        $allowed = '';

        if (! empty($allowedTags) && is_array($allowedTags)) {
            foreach ($allowedTags as $key => $value) {
                $allowed .= '<'.$key.'>';
            }
        }

        $html = strip_tags($html, $allowed);

        return $clearTags ? static::removeTagAttributes($html) : $html;
    }

    /**
     * Удаляет содержимое указанных тегов и сами теги. Удаляются данные от
     * начала тега до конца тега, не затрагивая окружающие пробелы, табуляции
     * и переносы строк.
     *
     * @param  string   $html
     * @param  array    $tags
     * @return string
     */
    public static function removeTagContent($html, $tags = [])
    {
        foreach ($tags as $tag) {
            if (in_array($tag, static::$singleTags)) {
                $html = preg_replace("#<[!?]?\s*?$tag\b[^>]*\/?>#si", '', $html);
            } else {
                $html = preg_replace("#<\s*?$tag\b[^>]*>(.*?)</$tag\b[^>]*>#si", '', $html);
            }
        }

        return $html;
    }

    /**
     * Удаляет одиночные теги, кроме разрешенных.
     *
     * @param  string   $html
     * @param  array    $allowed
     * @return string
     */
    public function removeSingleTags($html, $allowed = [])
    {
        $allowed = array_map('mb_strtolower', $allowed);
        $tags = array_diff(static::$singleTags, $allowed);

        return static::removeTagContent($html, $tags);
    }

    /**
     * Удаляет атрибуты тегов, оставляя разрешенные атрибуты.
     * При полной очистке тега от атрибутов создается новый атрибут
     * в формате HTML5, т.о. все теги будут в формате <tag>
     * без символа '/' в конце, даже одиночные теги.
     *
     * @param  string $html
     * @param  array  $allowedAttributes
     * @return string
     */
    public static function removeTagAttributes($html, $allowedAttributes = [])
    {
        preg_match_all("#<\s*?([\w]+)((\s+(([\w]+[\w\d-]*[\w\d]+)(=('[[:alnum:]\s;\#]*'|\"[[:alnum:]\s;\#]*\"|[[:alnum:]]*))?)+)+)\/?>#si", $html, $matches, PREG_SET_ORDER);

        if (count($matches)) {
            $tags = [];

            foreach ($matches as $index => $tag) {
                $tags[$index]['source_tag'] = $tag[0]; // Example: <a href='#link' class='className' id='identifier'>
                $tags[$index]['tag_name'] = $tag[1]; // Example: a
                $tags[$index]['source_attributes'] = $tag[2]; // Example: href='#link' class='className' id=''

                // Defines attributes
                if (mb_strlen($tags[$index]['source_attributes']) > 1) {
                    preg_match_all("#\s+(([\w]+[\w\d-]*[\w\d]+)(=('[[:alnum:]\s;\#]*'|\"[[:alnum:]\s;\#]*\"|[[:alnum:]]*))?)+#si", $tags[$index]['source_attributes'], $tags[$index]['matching_attributes'], PREG_SET_ORDER);

                    // Defines allowed attributes
                    if (count($tags[$index]['matching_attributes'])) {
                        foreach ($tags[$index]['matching_attributes'] as $attribute) {
                            $tags[$index]['attributes'][$attribute[2]]['source'] = $attribute[0];
                            $tags[$index]['attributes'][$attribute[2]]['normalized'] = $attribute[1];
                            $tags[$index]['attributes'][$attribute[2]]['name'] = $attribute[2];
                            $tags[$index]['attributes'][$attribute[2]]['value'] = $attribute[4] ?? null;
                        }
                    }

                    if (! empty($tags[$index]['attributes'])) {
                        // Finds allowed attributes
                        foreach ($tags[$index]['attributes'] as $attributeName => $values) {
                            if (in_array($attributeName, $allowedAttributes)) {
                                $tags[$index]['final_attributes'][$attributeName]['name'] = $attributeName;
                                $tags[$index]['final_attributes'][$attributeName]['normalized'] = $tags[$index]['attributes'][$attributeName]['normalized'];
                            }
                        }

                        // Makes a new tag with attributes
                        if (! empty($tags[$index]['final_attributes'])) {
                            $attributes = array_column($tags[$index]['final_attributes'], 'normalized');
                            $finalAttributes = implode(' ', $attributes);

                            $replacement = '<'.$tags[$index]['tag_name'].' '.$finalAttributes.'>';
                        } else {
                            $replacement = '<'.$tags[$index]['tag_name'].'>';
                        }

                        // Replaces the tag
                        $pattern = '#'.preg_quote($tags[$index]['source_tag'], '#').'#si';
                        $html = preg_replace($pattern, $replacement, $html);
                    }
                }
            }
        }

        return $html;
    }
}
