<?php

namespace Belca\Support;

/**
 * Статические функции для обработки HTTP заголовков.
 */
class HTTP
{
    /**
     * Парсит строку HTTP заголовка 'HTTP_ACCEPT_LANGUAGE'. На выходе
     * возвращает массив.
     *
     * Example:
     * $languages = HTTP::parseAcceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");
     *
     * Output $languages:
     * [
     *    "ru-RU" => 1,
     *    "ru" => 0.8,
     *    "en-US" => 0.5,
     *    "en" => 0.3
     * ];
     *
     * @param  string $acceptLanguage
     * @return array
     */
    public static function parseAcceptLanguage($acceptLanguage)
    {
        $array = explode(',', $acceptLanguage);

        $languages = [];

        foreach ($array as $lang) {
            $langWithPriority = explode(';', $lang);  // 0 - lang, 1 - priority

            if (count($langWithPriority) == 1) {
                $languages[$langWithPriority[0]] = 1;
            } elseif (count($langWithPriority) == 2) {
                $languages[$langWithPriority[0]] = floatval(substr($langWithPriority[1], 2));
            }
        }

        return $languages;
    }
}
