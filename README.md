# Support - вспомогательные функции PHP

> Документация актуальна для версии v0.10.

Вспомогательные классы и их функции могут быть использованы в любом PHP-проекте.

Вспомогательные классы используются в компонентах Belca.

||||
|--|--|--|
|[Constants & Enum](#constants)|[Arrays](#arrays)|[Special arrays](#special-arrays)|
|[HTML](#html)|[HTTP](#HTTP)|[Strings](#strings)|
|[ServiceProvider](#service-provider)|||

## <a name="constants"></a> Константы и перечисления (Constants & Enum)

Именованные константы и перечисления используются для неизменяемых значений. Классы ниже содержат функции для извлечения списка констант и для получения констант без выброса исключений.

|||
|--|--|
|[AbstractConstants](#abstract-constants)|[AbstractEnum](#abstract-enum)|

### <a name="abstract-constants"></a> AbstractConstants

AbstractConstants - абстрактный класс для реализации списка именованных констант и получения их значений.

|Имена функций|Описание функций|
|--|--|
|getConstants()|Возвращает все константы используемого класса.|
|getLastConstants()|Возвращает массив констант определенных в вызываемом классе и не возвращает константы родительских классов.|
|getParentConstants()|Возвращает все константы родительских классов.|
|getConst($const)|$const - имя константы.<br/><br/> Возвращает значение указанной константы, если оно существует, иначе возвращает *null*.<br/> Это безопасный метод вызова констант, т.к. вызывая константу другим способом вы можете получить ошибку, если контанта не существует.|
|isDefined($const)|$const - имя константы. <br/><br/> Проверяет существование константы в используемом классе.|

Возможно вы будите использовать только один класс констант, и тогда он будет примерно таким, как показано ниже.

```php
use Belca\Support\AbstractConstants;

class FirstConstants extends AbstractConstants
{
    const USER = 'user';
    const SUPERUSER = 'superuser';
    const CLIENT = 'client';
    const MODERATOR = 'moderator';
    const SUPERMODERATOR = 'super'.self::USER;
}
```

В классе `FirstConstants` мы объявили все необходимые нам константы. Спустя какое-то время у нас появилась необходимость добавить еще констант. Мы можем это осуществить в том же классе, но если мы разрабатываем пакет, то у нас это будет выглядеть так, как показано ниже.

```php
class SecondConstants extends FirstConstants
{
    const VIEWER = 'viewer';
    const CHECKER = 'checker';
    const TESTER = 'tester';
    const SUPERUSER = 'root'; // заменяет предыдущее значение
    const SUPERMODERATOR = 'supermoderator'; // заменяет предыдущее значение
}
```

В новом классе констант мы можем переопределить ранее объявленные значения.

Давайте посмотрим примеры использования наших созданных классов.

```php
// Получим все константы классов
$allFirstConstants = FirstConstants::getConstants();
$allSecondConstants = SecondConstants::getConstants();

// Output $allFirstConstants: [
//    'USER' => 'user',
//    'SUPERUSER' => 'superuser',
//    'CLIENT' => 'client',
//    'MODERATOR' => 'moderator',
//    'SUPERMODERATOR' => 'superuser',
// ]
//
// Output $allSecondConstants: [
//    'USER' => 'user',
//    'SUPERUSER' => 'root',
//    'CLIENT' => 'client',
//    'MODERATOR' => 'moderator',
//    'SUPERMODERATOR' => 'superuser',
//    'VIEWER' => 'viewer',
//    'CHECKER' => 'checker',
// ]

// Получим конкретные константы FirstConstants
$user = FirstConstants::getConst('USER'); // 'user'
$superuser = FirstConstants::getConst('SUPERUSER'); // 'superuser'
$root = FirstConstants::getConst('ROOT'); // null

// Получим конкретные константы SecondConstants
$user = SecondConstants::getConst('USER'); // 'user'
$superuser = SecondConstants::getConst('SUPERUSER'); // 'root'
$root = SecondConstants::getConst('ROOT'); // null

// Проверим существование констант
SecondConstants::isDefined('SUPERUSER'); // true
SecondConstants::isDefined('ROOT'); // false
```

В примере выше мы получили все константы, получили конкретные константы, попытались получить несуществующую константу и проверили существование константы в классе.

Конечно, мы можем обращаться к конкретным константам напрямую, но обратившись к несуществующей константе мы получим исключение. Поэтому лучше использовать функции класса.

```php
$superuser = SecondConstants::SUPERUSER; // 'root'
$root = SecondConstants::ROOT; // Error: Undefined class constant 'ROOT'
```

### <a name="abstract-enum"></a> AbstractEnum

AbstractEnum - абстрактный класс для реализации списка именованных констант и возвращения их значений.  Отличие от `Belca\Support\AbstractConstants` класса, класс `Belca\Support\AbstractEnum` использует константу по умолчанию.

|Имена функций|Описание функций|
|--|--|
|getConstants()| Возвращает все константы класса без значения по умолчанию (значение по умолчанию может ссылаться на одну из констант).|
|getLastConstants()|Возвращает массив констант определенных в вызываемом классе и не возвращает константы родительских классов.|
|getParentConstants()|Возвращает все константы родительских классов без значения по умолчанию.|
|getConst($const)|$const - имя константы.<br/><br/> Возвращает значение указанной константы, если оно существует, иначе возвращает *null*.<br/> Это безопасный метод вызова констант, т.к. вызывая константу другим способом вы можете получить ошибку, если контанта не существует.|
|isDefined($const)|$const - имя константы. <br/><br/> Проверяет существование константы в используемом классе.|
|getDefault()|Возвращает последнее объявленное значение по умолчанию.|

Функции реализованного класса от `AbstractEnum` идентичны классу `AbstractConstants`, за исключением одного. Вам доступна новая функция - `getDefault()`. Также немного отличается и реализация класса.

```php
use Belca\Support\AbstractEnum;

class FirstConstants extends AbstractEnum
{
    const DEFAULT = self::USER;

    const USER = 'user';
    const SUPERUSER = 'root';
    const CLIENT = 'client';
}
```

```php
$defaultValue = FirstConstants::getDefault(); // 'user'
```

Расширяя класс мы можем переопределить значение по умолчанию.

## <a name="arrays"></a> Массивы (Arrays) - функции для обработки массивов

Класс `Belca\Support\Arr` используется для обработки массивов с простым набором данных (данные и ключи массивов могут не иметь каких-то специальных правил хранения, в отличии от обрабатываемых данных классом `Belca\Support\SpecialArr`).

Для работы с функциями класса необходимо подключить его или вызывать его функции указывая полный путь к классу. Примеры подключения и использования функций класса показаны ниже.

```php
use Belca\Support\Arr;

// или

$result = \Belca\Support\Arr::trim($array); // и другие функции
```

||||
|--|--|--|
|[Arr::trim](#array-trim) |[Arr::removeEmpty](#array-remove-empty)|[Arr::removeNull](#array-remove-null)|
|[Arr::removeNotScalar](#array-remove-not-scalar)|[Arr::removeEmptyRecurcive](#array-remove-empty-recurcive)|[Arr::removeNullRecurcive](#array-remove-null-recurcive)|
|[Arr::isArrayWithIntKeys](#array-is-array-with-int-keys)|[Arr::isIntKeys](#array-is-int-keys)|[Arr::isFirstLastWithIntKeys](#array-is-first-last-with-int-keys)|
|[Arr::pushArray](#array-push-array)|[Arr::removeArrays](#array-remove-arrays)|[Arr::last](#array-last)|
|[Arr::unset]|(#array-unset)||

### <a name="array-trim"></a> Arr::trim

`Arr::trim(array $array) : array`

Удаляет лишние пробелы, табуляции, переносы в строковых значениях массива с помощью функции `trim()`. Ключи массива остаются в неизменном виде.

```php
$array = [
    ' value ',
    'trim',
    'one ',
    ' two',
    '   three    ',
    1,
    2,
    'string',
    null,
    ['  no trim  '],
];

$result = Arr::trim($array);

// Output: ['value', 'trim', 'one', 'two', 'three', 1, 2, 'string', null, ['  no trim  ']];
```

Если в качестве аргумента передан не массив, то будет возвращен пустой массив.

```php
$notArray = null;
$result = Arr::trim($notArray);

// Output: [];
```

Значения вложенных массивов не обрабатываются этой функцией.

### <a name="array-remove-empty"></a> Arr::removeEmpty

`Arr::removeEmpty(array $array) : array`

Удаляет пустые значения массива проверяя значения функцией `empty()`. Ключи массива остаются в неизменном виде.

Если в качестве аргумента передан не массив, то будет возвращен пустой массив.

```php
$array4 = [1, 2, null, '', [], new stdClass, false, 0];

$result = Arr::removeEmpty($array);

// Output: [1, 2, 5 => new stdClass];
```

### <a name="array-remove-null"></a> Arr::removeNull

`Arr::removeNull(array $array) : array`

Удаляет элементы массива со значением *null* с помощью функции `is_null()`. Ключи массива остаются в неизменном виде.

Если в качестве аргумента передан не массив, то будет возвращен пустой массив.

```php
$array = [1, 2, null, '', [], new stdClass, false, 0];

$result = Arr::removeNull($array);  

// Output: [1, 2, 3 => '', [], new stdClass, false, 0];
```

### <a name="array-remove-not-scalar"></a> Arr::removeNotScalar

`Arr::removeNotScalar(array $array) : array`

Удаляет значения массива со значением 'null' или с другими значениями не являющимися скалярными (скалярные значения: integer, float, string, boolean). Ключи остаются в неизменном виде.

```php
$array = [1, 2, null, '', [], new stdClass, false, 0];
$result = Arr::removeNotScalar($array);

// Output: [0 => 1, 1 => 2, 3 => '', 6 => false, 7 => 0];
```

### <a name="array-remove-empty-recurcive"></a> Arr::removeEmptyRecurcive

`Arr::removeEmptyRecurcive(array $array, boolean $resetIndex = true) : array`

Рекурсивно удаляет пустые значения многомерного массива.

Если в качестве значения будет указан не массив, то это значение будет возвращено в неизменном виде.

Параметры функции:
- $array - любой массив;
- $resetIndex - сброс массива. Если **$resetIndex** - *true*, то сбрасывает числовые ключи массива, в т.ч., которые были заданы вручную, а не автоматически присвоены при инициализации массива.

```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    'a3' => [
        1,
        2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeEmptyRecurcive($array);

// Output:
// [
//    1 => [0 => 1, 1 => 2, 2 => [1, 2, 3, 4], 3 => 4],
//    2 => -2,
//    'a3' => [
//         0 => 1,
//         1 => 2,
//         'a3.3' => [0 => 1, 1 => 2, 2 => 3],
//    ],
// ]
```

В примере выше сброс ключей произешел только в тех массивах, в которых все ключи были числовыми. Таким образом основные ключи массива и ключи значения `$arrar['a3']` остались без изменений, а все другие ключи были обнулены.

```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    'a3' => [
        1,
        2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeEmptyRecurcive($array, false);

// Output:
// [
//    1 => [1, 2, 3 => [1, 2, 3, 4], 4 => 4],
//    2 => -2,
//    'a3' => [
//         0 => 1,
//         1 => 2,
//         'a3.3' => [1 => 1, 2 => 2, 3 => 3],
//    ],
// ]
```

В примере выше все ключи остались без изменений, потому что в качестве аргумента **$resetIndex** мы указали *false*.

### <a name="array-remove-null-recurcive"></a> Arr::removeNullRecurcive

`Arr::removeNullRecurcive(array $array, boolean $resetIndex = true) : array`

Рекурсивно удаляет значения равные *null* в многомерном массиве.

Параметры функции:
- $array - любой массив;
- $resetIndex - сброс индексов. Если **$resetIndex** - *true*, то сбрасывает числовые ключи массива во всех внутренних массивах. Если обрабатываемом массиве есть хотя бы один нечисловой ключ, то все ключи, в т.ч. числовые ключи обрабатываемого массива, остаются неизменными.

```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    4 => [
        1 => 1,
        2 => 2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeNullRecurcive($array);

// Output:
// [
//    0 => [0 => 1, 1 => 2, 2 => [1, 2, 3, 4, []], 3 => 4, 4 => ''],
//    1 => -2,
//    2 => [
//         1 => 1,
//         2 => 2,
//         'a3.3' => [0 => 1, 1 => 2, 2 => 3],
//    ],
//    3 => '',
//    4 => 0,
//    5 => false,
// ]
```

В примере выше происходит сброс ключей массива, если все ключи обрабатываемого массива числовые. Таким образом ключи значения `$array[2]` остались неизменными, хотя сам индекс этого значения изменился.

В примере ниже все ключи остаются без изменений.

```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    4 => [
        1 => 1,
        2 => 2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeNullRecurcive($array, false);

// Output:
// [
//    1 => [1, 2, 3 => [1, 2, 3, 4, []], 4 => 4, 5 => ''],
//    2 => -2,
//    4 => [
//         1 => 1,
//         2 => 2,
//         'a3.3' => [1 => 1, 2 => 2, 3 => 3],
//    ],
//    6 => '',
//    7 => 0,
//    8 => false,
// ]
```

### <a name="array-is-array-with-int-keys"></a> Arr::isArrayWithIntKeys

`Arr::isArrayWithIntKeys(array $array) : boolean`

Проверяет, числовые ли ключи в массиве. Может служить для проверки массива на ассоциативность.

Возвращает *true*, только если все ключи являются числовыми. Пустой массив не является числовым, т.к. его ключи и значения еще не определены.

```php
$array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
$array2 = []; // false
$array3 = 1; // false
$array4 = ['1' => 1, 2, 3, '4' => 4]; // true
$array5 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false

$result1 = Arr::isArrayWithIntKeys($array1); // true
$result2 = Arr::isArrayWithIntKeys($array2); // false, потому что пустой массив
$result3 = Arr::isArrayWithIntKeys($array3); // false, потому что не массив
$result4 = Arr::isArrayWithIntKeys($array4); // true, потому что числа в строке преобразованы в integer
$result5 = Arr::isArrayWithIntKeys($array5); // false
```

### <a name="array-is-int-keys"></a> Arr::isIntKeys

`Arr::isIntKeys(array $array) : boolean`

Синоним функции `Arr::isArrayWithIntKeys()`.

```php
$normalArray = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
$badArray = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false

$result1 = Arr::isIntKeys($normalArray); // true
$result2 = Arr::isIntKeys($badArray); // false
```

### <a name="array-is-first-last-with-int-keys"></a> Arr::isFirstLastWithIntKeys

`Arr::isFirstLastWithIntKeys(array $array) : boolean`

Проверяет, является ли первое и последнее значения в массиве с числовыми ключами.

Этот прстой алгоритм помогает определить ассоциативность массива. Фактически это аналог функции `isArrayWithIntKeys()`, но в этом случае все ключи переданного массива должны принадлежать одному или другому типу (т.е. быть либо числовыми, либо строковыми).

При передачи пустого массива или не массива, результатом функции будет *false*.

```php
$array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
$array2 = []; // false
$array3 = 1; // false
$array4 = ['1' => 1, 2, 3, '4' => 4]; // true
$array5 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // true
$array6 = [50 => 1, 'a2' => 3, 'a3' => 4, 'one' => 1]; // false

$result1 = Arr::isFirstLastWithIntKeys($array1); // true
$result2 = Arr::isFirstLastWithIntKeys($array2); // false, потому что пустой массив
$result3 = Arr::isFirstLastWithIntKeys($array3); // false, потому что не массив
$result4 = Arr::isFirstLastWithIntKeys($array4); // true, потому что числа в строке преобразованы в integer
$result5 = Arr::isFirstLastWithIntKeys($array5); // true, потому что первый и последний ключ является числовым
$result6 = Arr::isFirstLastWithIntKeys($array6); // false, потому что последний ключ строка
```

В отличии от фукнции `Arr::isArrayWithIntKeys()`, которая может пройти весь массив, текущая функция проверяет только первое и последнее значение, что выполнится быстрее.

### <a name="array-push-array"></a> Arr::pushArray

`Arr::pushArray(&$source, $array, $replace = true) : void`

Добавляет к указанному массиву массив новых ключей и значений в конец массива.

В отличие от `array_merge()` функция не создает и не возвращает новый массив, а работает с исходным массивом.

В отличие от `array_merge()`, которая бы добавила значения нового массива к первому массиву, если бы использовались числовые ключи, `Arr::pushArray()` будет заменять числовые значения с одинаковыми ключами, как если бы они были ассоциативными значениями.

Параметры функции:
- &$source - исходный массив (используется ссылка на массив);
- $array - добавляемый массив;
- $replace - замена значений. Если **$replace** - *true*, то все существующие значения с одинаковыми ключами будут заменены на новые.

** Пример 1: добавление новых значений в массив и замена значений с одинаковыми ключами, массив с цифровыми ключами.**

```php
$source = [1, 2, 3, 4, 5, 6];
$array = [6, 7, 8, 9, 10, 11, 12];

Arr::pushArray($source, $array);

// Output $source: [6, 7, 8, 9, 10, 11, 12];
```

В примере выше может показаться неожиданный результат, т.к. все значения были переписаны значениями нового массива. Это произошло из-за того, что все ключи массива совпадали и было добавлено одно новое значение - *12*.

** Пример 2: добавление новых значений в массив и замена значений с одинаковыми ключами, массив с цифровыми ключами. Смещение цифрового ключа.**

```php
$source = [1, 2, 3, 4, 5, 6];
$array = [6 => 6, 7, 8, 9, 10, 11, 12];

Arr::pushArray($source, $array);

// Output $source: [1, 2, 3, 4, 5, 6, 6, 7, 8, 9, 10, 11, 12];
```

В примере выше мы получили уже необходимый для нас результат, т.е. присоединили новые значения к переданному массиву. Такой результат наиболее полезен при работе с ассоциативными массивами.

В примере ниже мы используем ассоциативный массив, в котором будут переписаны значения с совпадающими ключами добавляемого массива.

** Пример 3: добавление новых значений в массив с заменой предыдущих значений с одинаковыми ключами. **

```php
$source = ['key1' => 1, 'key2' => 2];
$newValues = ['key2' => 3, 'key3' => 4];

Arr::pushArray($source, $newValues);

// Output $source: ['key1' => 1, 'key2' => 3, 'key3' => 4];
```

Однако не всегда может быть полезным заменять значения исходного массива и может быть необходимость добавлять исключительно новые значения, которых еще не было в исходном массиве.

Такой пример показан ниже.

** Пример 4: добавление только новых значений в массив. **

```php
$source = ['key1' => 1, 'key2' => 2];
$newValues = ['key2' => 3, 'key3' => 4];

Arr::pushArray($source, $newValues, false);

// Output $source: ['key1' => 1, 'key2' => 2, 'key3' => 4];
```

Как вы заметили, функция не возвращает результат, а работает с исходным массивом, т.е. передается по ссылке.

### <a name="array-remove-arrays"></a> Arr::removeArrays

`Arr::removeArrays($array, $resetIndex = false) : array`

Удаляет из массива вложенные массивы (подмассивы).

Параметры функции:
- $array - любой массив;
- $resetIndex - сброс массива. Если **$resetIndex** - *true*, то сбрасывает ключи массива.

** Пример 1: удаление внутренних массивов. **

```php
$array = [
    1,
    2,
    3,
    'four' => 4,
    'five' => 5,
    'matrix' => [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ],
    7,
    'eight' => 8,
    9,
    'symbols' => ['a', 'b', 'c'],
    'object' => new stdClass(),
];

$result = Arr::removeArrays($array);

// Output: [
//    1,
//    2,
//    3,
//    'four' => 4,
//    'five' => 5,
//    7,
//    'eight' => 8,
//    9,
//    'object' => new stdClass(),
// ];
```

В примере выше мы удалили все массивы и оставили другие значения.

Иногда при такой операции может потребоваться обнулять и ключи массива, как это показано в примере ниже.

** Пример 2: удаление внутренних массивов и сброс ключей массива. **

```php
$array = [
    1,
    2,
    3,
    'four' => 4,
    'five' => 5,
    'matrix' => [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ],
    7,
    'eight' => 8,
    9,
    'symbols' => ['a', 'b', 'c'],
    'object' => new stdClass(),
];

$result = Arr::removeArrays($array);

// Output: [1, 2, 3, 4, 5, 7, 8, 9, new stdClass()];
```

### <a name="array-last"></a> Arr::last

`Arr::last(&$array) : mixed`

Функция возвращает последний элемент массива. Не смотря на то, что в функцию передается ссылка на массив, внутренний указатель массива не сбрасывается.

```php
$array = [5 => 1, 2, 3, 4, 5];

$last = Arr::last($array); // Output: 5
```

### <a name="array-unset"></a> Arr::unset

`Arr::unset($array, ...$indexes) : array`

Удаляет указанные индексы и возвращает измененный массив с сокранением индексов.

```php
$array = [
    1,
    2,
    3,
    'four' => 4,
    'five' => 5,
    'matrix' => [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ],
    7,
    'eight' => 8,
    9,
    'symbols' => ['a', 'b', 'c'],
    'object' => new stdClass(),
];

$output = Arr::unset($array, 0, 'four', 'eight', 4);
$output = Arr::unset($array, [0, 'four', 'eight', 4]);
$output = Arr::unset($array, [0, 'four'], ['eight', 4]);
$output = Arr::unset($array, [0, 'four'], [['eight'], [4], []]);

// Output:
// [
//    1 => 2,
//    2 => 3,
//   'five' => 5,
//    'matrix' => [
//        [1, 2, 3],
//        [4, 5, 6],
//         [7, 8, 9],
//    ],
//    3 => 7,
//    'symbols' => ['a', 'b', 'c'],
//    'object' => new stdClass(),
// ]
```

Как видите из примера выше, функция принимает практически любые допустимые ключи и вложенные массивы, которые могут содержать ключи.

Эта функция может быть полезна, когда необходимо удалить из массива заранее неизвестные значения, но будут известны их индексы. Похожего эффекта можно достичь с помощью функции `array_filter()`, однако эта функция более читаема, компакта и универсальна. 

## <a name="special-arrays"></a> Функции обработки специальных массивов (Special arrays)

Функции класса `Belca\Support\SpecialArr` предназначены для обработки массивов со значениями хранящимися по определенным правилам и обрабатываются соответствующими функциями.

||||
|--|--|--|
|[SpecialArr::originalKeys](#special-array-original-keys)|[SpecialArr::doubleImplode](#special-array-double-implode)|[SpecialArr::pullThroughSeparator](#special-array-pull-through-separator)|

### <a name="special-array-original-keys"></a> SpecialArr::originalKeys

`SpecialArr::originalKeys($array)`

Возвращает оригинальные ключи массива, при условии, что передан массив ключей, в том числе состоящий из двумерного массива, где в качестве значения вложенного массива строка - алиас ключа.

** Пример 1: получение ключей с использованием их алиасов.**

```php
$array = ['finfo.created', 'finfo.size' => 'filesize', 'finfo.mime' => 'mime'];

$keys = SpecialArr::originalKeys($array);

// Output: ['finfo.created', 'finfo.size', 'finfo.mime']
```

** Пример 2: получение ключей без использования алиасов.**

```php
$array = ['finfo.created', 'finfo.size', 'finfo.mime'];

$keys = SpecialArr::originalKeys($array);

// Output: ['finfo.created', 'finfo.size', 'finfo.mime']
```

В последнем примере функции мы получили равнозначный массив. Функция преимущественно нацелена на использование массивов с указаниями алисами ключей или каких-то других значений.

### <a name="special-array-double-implode"></a> SpecialArr::doubleImplode

`SpecialArr::doubleImplode($array = [], $chainLinks = ["", "", ""], $glue = "")`

Параметры функции:
- $array - ассоциативный массив;
- $chainLinks - соединители внутренних значений массива;
- $glue - cоединитель конечных значений массива.

Функция используется для слияния массива и его значений, вставляя указанные разделяющие символы.

** Пример 1: объединение атрибутов тега **

```php
$array = [
    'name' => 'phone',
    'maxlength' => 20,
    'class' => 'input input_type_primary input_width_medium',
    'required' => true,
];

$result = SpecialArr::doubleImplode($array, ["", "=\"", "\""], " ");

// Output: 'name="phone" maxlength="20" class="input input_type_primary input_width_medium" required'
```

Обратите внимание, что логический атрибут был заменен на имя ключа без присвоения значения *true*. Стоит отметить, если указать в качестве значения *false*, то значение будет проигнорировано.

### <a name="special-array-pull-through-separator"></a> SpecialArr::pullThroughSeparator

`SpecialArr::pullThroughSeparator($array, $target, $separator = '.')`

Извлекает значение из многомерного массива с помощью разделителя.

Параметры функции:
- $array - многомерный ассоциативный массив;
- $target - цепочка из ключей разделенных разделителем (например, символом '.') для доступа к элементам массива;
- $separator - строка-разделитель или символ-разделитель.

```php
$array = [
    'finfo' => [
        'size' => 998,
        'mime' => 'image/png',
    ],
];

$value = SpecialArr::pullThroughSeparator($array, 'finfo.size'); // Output: 998

$value = SpecialArr::pullThroughSeparator($array, 'finfo');
// Output: [
//     'size' => 998,
//     'mime' => 'image/png'
// ]

$value = SpecialArr::pullThroughSeparator($array, 'finfo.date'); // Output: null
```

Как вы могли заметить, данная функция аналог функции `Arr::get($array, 'path.to.value')` из Laravel, за исключением возможности изменять разделяющий символ.

## HTML - функции обработки HTML

Класс предназначен для обработки HTML-фрагментов и HTML-документов в строковом представлении.

Подключите класс `Belca\Support\HTML`, чтобы начать его использовать.

```php
use Belca\Support\HTML;
```

||||
|--|--|--|
|[HTML::removeTags](#html-remove-tags)|[HTML::removeTagContent](#html-remove-tag-content)|[HTML::removeSingleTags](#html-remove-single-tags)|
|[HTML::removeTagAttributes](#html-remove-tag-attributes)|||

### <a name="html-remove-tags"></a> HTML::removeTags

`HTML::removeTags($html, $allowedTags = [], $clearTags = true, $allowedAttributes = [])`

Функция удаляет теги указанного HTML контента. Можно указывать разрешенные теги и оставлять только разрешенные атрибуты тегов.

Параметры функции:
- **$html** - фрагмент HTML-документа в виде строки;
- **$allowedTags** - разрешенные теги (исключения);
- **$clearTags** - очистка тегов. Если **$clearTags** - *true*, то атрибуты внутри тегов будут удалены, иначе атрибуты будут нетронуты;
- $allowedAttributes - массив разрешенных атрибутов тега. Если включена очистка тегов от атрибутов, то все атрибуты тегов станут пустыми. Это затрагивает также и одиночные теги, где будут удалены все атрибуты у таких элементов как *img*, *input*, *meta*, а без этих атрибутов элементы бессмысленны. Чтобы избежать этого, нужно здесь перечислить все необходимые атрибуты, например, *src*, *content*, *name*.

**Пример 1: удаление всех тегов.**

```php
$html = "<p><b>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><img src='/path/to/img.jpg'></p>";

$html = HTML::removeTags($html);

// Output: "Lorem ipsum dolor sit amet, consectetur adipisicing elit,  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
```

**Пример 2: удаление всех тегов кроме разрешенных.**

```php
$html = "<p><b class='color_red'>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><img src='/path/to/img.jpg'></p>";
$allowedTags = ['b', 'hr'];

$html = HTML::removeTags($html);

// Output: "<b>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
```

Обратите внимание, что все атрибуты тега *b* также удалены.

**Пример 3: удаление всех тегов кроме разрешенных, оставляя необходимые атрибуты в тегах.**

```php
$html = "<p><b class='color_red' style='color: green;'>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><img src='/path/to/img.jpg'></p>";
$allowedTags = ['b', 'hr'];
$clearTags = true;
$allowedAttributes = ['class'];

$html = HTML::removeTags($html, $allowedTags, $clearTags, $allowedAttributes);

// Output: "<b class='color_red'>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
```

**Пример 4: удаление всех тегов кроме разрешенных, оставив все атрибуты в тегах.**

Результат такой функции может быть небезопасным, т.к. при разрешении таких тегов как *script*, *style* и других подобных тегов, полученный код может исполняться и изменять отображаемый контент, что может быть нежелательно для пользователя.

```php
$html = "<p><b class='color_red' style='color: green;'>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><img src='/path/to/img.jpg'></p>";
$allowedTags = ['b', 'hr'];

$html = HTML::removeTags($html, $allowedTags, false);

// Output: "<b class='color_red' style='color: green;'>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
```

Иногда, кроме удаления тегов необходимо удалить и содержимое тегов. Для таких целей используйте функцию `HTML::removeTagContent()`.

### <a name="html-remove-tag-content"></a> HTML::removeTagContent

`HTML::removeTagContent($html, $tags = [])`

Функция удаляет содержимое указанных тегов и сами теги. Удаляются данные от начала тега до конца тега, не затрагивая окружающие пробелы, табуляции и переносы строк.

Параметры функции:
- $html - фрагмент HTML-документа в виде строки;
- $tags - массив тегов, которые будут удалены. Вместе с тегами будет удалено их содержимое.

В примере ниже мы указали тег *p*, в который обернут весь контент. Также мы указали теги *b*, *br*, *img*, но в данном примере другие теги, кроме тега *p* будут не важны, т.к. все содержимое этого тега будет удалено.

```php
$html = "<p><b>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><img src='/path/to/img.jpg'></p>";

$html = HTML::removeTagContent($html, $tags = ['p', 'b', 'br', 'img']); // Output: ''
```

В примере ниже мы указали теги *b*, *br*, *img*, содержимое которых будет удалено. Теги *br* и *img* нижего не содержат, т.к. это одиночные теги, но они также будут удалены.

```php
$html = "<p><b>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><img src='/path/to/img.jpg'></p>";

$html = HTML::removeTagContent($html, $tags = ['b', 'br', 'img']);

// Output: "<p> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>";
```

Обратите внимание на пробел в теге *p* перед словом 'sit' - он не был удален, т.к. не является частью тега. Это стоит учитывать при удалении тегов.

### <a name="html-remove-single-tags"></a> HTML::removeSingleTags

`HTML::removeSingleTags($html, $allowed = [])`

Функция удаляет одиночные теги, кроме разрешенных.

Параметры функции:
- $html - фрагмент HTML-документа в виде строки;
- $allowed - разрешенные теги. Разрешенные теги не будут удалены, т.е. это исключения.

**Список одиночных тегов**: area, *base*, *basefont* (устаревший тег), *bgsound* (нестандартный тег), *br*, *col*, *command*, *doctype*, *embed*, *hr*, *img*, *input*, *isindex* (устаревший тег), *keygen*, *link*, *meta*, *param*, *source*, *track*, *xml*, *wbr*.

```php
$html = "<p><b>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><img src='/path/to/img.jpg'></p>";

$html = HTML::removeSingleTags($html, $allowed = ['hr']);

// Output: "<p><b>Lorem ipsum dolor</b> sit amet, consectetur adipisicing elit, <hr> sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>";
```

### <a name="html-remove-tag-attributes"></a> HTML::removeTagAttributes

`HTML::removeTagAttributes($html, $allowedAttributes = [])`

Функция удаляет атрибуты тегов, оставляя разрешенные атрибуты.
При полной очистке тега от атрибутов создается новый атрибут в формате HTML5, т.о. все теги будут в формате *<tag>* без символа '/' в конце, даже одиночные теги.

## HTTP

Для использования функций класса подключите класс `Belca\Support\HTTP`.

```php
use Belca\Support\HTTP;
```

### <a name="http-parse-accept-language"></a> HTTP::parseAcceptLanguage

Парсит строку HTTP заголовка 'HTTP_ACCEPT_LANGUAGE' и возвращает массив.

```php
$languages = HTTP::parseAcceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");

// Output $languages:
// [
//   "ru-RU" => 1,
//   "ru" => 0.8,
//   "en-US" => 0.5,
//   "en" => 0.3
// ];
```

## <a name="strings"></a> Strings - функции для обработки строк

Для использования функций подключите класс `Belca\Support\Str`.

```php
use Belca\Support\Str;
```

||||
|--|--|--|
|[Str::removeDuplicateSymbols](#string-remove-duplicate-symbols)|[Str::reduceDuplicateSymbols](#string-reduce-duplicate-symbols)|[Str::normalizeFilePath](#string-normalize-file-path)|
|[Str::differenceSubstring](#string-difference-substring)|[Str::findPositionFromEnd](#string-find-position-from-end)|[Str::firstElementOfChain](#string-first-element-of-chain)|
|[Str::lastElementOfChain](#string-last-element-of-chain)|||

### <a name="string-remove-duplicate-symbols"></a> Str::removeDuplicateSymbols

`Str::removeDuplicateSymbols($string, $symbols, $number = 2, $strict = false)`

Функция сокращает повторяющиеся символы (повторяющуюся подстроку) в строке.

Параметры функции:
- **$string** - обрабатываемая строка;
- **$symbols** - символы для поиска (один символ или строка);
- **$number** - минимальное число повторений или точное число повторений, в зависимости от параметра **$strict**. Повторением тут называется число дублирующихся подстрок или символов (2 и более), т.е. одно повторение символа *A* означает вхождение двух символов *A* в подстроку - *AA*;
- **$strict** - строгое количество символов. Если true, то удалит точное число повторений строки, а не минимальное.

Работу функции лучше изучить на примере.

В примере ниже выполняется поиск и удаление значения 'A' до одного значения, которое встречается 2 и более раз подряд. Функция сократила подстроки повторяющегося символа 'A' до одного символа.

```php
$source = "AABC aabbcc A A aaaaAAAA A A A B B";

$result = Str::removeDuplicateSymbols($source, 'A ', 1); // 1+1 = 2 и более вхождений в подстроку

// Output: "ABC aabbcc A A aaaaA A A A B B"
```

Иногда нужно найти и сократить конкретное повторение значений, как это происходит в примере ниже. Все повторяющиеся по символы сокращаются до двух повторений.

```php

$source = "AABC aabbcc A A aaaaAAAA A A A B B";

$result = Str::removeDuplicateSymbols($source, 'A', 1, true); // 1+1 = 2 вхождения

// Output: "ABC aabbcc A A aaaaAA A A A B B"
```

### <a name="string-reduce-duplicate-symbols"></a> Str::reduceDuplicateSymbols

`Str::reduceDuplicateSymbols($string, $symbols, $number = 1)`

Параметры функции:
- **$string** - обрабатываемая строка;
- **$symbols** - символ или набор символов для уменьшения;
- **$number** - количество дублирующихся символов. При значении 1 - уменьшает число подряд идущих символов до одного, 2 - до 2-х и т.п.

Функция уменьшает количество повторений подряд идущих символов до указанного значения.

В примере ниже, количество символов 'A' было уменьшено до 2-х символов в символьном наборе `aaaaAAAA` (`aaaaAAAA` -> `aaaaAA`). Вроде бы ничего обычного нет, когда работаешь с одним символом.

```php
$source = "ABC aabbcc aaaaAAAA A A A B B B A A A";

$result = Str::reduceDuplicateSymbols($source, 'A', 2);

// Output: "ABC aabbcc aaaaAA A A A B B B A A A"
```

В другом примере мы работаем уже с последовательностью из двух символов: 'A' и ' '. Не разбираясь в примере, можно предположить, что результатом будет 'ABC aabbcc aaaaAAAA A A B B B A A', но в реальности мы получаем `ABC aabbcc aaaaAAAA A A B B B A A A`. Это значение было получено, т.к. в конце строки нет пробела как в исходном выражении. Это нужно учитывать при поиске и замене значений.

```php
$source = "ABC aabbcc aaaaAAAA A A A B B B A A A";

$result = Str::reduceDuplicateSymbols($source, 'A ', 2);

// Output: "ABC aabbcc aaaaAAAA A A B B B A A A"
```

### <a name="string-normalize-file-path"></a> Str::normalizeFilePath

`Str::normalizeFilePath($string)`

Функция удаляет лишние слеши (символ '/') из строки и возвращает новую строку.

```php
$path = "//path///to//directory/";
$path = Str::normalizeFilePath($path); // Output: "/path/to/directory/"
```

### <a name="string-difference-substring"></a> Str::differenceSubstring

`Str::differenceSubstring($sourceString, $secondString)`

Возвращает разницу строк, если строка является подстрокой другой строки, иначе выводит *null*.
Порядок передачи строк не имеет значения.
Если передано значение отличное от строки, то возвращает *null*.

Типичный случай использования этой функции - определение различающегося пути директорий.  Такой пример ниже.

```php
$sourceString = '/home/user/directory/';
$secondString = '/home/user/directory/subdirecotry/filename';
$result = Str::differenceSubstring($sourceString, $secondString);

// Output: 'subdirecotry/filename';
```

Ниже пример, когда происходит определение разницы строк из двух разных строк. Т.к. строки разные, то результатом функции будет *null*.

```php
$sourceString = '/home/user/';
$secondString = '/user/directory/subdirecotry/filename';
$result = Str::differenceSubstring($sourceString, $secondString);

// Output: null
```

Если одно из значений будет пустая строка, то результатом функции будет заполенная строка. А при сравнении одинаковых строк будет возвращена пустая строка.

```php
$sourceString = '';
$secondString = '/home/user/directory/subdirecotry/filename';
$result = Str::differenceSubstring($sourceString, $secondString);

// Output: '/home/user/directory/subdirecotry/filename'
```

### <a name="string-find-position-from-end"></a> Str::findPositionFromEnd

`Str::findPositionFromEnd($haystack, $needle, $offset = 0, $fromEnd = false)`

Функция возвращает позицию начала вхождения подстроки. Поиск начинается/происходит с конца строки Если  подстрока не найдена возвращает *false*. По умолчанию возвращаемая позиция отсчитывается с нуля от начала строки, но это значение можно изменить и возвращать позицию с конца строки отсчитывая с *-1* и глубже.
В отличие от функций `strpos()` и `mb_strpos()`, в этой функции поиск осуществляется с конца строки, а не с начала.

Параметры функции:
- **$haystack** - исходная строка;
- **$needle** - значение для поиска (символ или строка);
- **$offset** - смещение позиции начала поиска от конца строки;
- **$fromEnd** - если *true*, то возвращает позицию отсчитывая с конца (значение *-1* - последний элемент).

Давайте рассмотрим примеры работы функции.

```php
$string = "Lorem ipsum dolor sit amet... dolore magna aliqua.";

# Example 1: Поиск значения без дополнительных параметров
$result = Str::findPositionFromEnd($string, "dolor"); // Output:  30

# Example 2: Поиск значения без отступа от конца строки, а возвращаемая позиция должна отсчитываться с конца строки с позиции '-1'.
$result = Str::findPositionFromEnd($string, "dolor", 0, true); // Output: -20

# Example 3: Поиск значения с отступом с конца в 20 символов.
$result = Str::findPositionFromEnd($string, "dolor", 20); // Output: 12

# Example 4: Поиск значения с отступом с конца в 20 символов и возврат значения с отчетом с конца строки.
$result = Str::findPositionFromEnd($string, "dolor", 20, true); // Output: -38

# Example 5: Поиск одного символа
$result = Str::findPositionFromEnd($string, "."); // Output: 49

# Example 6: Поиск одного символа с конца строки и возврат значения с отчетом с конца строки.
$result = Str::findPositionFromEnd($string, ".", 0, true); // Output: -1

# Example 7: Поиск несуществующего символа
$result = Str::findPositionFromEnd($string, "!"); // Output: null
```

Если вам нужно получить значение с конца строки ведя счет с *0*, а не с *-1*, то воспользуйтесь следующим примером.

```php
$string = "Lorem ipsum dolor sit amet... dolore magna aliqua.";
$result = Str::findPositionFromEnd($string, "dolor", 0, true);

$result = ($result + 1) * (-1); // 19
// or
$result = abs($result + 1); // 19
```

### <a name="string-first-element-of-chain"></a> Str::firstElementOfChain

`Str::firstElementOfChain($chain, $separator = '.', $strict = false)`

Возвращает первый элемент указанной цепочки.

Параметры функции:
- **$chain** - строка со значениями объединенными через разделитель (цепочка значений), например, 'path.to.file' или '/path/to/file' или '/path/to/directory/';
- **$separator** - разделитель из одного символа или строки;
- **$strict** - строгий режим. При строгом режиме выдается первый результат слева от разделителя.

Давайте рассмотрим примеры работы этой функции. Он аргументов функции зависит ее будущий результат, конечно, речь идет о параметре *strict*.

В первом примере мы работаем с цепочкой значений разделенных через точку. Такие строки используются в Laravel для доступа к конфигурации, для подключения шаблонов Blade, для доступа к элементам массива и другим данным. Разделение через точку (символ '.') является значением по умолчанию, т.к. он является наиболее простым для работы с цепочками.

```php
$chain = "path.to.file";
$result = Str::firstElementOfChain($chain); // Output: 'path'
```

Если символ точка вам не подходит, вы можете задать любой другой разделитель, например, один символ или даже строку.

В примере ниже, мы работаем уже с символом '/', который может использоваться для разделения пути к файлу или директории.

```php
$chain = "/path/to/file";
$result = Str::firstElementOfChain($chain); // Output: 'path'
```

При работе в нестрогом режиме мы всегда получаем первое "действительное" значение. т.е. значение отличное от символа-разделителя. Но иногда нам может понадобиться получать реально первое значение цепочки, даже если оно может быть "некорректным".

Давайте сравним работу функции в разных режимах, в том числе, когда используются некорректные значения.

```php
// Example 1
$chain = "path.to.file";
$nonStrict = Str::firstElementOfChain($chain, '.'); // Output: 'path'
$strict = Str::firstElementOfChain($chain, '.', true); // Output: 'path'

// Example 2
$chain = "/path/to/file";
$nonStrict = Str::firstElementOfChain($chain, '/'); // Output: 'path'
$strict = Str::firstElementOfChain($chain, '/', true); // Output: null

// Example 3
$chain = "////path/to/file";
$nonStrict = Str::firstElementOfChain($chain, '/'); // Output: 'path'
$strict = Str::firstElementOfChain($chain, '/', true); // Output: null

// Example 4
$chain = "path";
$nonStrict = Str::firstElementOfChain($chain, '/'); // Output: 'path'
$strict = Str::firstElementOfChain($chain, '/', true); // Output: 'path'

// Example 5
$chain = "==path==to==file";
$nonStrict = Str::firstElementOfChain($chain, '=='); // Output: 'path'
$strict = Str::firstElementOfChain($chain, '==', true); // Output: null

// Example 6
$chain = "path====to====file";
$nonStrict = Str::firstElementOfChain($chain, '=='); // Output: 'path'
$strict = Str::firstElementOfChain($chain, '==', true); // Output: 'path'

// Example 7
$chain = "===path==to==file";
$nonStrict = Str::firstElementOfChain($chain, '=='); // Output: '=path'
```

Как видно, результат выполнения функции легко предсказать при использовании корректных данных. А вот в примере 7 обратите внимание на результат при работе с многосимвольными разделителями. Особенность работы с многосимвольными разделителями описана в функции `Str::lastElementOfChain()`.

### <a name="string-last-element-of-chain"></a> Str::lastElementOfChain

`Str::lastElementOfChain($chain, $separator = '.', $strict = false)`

Возвращает последний элемент указанной цепочки. Эта функция противоположна функции `firstElementOfChain()`.

Параметры функции:
- **$chain** - строка со значениями объединенными через разделитель (цепочка значений), например, 'path.to.file' или '/path/to/file' или '/path/to/directory/';
- **$separator** - разделитель из одного символа или строки;
- **$strict** - строгий режим. При строгом режиме выдается первый результат справа от разделителя.

Принцип работы этой функции такой же как у предыдущей: задаете строку, разделитель и, при необходимости, режим работы.

```php
$chain = 'path.to.element';
$element = Str::lastElementOfChain($chain); // Output: 'element'

$chain = 'path/to/element';
$element = Str::lastElementOfChain($chain, '/'); // Output: 'element'

$chain = 'path/to/element/';
$element = Str::lastElementOfChain($chain, '/'); // Output: 'element'

$chain = 'path/to/element/';
$element = Str::lastElementOfChain($chain, '/', true); // Output: null

$chain = 'path/to/element////';
$element = Str::lastElementOfChain($chain, '/'); // Output: 'element'
```

Обратите внимание на работу с многосимвольными разделителями! Когда используются многосимвольные разделители цепочки, то множество символов одного разделителя считается как набор одного символа. Пример работы с такими разделителями смотрите ниже.

```php
# Example 1
$chain = '==path==to==any==object===';
$element = Str::lastElementOfChain($chain, '==', true); // Output: null

# Example 2
$chain = '==path==to==any==object===';
$element = Str::lastElementOfChain($chain, '=='); // Output: 'object='
```

Во втором примере вы видите, что результат работы функции не *object*, а *object=*. Это потому что первый раздитель с конца посчитался за один символ, затем произошло смещение на длину раздителя (это 2 символа). Это стоит учитывать при использовании многосимвольных разделителей.

## <a name="service-provider"></a> ServiceProvider (depends on Laravel: Illuminate\Support\ServiceProvider, Illuminate\Foundation\AliasLoader)

Класс Belca\Support\ServiceProvider расширяет класс Illuminate\Support\ServiceProvider. Он добавляет в класс новые функции.

### <a name="service-provider-recurcive-replace-config-from"></a> recurciveReplaceConfigFrom

`recurciveReplaceConfigFrom($path, $key)`

Рекурсивно заменяет и расширяет значения конфигурации. В отличие от функции `mergeConfigFrom()`, встроенной в Laravel, текущая функция заменяет конечные значения вложенных элементов массива и дополняет новые элементы массива, а не переписывает полностью значения по основным ключам.

Параметры функции:
- **$path** - путь к конфигурации (PHP-файл возвращающий массив);
- **$key** - ассоциативный ключ массива заменяемой конфигурации.

Вызов функции осуществляется также, как и `mergeConfigFrom()`.

```php
// $this->mergeConfigFrom(__DIR__.'/../config/page.php', 'page'); // default in Laravel
$this->recurciveReplaceConfigFrom(__DIR__.'/../config/page.php', 'page');
```

Давайте сравним результаты выполнения функций.

```php
$source = [
  'one' => [
      'one_one' => 1,
      'one_two' => 2,
      'one_three' => 3,
  ],
  'two' => [
      'two_one' => 1,
      'two_two' => 2,
      'two_three' => 3,
  ],
];

$newConfig = [
    'one' => [
        'one_one' => 10,
        'one_two' => 20,
        'one_three' => 30,
    ],
    'two' => [
        'two_two' => 20,
        'two_four' => 40,
    ],
    'three' => [
        'three_one' => 30,
    ],
];

// The result of merge by Laravel
// [
//     'one' => [
//         'one_one' => 10,
//         'one_two' => 20,
//         'one_three' => 30,
//     ],
//     'two' => [
//         // the old values are not left
//         'two_two' => 20,
//         'two_four' => 40,
//     ],
//     'three' => [
//         'three_one' => 30,
//     ],
// ]

// The result of merge by the new function
// [
//     'one' => [
//         'one_one' => 10,
//         'one_two' => 20,
//         'one_three' => 30,
//     ],
//     'two' => [
//         'two_one' => 1, // it is left
//         'two_two' => 20,
//         'two_three' => 3, // it is left
//         'two_four' => 40,
//     ],
//     'three' => [
//         'three_one' => 30,
//     ],
// ]
```

Как видно, результаты отличаются. Такую функцию удобно использовать, когда необходимо заменить или частично дополнить старую конфигурацию новыми значениями.

Наиболее часто эта функция используется для добавления новых значений массива вложенных значений массива.
