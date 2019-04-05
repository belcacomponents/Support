<?php

namespace Belca\Support;

abstract class AbstractEnum
{
    /**
     * Возвращает все константы класса.
     *
     * @return array
     */
    static function getConstants()
    {
        $rc = new ReflectionClass(get_called_class());

        return $rc->getConstants();
    }
}
