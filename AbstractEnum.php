<?php

namespace Belca\Support;

abstract class AbstractEnum extends AbstractConstants
{
    const DEFAULT = null;

    /**
     * Возвращает все константы класса без значения по умолчанию.
     *
     * @return array
     */
    public static function getConstants()
    {
        $rc = new \ReflectionClass(get_called_class());
        $consts = $rc->getConstants();

        unset($consts['DEFAULT']);

        return $consts;
    }

    /**
     * Возвращает все константы родительских классов без значения по умолчанию.
     *
     * @return array
     */
    public static function getParentConstants()
    {
        $rc = new \ReflectionClass(get_parent_class(static::class));
        $consts = $rc->getConstants();

        unset($consts['DEFAULT']);

        return $consts;
    }

    /**
     * Возвращает последнюю константу по умолчанию.
     *
     * @return mixed
     */
    public static function getDefault()
    {
        return static::DEFAULT;
    }
}
