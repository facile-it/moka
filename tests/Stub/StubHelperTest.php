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
    public function testStripName(): void
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

    public function testValidateNameFailure1(): void
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('0name');
    }

    public function testValidateNameFailure2(): void
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('na-me');
    }

    public function testValidateNameFailure3(): void
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('na me');
    }

    public function testValidateNameFailure4(): void
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('na.me');
    }

    public function testValidateNameFailure5(): void
    {
        $this->expectException(InvalidArgumentException::class);

        stripNameAndValidate('');
    }

    public function testStaticNameSuccess(): void
    {
        $this->assertTrue(isStaticName('::_name'));

        validateStaticName('::$_name');
    }

    public function testStaticNameFailure(): void
    {
        $this->assertFalse(isStaticName('name0'));

        $this->expectException(InvalidArgumentException::class);

        validateStaticName('$name0');
    }

    public function testPropertyNameSucess(): void
    {
        $this->assertTrue(isPropertyName('$_name'));

        validatePropertyName('::$_name');
    }

    public function testPropertyNameFailure(): void
    {
        $this->assertFalse(isPropertyName('name0'));

        $this->expectException(InvalidArgumentException::class);

        validatePropertyName('::name0');
    }

    public function testMethodNameSucess(): void
    {
        $this->assertTrue(isMethodName('_name'));

        validateMethodName('::_name');
    }

    public function testMethodNameFailure(): void
    {
        $this->assertFalse(isMethodName('$name0'));

        $this->expectException(InvalidArgumentException::class);

        validateMethodName('::$name0');
    }
}
