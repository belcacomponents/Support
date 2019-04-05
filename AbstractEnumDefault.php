<?php

namespace Belca\Support;

abstract class AbstractEnumDefault
{
    const __DEFAULT = null;

    /**
     * Возвращает все константы класса без значения по умолчанию.
     *
     * @return array
     */
    static function getConstants()
    {
        $rc = new \ReflectionClass(get_called_class());
        $consts = $rc->getConstants();

        unset($consts['__DEFAULT']);

        return $consts;
    }

    /**
     * Возвращает константу по умолчанию.
     *
     * @return mixed
     */
    static function getDefault()
    {
        return self::__DEFAULT;
    }
}
