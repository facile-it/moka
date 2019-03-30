<?php
declare(strict_types=1);

namespace Tests\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Moka\Generator\ASTFactory\getVisibility;

class GetVisibilityTest extends TestCase
{
    public function testGetVisibilitySuccess(): void
    {
        $reflectionClass = new \ReflectionClass(new class {
            public $public;

            protected $protected;

            private $private;
        });

        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $this->assertEquals(
                $property->getName(),
                getVisibility($property)
            );
        }
    }

    public function testGetVisibilityFailure(): void
    {
        /** @var \Reflector $reflector */
        $reflector = $this->getMockBuilder(\Reflector::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(InvalidArgumentException::class);

        getVisibility($reflector);
    }

    public function testGetVisibilityImpossibleFailure(): void
    {
        /** @var \ReflectionProperty|MockObject $reflector */
        $reflector = $this->getMockBuilder(\ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflector->method('isPublic')->willReturn(false);
        $reflector->method('isProtected')->willReturn(false);
        $reflector->method('isPrivate')->willReturn(false);

        $this->expectException(\RuntimeException::class);

        getVisibility($reflector);
    }
}
