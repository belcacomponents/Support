<?php

namespace Belca\Support;

abstract class AbstractConstants
{
    /**
     * Returns all constants of the used class.
     *
     * @return array
     */
    public static function getConstants()
    {
        $rc = new \ReflectionClass(get_called_class());

        return $rc->getConstants();
    }

    /**
     * Returns an array of constants defined in the called class.
     *
     * @return array
     */
    public static function getLastConstants()
    {
        $parentConstants = static::getParentConstants();

        $allConstants = static::getConstants();

        return array_diff($allConstants, $parentConstants);
    }

    /**
     * Returns all constants of parent classes.
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
     * Returns a value of the given named constant if it is exists,
     * else returns null.
     * This is safe method of call constants, in other way
     * when you calls to undefined constants you will catch an error.
     *
     * @param  string $const  The constant name
     * @return mixed
     */
    public static function getConst($const)
    {
        return defined("static::$const") ? constant("static::$const") : null;
    }

    /**
     * Checks whether a given named constant exists in the used class.
     *
     * @param  string $const  The constant name
     * @return bool
     */
    public static function isDefined($const)
    {
        return defined("static::$const");
    }
}
