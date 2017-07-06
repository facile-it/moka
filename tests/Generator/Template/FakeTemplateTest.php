<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class FakeTemplateTest extends TestCase
{
    public function testGetVisibilitySuccess()
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
                FakeTemplate::checkVisibility($property)
            );
        }
    }

    public function testGetVisibilityFailure()
    {
        /** @var \Reflector $reflector */
        $reflector = $this->getMockBuilder(\Reflector::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(InvalidArgumentException::class);

        FakeTemplate::checkVisibility($reflector);
    }

    public function testGetVisibilityImpossibleFailure()
    {
        /** @var \ReflectionProperty|MockObject $reflector */
        $reflector = $this->getMockBuilder(\ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflector->method('isPublic')->willReturn(false);
        $reflector->method('isProtected')->willReturn(false);
        $reflector->method('isPrivate')->willReturn(false);

        $this->expectException(\RuntimeException::class);

        FakeTemplate::checkVisibility($reflector);
    }
}
