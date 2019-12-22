<?php

declare(strict_types=1);

namespace Belca\Support\Tests;

use PHPUnit\Framework\TestCase;
use Belca\Support\Tests\Enum\FirstConstants;
use Belca\Support\Tests\Enum\SecondConstants;
use Belca\Support\Tests\Enum\ThirdConstants;

final class AbstractEnumTest extends TestCase
{
    public function testGetConstans()
    {
        $this->assertEquals(FirstConstants::getConstants(), [
            'USER' => 'user',
            'SUPERUSER' => 'superuser',
            'CLIENT' => 'client',
            'MODERATOR' => 'moderator',
            'SUPERMODERATOR' => 'superuser',
        ]);

        $this->assertEquals(SecondConstants::getConstants(), [
            'USER' => 'user',
            'SUPERUSER' => 'root',
            'CLIENT' => 'client',
            'MODERATOR' => 'moderator',
            'SUPERMODERATOR' => 'superuser',
            'VIEWER' => 'viewer',
            'CHECKER' => 'checker',
        ]);

        $this->assertEquals(ThirdConstants::getConstants(), [
            'USER' => 'user',
            'SUPERUSER' => 'root',
            'CLIENT' => 'client',
            'MODERATOR' => 'moderator',
            'SUPERMODERATOR' => 'superuser',
            'VIEWER' => 'viewer',
            'CHECKER' => 'checker',
            'TESTER' => 'tester',
            'DEVELOPER' => 'developer',
            'STAGER' => 'stager',
        ]);
    }

    public function testGetLastConstants()
    {
        $this->assertEquals(FirstConstants::getLastConstants(), [
            'USER' => 'user',
            'SUPERUSER' => 'superuser',
            'CLIENT' => 'client',
            'MODERATOR' => 'moderator',
            'SUPERMODERATOR' => 'superuser',
        ]);

        $this->assertEquals(SecondConstants::getLastConstants(), [
            'SUPERUSER' => 'root',
            'VIEWER' => 'viewer',
            'CHECKER' => 'checker',
        ]);

        $this->assertEquals(ThirdConstants::getLastConstants(), [
            'TESTER' => 'tester',
            'DEVELOPER' => 'developer',
            'STAGER' => 'stager',
        ]);
    }

    public function testGetParentConstants()
    {
        $this->assertEquals(FirstConstants::getParentConstants(), []);

        $this->assertEquals(SecondConstants::getParentConstants(), [
            'USER' => 'user',
            'SUPERUSER' => 'superuser',
            'CLIENT' => 'client',
            'MODERATOR' => 'moderator',
            'SUPERMODERATOR' => 'superuser',
        ]);

        $this->assertEquals(ThirdConstants::getParentConstants(), [
            'USER' => 'user',
            'SUPERMODERATOR' => 'superuser',
            'SUPERUSER' => 'root',
            'CLIENT' => 'client',
            'MODERATOR' => 'moderator',
            'VIEWER' => 'viewer',
            'CHECKER' => 'checker',
        ]);
    }

    public function testGetConst()
    {
        $this->assertEquals(FirstConstants::getConst('USER'), 'user');
        $this->assertEquals(FirstConstants::getConst('SUPERUSER'), 'superuser');
        $this->assertEquals(FirstConstants::getConst('MODERATOR'), 'moderator');
        $this->assertEquals(FirstConstants::getConst('ROOT'), null);

        $this->assertEquals(SecondConstants::getConst('USER'), 'user');
        $this->assertEquals(SecondConstants::getConst('SUPERUSER'), 'root');
        $this->assertEquals(SecondConstants::getConst('MODERATOR'), 'moderator');
        $this->assertEquals(SecondConstants::getConst('ROOT'), null);

        $this->assertEquals(ThirdConstants::getConst('USER'), 'user');
        $this->assertEquals(ThirdConstants::getConst('SUPERUSER'), 'root');
        $this->assertEquals(ThirdConstants::getConst('DEVELOPER'), 'developer');
        $this->assertEquals(ThirdConstants::getConst('TESTER'), 'tester');
        $this->assertEquals(ThirdConstants::getConst('ROOT'), null);

        $this->assertNotEquals(ThirdConstants::getConst('SUPERUSER'), 'superuser');
    }

    public function testGetRawConst()
    {
        $this->assertEquals(FirstConstants::USER, 'user');
        // $this->assertEquals(FirstConstants::ROOT, null); // error. it's ok

        $this->assertEquals(SecondConstants::SUPERUSER, 'root');
    }

    public function testIsDefined()
    {
        $this->assertTrue(FirstConstants::isDefined('USER'));
        $this->assertTrue(FirstConstants::isDefined('SUPERUSER'));
        $this->assertFalse(FirstConstants::isDefined('ROOT'));

        $this->assertTrue(SecondConstants::isDefined('USER'));
        $this->assertTrue(SecondConstants::isDefined('SUPERUSER'));
        $this->assertFalse(SecondConstants::isDefined('TESTER'));
        $this->assertFalse(SecondConstants::isDefined('ROOT'));

        $this->assertTrue(ThirdConstants::isDefined('USER'));
        $this->assertTrue(ThirdConstants::isDefined('SUPERUSER'));
        $this->assertTrue(ThirdConstants::isDefined('DEVELOPER'));
        $this->assertTrue(ThirdConstants::isDefined('TESTER'));
        $this->assertFalse(ThirdConstants::isDefined('ROOT'));
    }

    public function testGetDefault()
    {
        $this->assertEquals(FirstConstants::getDefault(), 'user');
        $this->assertEquals(SecondConstants::getDefault(), 'user');
        $this->assertEquals(ThirdConstants::getDefault(), 'client');
        $this->assertNotEquals(FirstConstants::getDefault(), 'root');
        $this->assertNotEquals(ThirdConstants::getDefault(), 'user');
    }
}
