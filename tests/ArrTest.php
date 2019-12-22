<?php

declare(strict_types=1);

namespace Belca\Support\Tests;

use PHPUnit\Framework\TestCase;
use Belca\Support\Arr;
use \stdClass;

final class ArrTest extends TestCase
{
    public function testRemoveArrays()
    {
        $input = [
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

        $output = [
            1,
            2,
            3,
            'four' => 4,
            'five' => 5,
            7,
            'eight' => 8,
            9,
            'object' => new stdClass(),
        ];

        $resetIndexOutput = [1, 2, 3, 4, 5, 7, 8, 9, new stdClass()];

        $this->assertEquals(Arr::removeArrays($input), $output);
        $this->assertEquals(Arr::removeArrays(null), [], 'null => []');
        $this->assertEquals(Arr::removeArrays([]), []);
        $this->assertEquals(Arr::removeArrays([[]]), []);
        $this->assertEquals(Arr::removeArrays('string'), []);
        $this->assertEquals(Arr::removeArrays(0), []);
        $this->assertEquals(Arr::removeArrays($input, true), $resetIndexOutput);
    }

    public function testPushArray()
    {
        // # First test
        $source = [];
        $array = [];
        $output = [];

        // Filling. Number keys
        for ($i = 0; $i < 20; $i++) {
            $source[$i] = $i * 10;
        }

        for ($i = 20; $i < 30; $i++) {
            $array[$i] = $i + 10;
        }

        $output = array_merge($source, $array);

        // Normal data
        $result = $source; // the result is here
        Arr::pushArray($result, $array);

        $this->assertEquals($result, $output);

        // Null data
        $sourceNull = null;
        Arr::pushArray($sourceNull, $array);

        $this->assertEquals($sourceNull, null);

        // # Second test
        // Replace values
        $source = [];
        $array = [];

        // Filling. String keys
        for ($i = 0; $i < 20; $i++) {
            $source["a$i"] = $i * 10;
        }

        for ($i = 10; $i < 20; $i++) {
            $array["a$i"] = $i + 10;
        }

        $output = array_merge($source, $array);

        $result = $source; // the result is here
        Arr::pushArray($result, $array);

        $this->assertEquals($result, $output);
    }

    public function testisFirstLastWithIntKeys()
    {
        $array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
        $array2 = []; // false
        $array3 = [1]; // true
        $array4 = 1; // false
        $array5 = [null]; // true
        $array6 = [1, '2', '3', 4]; // true
        $array7 = ['1', 2, 3, '4']; // true
        $array8 = ['1' => 1, 2, 3, '4' => 4]; // true
        $array9 = [-1 => 1, 2 => 2]; // true
        $array10 = [0 => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array11 = ['0' => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array11 = [0 => 1, 1 => 2, 2 => 3, '4' => 4]; // true
        $array12 = ['a0' => 1, 1 => 2, 2 => 3, 4 => 4]; // false
        $array13 = [0 => 1, 1 => 2, 2 => 3, 'a4' => 4]; // false
        $array14 = [0.5 => 1, 1 => 2, 2 => 3, 3.5 => 4]; // true
        $array15 = [false => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array16 = [true => 1, 0 => 2, 2 => 3, 4 => 4]; // true
        $array17 = [null => 1, 0 => 2, 2 => 3, 4 => 4]; // false
        $array18 = [0 => 1, 0 => 2, 2 => 3, false => 4]; // true
        $array19 = [50 => 1, 2 => 3, 0 => 4]; // true
        $array20 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // true

        $this->assertTrue(Arr::isFirstLastWithIntKeys($array1));
        $this->assertFalse(Arr::isFirstLastWithIntKeys($array2));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array3));
        $this->assertFalse(Arr::isFirstLastWithIntKeys($array4));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array5));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array6));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array7));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array8));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array9));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array10));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array11));
        $this->assertFalse(Arr::isFirstLastWithIntKeys($array12));
        $this->assertFalse(Arr::isFirstLastWithIntKeys($array13));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array14));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array15));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array16));
        $this->assertFalse(Arr::isFirstLastWithIntKeys($array17));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array18));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array19));
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array20));
    }

    public function testIsArrayWithIntKeys()
    {
        $array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
        $array2 = []; // false
        $array3 = [1]; // true
        $array4 = 1; // false
        $array5 = [null]; // true
        $array6 = [1, '2', '3', 4]; // true
        $array7 = ['1', 2, 3, '4']; // true
        $array8 = ['1' => 1, 2, 3, '4' => 4]; // true
        $array9 = [-1 => 1, 2 => 2]; // true
        $array10 = [0 => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array11 = ['0' => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array11 = [0 => 1, 1 => 2, 2 => 3, '4' => 4]; // true
        $array12 = ['a0' => 1, 1 => 2, 2 => 3, 4 => 4]; // false
        $array13 = [0 => 1, 1 => 2, 2 => 3, 'a4' => 4]; // false
        $array14 = [0.5 => 1, 1 => 2, 2 => 3, 3.5 => 4]; // true
        $array15 = [false => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array16 = [true => 1, 0 => 2, 2 => 3, 4 => 4]; // true
        $array17 = [null => 1, 0 => 2, 2 => 3, 4 => 4]; // false
        $array18 = [0 => 1, 0 => 2, 2 => 3, false => 4]; // true
        $array19 = [50 => 1, 2 => 3, 0 => 4]; // true
        $array20 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false
        $array21 = ['a50' => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false
        $array22 = [50 => 1, 2 => 3, 3 => 4, 'a0' => 1]; // false

        $this->assertTrue(Arr::isIntKeys($array1));
        $this->assertFalse(Arr::isIntKeys($array2));
        $this->assertTrue(Arr::isIntKeys($array3));
        $this->assertFalse(Arr::isIntKeys($array4));
        $this->assertTrue(Arr::isIntKeys($array5));
        $this->assertTrue(Arr::isIntKeys($array6));
        $this->assertTrue(Arr::isIntKeys($array7));
        $this->assertTrue(Arr::isIntKeys($array8));
        $this->assertTrue(Arr::isIntKeys($array9));
        $this->assertTrue(Arr::isIntKeys($array10));
        $this->assertTrue(Arr::isIntKeys($array11));
        $this->assertFalse(Arr::isIntKeys($array12));
        $this->assertFalse(Arr::isIntKeys($array13));
        $this->assertTrue(Arr::isIntKeys($array14));
        $this->assertTrue(Arr::isIntKeys($array15));
        $this->assertTrue(Arr::isIntKeys($array16));
        $this->assertFalse(Arr::isIntKeys($array17));
        $this->assertTrue(Arr::isIntKeys($array18));
        $this->assertTrue(Arr::isIntKeys($array19));
        $this->assertFalse(Arr::isIntKeys($array20));
        $this->assertFalse(Arr::isIntKeys($array21));
        $this->assertFalse(Arr::isIntKeys($array22));
    }

    public function testRemoveNullRecurcive()
    {
        $array1 = [];
        $result1 = [];

        $array2 = null;
        $result2 = null;

        $array3 = [1, -2, 'a3', '4', 0, ''];
        $result3 = [1, -2, 'a3', '4', 0, ''];

        $array4 = [1, -2, 'a3', null];
        $result4 = [1, -2, 'a3'];

        $array5 = [1, -2, 0, '', []];
        $result5 = [1, -2, 0, '', []];

        $array6 = [1, -2, 'a3' => [1, 2, 3], null];
        $result6 = [1, -2, 'a3' => [1, 2, 3]];

        $array7 = [
            1,
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [],
                ]
            ],
            null
        ];
        $result7 = [1, -2, 'a3' => [1, 2, 'a3.3' => [1, 2, 3, 3 => []]]];

        $array8 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, [], null],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           null,
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            null,
            '',
            0,
            false,
        ];
        $result8 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, []],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            4 => '',
            5 => 0,
            6 => false,
        ];

        $array9 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, [], null],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           null,
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            null,
            '',
            0,
            false,
        ];
        $result9 = [
            1 => [
                1,
                2,
                2 => [1, 2, 3, 4, []],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    3 => [
                        1,
                        2,
                        3,
                        3 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           5 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            4 => '',
            5 => 0,
            6 => false,
        ];

        $this->assertEquals(Arr::removeNullRecurcive($array1), $result1);
        $this->assertEquals(Arr::removeNullRecurcive($array2), $result2);
        $this->assertEquals(Arr::removeNullRecurcive($array3), $result3);
        $this->assertEquals(Arr::removeNullRecurcive($array4), $result4);
        $this->assertEquals(Arr::removeNullRecurcive($array5), $result5);
        $this->assertEquals(Arr::removeNullRecurcive($array6), $result6);
        $this->assertEquals(Arr::removeNullRecurcive($array7), $result7);
        $this->assertEquals(Arr::removeNullRecurcive($array8, false), $result8);
        $this->assertEquals(Arr::removeNullRecurcive($array9, true), $result9);
    }

    public function testRemoveEmptyRecurcive()
    {
        $array1 = [];
        $result1 = [];

        $array2 = null;
        $result2 = null;

        $array3 = [1, -2, 'a3', '4', 0, ''];
        $result3 = [1, -2, 'a3', '4'];

        $array4 = [1, -2, 'a3', null];
        $result4 = [1, -2, 'a3'];

        $array5 = [1, -2, 'a3' => null, null, 0, '', []];
        $result5 = [1, -2];

        $array6 = [1, -2, 'a3' => [1, 2, 3], null];
        $result6 = [1, -2, 'a3' => [1, 2, 3]];

        $array7 = [
            1,
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [],
                ]
            ],
            null
        ];
        $result7 = [1, -2, 'a3' => [1, 2, 'a3.3' => [1, 2, 3]]];

        $array8 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, [], null],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           null,
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            null,
            '',
            0,
            false,
        ];
        $result8 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4],
                4 => 4,
            ],
            2 => -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    0 => 1,
                    1 => 2,
                    2 => 3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           1 => 2,
                           2 => true,
                           4 => ' ',
                           12 => [1, 2],
                        ]
                    ],
                ]
            ],
        ];

        $array9 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, [], null],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           null,
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            null,
            '',
            0,
            false,
        ];
        $result9 = [
            1 => [
                1,
                2,
                2 => [1, 2, 3, 4],
                3 => 4,
            ],
            2 => -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    0 => 1,
                    1 => 2,
                    2 => 3,
                    3 => [
                        1,
                        2,
                        3,
                        3 => [
                           0 => 2,
                           1 => true,
                           2 => ' ',
                           3 => [1, 2],
                        ]
                    ],
                ]
            ],
        ];

        $this->assertEquals(Arr::removeEmptyRecurcive($array1), $result1);
        $this->assertEquals(Arr::removeEmptyRecurcive($array2), $result2);
        $this->assertEquals(Arr::removeEmptyRecurcive($array3), $result3);
        $this->assertEquals(Arr::removeEmptyRecurcive($array4), $result4);
        $this->assertEquals(Arr::removeEmptyRecurcive($array5), $result5);
        $this->assertEquals(Arr::removeEmptyRecurcive($array6), $result6);
        $this->assertEquals(Arr::removeEmptyRecurcive($array7), $result7);
        $this->assertEquals(Arr::removeEmptyRecurcive($array8, false), $result8);
        $this->assertEquals(Arr::removeEmptyRecurcive($array9, true), $result9);
    }

    public function testRemoveNotScalar()
    {
        $array1 = null;
        $result1 = [];

        $array2 = [];
        $result2 = [];

        $array3 = [1, 2, 3, 4, 0];
        $result3 = [1, 2, 3, 4, 0];

        $array4 = [1, 2, null, '', [], new stdClass, false, 0];
        $result4 = [1, 2, 3 => '', 6 => false, 7 => 0];

        $this->assertEquals(Arr::removeNotScalar($array1), $result1);
        $this->assertEquals(Arr::removeNotScalar($array2), $result2);
        $this->assertEquals(Arr::removeNotScalar($array3), $result3);
        $this->assertEquals(Arr::removeNotScalar($array4), $result4);
    }

    public function testRemoveNull()
    {
        $array1 = null;
        $result1 = [];

        $array2 = [];
        $result2 = [];

        $array3 = [1, 2, 3, 4, 0, null];
        $result3 = [1, 2, 3, 4, 0];

        $array4 = [1, 2, null, '', [], new stdClass, false, 0];
        $result4 = [1, 2, 3 => '', [], new stdClass, false, 0];

        $this->assertEquals(Arr::removeNull($array1), $result1);
        $this->assertEquals(Arr::removeNull($array2), $result2);
        $this->assertEquals(Arr::removeNull($array3), $result3);
        $this->assertEquals(Arr::removeNull($array4), $result4);
    }

    public function testRemoveEmpty()
    {
        $array1 = null;
        $result1 = [];

        $array2 = [];
        $result2 = [];

        $array3 = [1, 2, 3, 4, 0];
        $result3 = [1, 2, 3, 4];

        $array4 = [1, 2, null, '', [], new stdClass, false, 0];
        $result4 = [1, 2, 5 => new stdClass];

        $this->assertEquals(Arr::removeEmpty($array1), $result1);
        $this->assertEquals(Arr::removeEmpty($array2), $result2);
        $this->assertEquals(Arr::removeEmpty($array3), $result3);
        $this->assertEquals(Arr::removeEmpty($array4), $result4);
    }

    public function testTrim()
    {
        $array1 = null;
        $result1 = [];

        $array2 = [];
        $result2 = [];

        $array3 = [
            1, 2, 3, 4, '', ' ', '    ', 'a', '        array     ', "\n", [],
            null, new stdClass, false,
        ];
        $result3 = [
            1, 2, 3, 4, '', '', '', 'a', 'array', '', [],
            null, new stdClass, false,
        ];

        $this->assertEquals(Arr::trim($array1), $result1);
        $this->assertEquals(Arr::trim($array2), $result2);
        $this->assertEquals(Arr::trim($array3), $result3);
    }
}
