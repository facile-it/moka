<?php
declare(strict_types=1);

namespace Tests\Stub;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\StubHelper;
use PHPUnit\Framework\TestCase;

class StubHelperTest extends TestCase
{
    public function testStripName()
    {
        foreach ([
                     '_name' => '_name',
                     '$name0' => 'name0',
                     '::name' => 'name',
                     '::$name' => 'name'
                 ] as $name => $strippedName) {
            $this->assertEquals(
                $strippedName,
                StubHelper::stripName($name)
            );
        }
    }

    public function testValidateNameFailure0()
    {
        $this->expectException(InvalidArgumentException::class);

        StubHelper::stripName('0name');
    }

    public function testValidateNameFailure1()
    {
        $this->expectException(InvalidArgumentException::class);

        StubHelper::stripName('na-me');
    }

    public function testValidateNameFailure2()
    {
        $this->expectException(InvalidArgumentException::class);

        StubHelper::stripName('na me');
    }

    public function testValidateNameFailure3()
    {
        $this->expectException(InvalidArgumentException::class);

        StubHelper::stripName('na.me');
    }

    public function testValidateNameFailure4()
    {
        $this->expectException(InvalidArgumentException::class);

        StubHelper::stripName('');
    }

    public function testStaticNameSucess()
    {
        $this->assertTrue(StubHelper::isStaticName('::_name'));

        StubHelper::validateStaticName('::$_name');
    }

    public function testStaticNameFailure()
    {
        $this->assertFalse(StubHelper::isStaticName('name0'));

        $this->expectException(InvalidArgumentException::class);

        StubHelper::validateStaticName('$name0');
    }

    public function testPropertyNameSucess()
    {
        $this->assertTrue(StubHelper::isPropertyName('$_name'));

        StubHelper::validatePropertyName('::$_name');
    }

    public function testPropertyNameFailure()
    {
        $this->assertFalse(StubHelper::isPropertyName('name0'));

        $this->expectException(InvalidArgumentException::class);

        StubHelper::validatePropertyName('::name0');
    }
}
