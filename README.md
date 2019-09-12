# Support

Вспомогательные классы для компонентов Belca, составляющих основу Dios System. Могут использоваться независимо от Belca, Dios System, Illuminate и Laravel.

# Constants & Enum

# Array

Uses Belca\Arr class.

Arr::doubleImplode($array = [], $chainLinks = ["", "", ""], $glue = "")
Arr::pullThroughSeparator($array, $target, $separator = '.')
Arr::originalKeys($array)
Arr::mergeByRules($attributes, $modifiers)
Arr::trim($array)
Arr::removeEmpty($array)
Arr::removeNull($array)
Arr::removeEmptyRecurcive($array, $resetIndex = true)
Arr::removeNullRecurcive($array, $resetIndex = true)
Arr::isArrayWithIntKey($array)
Arr::isFirstLastWithIntKey($array)
Arr::pushArray(&$source, $array, $replace = true)

# Config (depends on Laravel: Illuminate\Config\Repository)

Uses Belca\Config class and depends on Illuminate\Config\Repository.

Config::getValuesFromConfigByConfigKeys($keys, $default = [])

# HTML

Uses Belca\HTML class.

# HTTP

Uses Belca\HTTP class.

HTTP::parseAcceptLanguage($acceptLanguage)

# String

Uses Belca\Str class.

Str::removeDuplicateSymbols($string, $symbol, $number = 2, $stict = false)
Str::reduceDuplicateSymbols($string, $symbol, $number = 1)
Str::normalizeFilePath($string)
Str::differenceSubstring($sourceString, $secondString, $startLeft = true)
Str::firstElementChain($chain, $separator = '.')
Str::lastElementChain($chain, $separator = '.')

# ServiceProvider (depends on Laravel: Illuminate\Support\ServiceProvider, Illuminate\Foundation\AliasLoader)

Extends Illuminate\Support\ServiceProvider class with Belca\Support\ServiceProvider class.

$this->recurciveReplaceConfigFrom($path, $key)
$this->registerExtensibleClass($alias, $className)

# Helpers

html_tag_attributes($attributes, $defaultModifiers)

Где используются эти имена и когда необходимо вызывать последние константы (в миграциях БД).

namespace App\Models\User;

class NewRoles extends Roles
{
    const DEFAULT = self::ROOT;

    const VIEWER = 'viewer';
    const CHECKER = 'checker';
    const ROOT = 'rooter';
}

namespace App\Models\User;

use \Belca\User\Role\Roles as UserRoles;

class Roles extends UserRoles
{
    const DEFAULT = self::CLIENT;

    const CLIENT = 'client';
    const MODERATOR = 'moderator';
    const SUPERMODERATOR = 'super'.self::USER;
}


Информация о пользователях
  <br><br>
  Все константы: {{ implode(', ', (\Belca\User\Role\Roles::getConstants())) }}
  <br><br>
  Значение по умолчанию через функцию: {{ (\Belca\User\Role\Roles::getDefault()) }}
  <br><br>
  Значение по умолчанию через название константы: {{ (\Belca\User\Role\Roles::DEFAULT) }}
  <br><br>
  Значение константы по имени: {{ (\Belca\User\Role\Roles::DEFAULT) }}
  <br><br>
  Последние константы: {{ implode(', ', \Belca\User\Role\Roles::lastConstants()) }}
  <br><br>
  <hr>
  Переопределение классов
  <br><br>
  Все константы: {{ implode(', ', (\App\Models\User\Roles::getConstants())) }}
  <br><br>
  Значение по умолчанию через функцию: {{ (\App\Models\User\Roles::getDefault()) }}
  <br><br>
  Значение по умолчанию через название константы: {{ (\App\Models\User\Roles::DEFAULT) }}
  <br><br>
  Последние константы: {{ implode(', ', \App\Models\User\Roles::lastConstants()) }}
  <br><br>
  <hr>
  Еще наследование
  <br><br>
  Все константы: {{ implode(', ', (\App\Models\User\NewRoles::getConstants())) }}
  <br><br>
  Значение по умолчанию через функцию: {{ (\App\Models\User\NewRoles::getDefault()) }}
  <br><br>
  Значение по умолчанию через название константы: {{ (\App\Models\User\NewRoles::DEFAULT) }}
  <br><br>
  Последние константы: {{ implode(', ', \App\Models\User\NewRoles::lastConstants()) }}
  <br><br>
