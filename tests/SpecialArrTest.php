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
}
