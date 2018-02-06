<?php
declare(strict_types=1);

namespace Tests\Stub;

use Moka\Exception\InvalidArgumentException;
use function Moka\Stub\Helper\isMethodName;
use function Moka\Stub\Helper\isPropertyName;
use function Moka\Stub\Helper\isStaticName;
use function Moka\Stub\Helper\stripNameAndValidate;
use function Moka\Stub\Helper\validateMethodName;
use function Moka\Stub\Helper\validatePropertyName;
use function Moka\Stub\Helper\validateStaticName;
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
                stripNameAndValidate($name)
            );
        }
    }

    public function testValidateNameFailure1()
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('0name');
    }

    public function testValidateNameFailure2()
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('na-me');
    }

    public function testValidateNameFailure3()
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('na me');
    }

    public function testValidateNameFailure4()
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('na.me');
    }

    public function testValidateNameFailure5()
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('');
    }

    public function testStaticNameSuccess()
    {
        $this->assertTrue(isStaticName('::_name'));

        validateStaticName('::$_name');
    }

    public function testStaticNameFailure()
    {
        $this->assertFalse(isStaticName('name0'));

        $this->expectException(InvalidArgumentException::class);

        validateStaticName('$name0');
    }

    public function testPropertyNameSucess()
    {
        $this->assertTrue(isPropertyName('$_name'));

        validatePropertyName('::$_name');
    }

    public function testPropertyNameFailure()
    {
        $this->assertFalse(isPropertyName('name0'));

        $this->expectException(InvalidArgumentException::class);

        validatePropertyName('::name0');
    }

    public function testMethodNameSucess()
    {
        $this->assertTrue(isMethodName('_name'));

        validateMethodName('::_name');
    }

    public function testMethodNameFailure()
    {
        $this->assertFalse(isMethodName('$name0'));

        $this->expectException(InvalidArgumentException::class);

        validateMethodName('::$name0');
    }
}
