<?php

namespace Belca\Support;

abstract class AbstractConstants
{
    /**
     * Возвращает все константы класса.
     *
     * @return array
     */
    static function getConstants()
    {
        $rc = new \ReflectionClass(get_called_class());

        return $rc->getConstants();
    }

    /**
     * Возвращает массив констант определенные в вызываемом классе.
     *
     * @return array
     */
    static function lastConstants()
    {
        $parentConstants = static::getParentConstants();

        $allConstants = static::getConstants();

        return array_diff($allConstants, $parentConstants);
    }

    /**
     * Возвращает все константы родительских классов
     *
     * @return array
     */
    static function getParentConstants()
    {
        $rc = new \ReflectionClass(get_parent_class(static::class));
        $consts = $rc->getConstants();

        return $consts;
    }

    /**
     * Возвращает значение указанной константы, если она существует. Иначе возвращает
     * null.
     * Это безопасный метод вызова констант, т.к. при обращении к несуществующей
     * константе вы получите ошибку PHP.
     *
     * @param  string $const Имя константы
     * @return mixed
     */
    static function getConst($const)
    {
        return defined ("static::$const") ? constant("static::$const") : null;
    }

    /**
     * Проверяет существование константы.
     *
     * @param  $string $const Имя константы
     * @return bool
     */
    static function defined($const)
    {
        return defined("static::$const");
    }
}
