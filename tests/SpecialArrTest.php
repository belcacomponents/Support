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

        $target = 'finfo.size';

        $this->assertEquals(SpecialArr::pullThroughSeparator($array, $target), 998);
    }
}
