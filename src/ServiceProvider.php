<?php

namespace Belca\Support;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Foundation\AliasLoader;

abstract class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Рекурсивно заменяет и расширяет значения конфигурации.
     *
     * @param  string $path
     * @param  string $key
     * @return void
     */
    public function recurciveReplaceConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        if (file_exists($path)) {
            $array = require $path;

            if (is_array($array)) {
                $this->app['config']->set($key, array_replace_recursive($array, $config));
            }
        }
    }

    /**
     * Регистрирует расширяемый класс и задает ему алиас.
     * При успешной регистрации возвращает true.
     * Расширяемый класс должен использовать трейт \Belca\ClassExtender.
     *
     * @param  string   $alias
     * @param  string   $className
     * @return boolean
     */
    public function registerExtensibleClass($alias, $className)
    {
        $loader = AliasLoader::getInstance();

        if (class_exists($className) && ! in_array($className, $loader->getAliases())) {

            $key = is_string($alias) ? $alias : $className;
            $loader->alias($alias, $className);

            return true;
        }

        return false;
    }

    /**
     * Расширяет указанный класс указанными расширяемыми классами (расширителями).
     * В качестве класса для расширения может быть указан алиас класса, если
     * он был задан.
     *
     * @param  string    $className
     * @param  mixed     $extenders
     * @return array
     */
    public function extendClass($className, $extenders)
    {
        $result = [];
        $loader = AliasLoader::getInstance();

        $key = array_search($className, $loader->getAliases()) ?: key_exists($className, $loader->getAliases()) ? $className : null;

        if ($key) {
            if (is_string($extenders)) {
                $extenders = [$extenders];
            }

            if (is_array($extenders) && count($extenders) > 0) {
                foreach ($extenders as $classExtender) {
                    $extendedClass = $loader->getAliases()[$key];
                    $result[$key][] = [
                        'class' => $classExtender,
                        'state' => $extendedClass::addClass($classExtender),
                    ];
                }
            }
        }

        return $result;
    }
}
