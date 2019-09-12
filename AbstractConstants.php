<?php

namespace Belca\Support;

abstract class AbstractConstants
{
    /**
     * Возвращает все константы класса.
     *
     * @return array
     */
    public static function getConstants()
    {
        $rc = new \ReflectionClass(get_called_class());

        return $rc->getConstants();
    }

    /**
     * Возвращает массив констант определенные в вызываемом классе.
     *
     * @return array
     */
    public static function lastConstants()
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
    public static function getParentConstants()
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
    public static function getConst($const)
    {
        return defined ("static::$const") ? constant("static::$const") : null;
    }

    /**
     * Проверяет существование константы.
     *
     * @param  $string $const Имя константы
     * @return bool
     */
    public static function defined($const)
    {
        return defined("static::$const");
    }
}
