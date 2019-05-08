# Support
Вспомогательные классы для компонентов Belca, составляющих основу Dios CMS.

# Constants

# Enum

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

# Array

doubleImplode($array = [], $chainLinks = ["", "", ""], $glue = "")
pullThroughSeparator($array, $target, $separator = '.')
originalKeys($array)

# Config

getConfigArrayByConfigKeys($keys, $default = [])

# Helpers

html_tag_attributes($attributes, $defaultModifiers)
