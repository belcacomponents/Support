<?php

declare(strict_types=1);

namespace Belca\Support\Tests;

use PHPUnit\Framework\TestCase;
use Belca\Support\SpecialArr;

final class SpecialArrTest extends TestCase
{
    public function testOriginalKeys()
    {
        $array1 = ['finfo.created', 'finfo.size' => 'filesize', 'finfo.mime' => 'mime'];
        $result1 = ['finfo.created', 'finfo.size', 'finfo.mime'];

        $array2 = ['finfo.created', 'finfo.size', 'finfo.mime' => 'mime'];
        $result2 = ['finfo.created', 'finfo.size', 'finfo.mime'];

        $array3 = null;
        $result3 = [];

        $array4 = ['finfo.created', 'finfo.size' => ['a' => 'b'], 'finfo.mime' => 'mime'];
        $result4 = ['finfo.created', 'finfo.size', 'finfo.mime'];

        $array5 = [];
        $result5 = [];

        $array6 = [1, 2, 3, 4, 6 => '5'];
        $result6 = [1, 2, 3, 4, 5];

        $this->assertEquals(SpecialArr::originalKeys($array1), $result1);
        $this->assertEquals(SpecialArr::originalKeys($array2), $result2);
        $this->assertEquals(SpecialArr::originalKeys($array3), $result3);
        $this->assertEquals(SpecialArr::originalKeys($array4), $result4);
        $this->assertEquals(SpecialArr::originalKeys($array5), $result5);
        $this->assertEquals(SpecialArr::originalKeys($array6), $result6);
    }

    public function testPullThroughSeparator()
    {
        $array = [
            'finfo' => [
                'size' => 998,
                'mime' => 'image/png',
            ],
        ];

        $this->assertEquals(SpecialArr::pullThroughSeparator($array, 'finfo.size'), 998);
        $this->assertEquals(SpecialArr::pullThroughSeparator($array, 'finfo.siz2e'), null);
        $this->assertEquals(SpecialArr::pullThroughSeparator($array, 'finfo'), [
            'size' => 998,
            'mime' => 'image/png',
        ]);
    }

    public function testDoubleImplode()
    {
        $array = [
            'name' => 'phone',
            'maxlength' => 20,
            'class' => 'input input_type_primary input_width_medium',
            'required' => true,
        ];

        $this->assertEquals(SpecialArr::doubleImplode($array, ["", "=\"", "\""], " "), 'name="phone" maxlength="20" class="input input_type_primary input_width_medium" required');

        // WARNING: функция требует доработки.
        //$this->assertEquals(SpecialArr::doubleImplode($array, ["'", "':'", "'"], ", "), "'name':'phone', 'maxlength':'20', 'class':'input input_type_primary input_width_medium', 'required':true");
    }

    public function testOrderKeysByIndexRules()
    {
        $array = [
            'key1', 'key5', 'key9', 'key15', 'key0', 'key14', 'key10', 'key8', 'key7',
            'key6', 'key4', 'key3', 'key2', 'key2.1', 'key2.3', 'key99', 'key99.5',
            'key100',
        ];

        $indexes = [
            'key0' => [
                'priority' => 0,
                'direction' => -1,
                'nearby_key' => 'key1',
            ],
            'key14' => [
                'priority' => 0,
                'direction' => -1,
                'nearby_key' => 'key15',
            ],
            'key10' => [
                'priority' => 0,
                'direction' => 1,
                'nearby_key' => 'key9',
            ],
            'key8' => [
                'priority' => 0,
                'direction' => -1,
                'nearby_key' => 'key9',
            ],
            'key7' => [
                'priority' => 0,
                'direction' => -1,
                'nearby_key' => 'key8',
            ],
            'key6' => [
                'priority' => -10,
                'direction' => -1,
                'nearby_key' => 'key8',
            ],
            'key4' => [
                'priority' => 0,
                'direction' => 1,
                'nearby_key' => 'key3',
            ],
            'key3' => [
                'priority' => 0,
                'direction' => 1,
                'nearby_key' => 'key2',
            ],
            'key2' => [
                'priority' => 20,
                'direction' => 1,
                'nearby_key' => 'key1',
            ],
            'key2.3' => [
                'priority' => -5,
                'direction' => 1,
                'nearby_key' => 'key2',
            ],
            'key2.1' => [
                'priority' => -5,
                'direction' => 1,
                'nearby_key' => 'key1',
            ],
            'key99' => [
                'priority' => 0,
                'direction' => -1,
                'nearby_key' => 'key99.5',
            ],
            'key99.5' => [
                'priority' => 0,
                'direction' => 1,
                'nearby_key' => 'key99',
            ],
            'key100' => [
                'priority' => 0,
                'direction' => 1,
                'nearby_key' => 'key100',
            ],
        ];

        $keys = SpecialArr::orderKeysByIndexRules($array, $indexes);

        $result = [
          'key0',
          'key1',
          'key2.1',
          'key2',
          'key2.3',
          'key3',
          'key4',
          'key5',
          'key6',
          'key7',
           'key8',
           'key9',
           'key10',
           'key14',
           'key15',
           'key99',
           'key99.5',
           'key100',
        ];

        $this->assertEquals($result, $keys);
    }
}
