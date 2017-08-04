<?php
declare(strict_types=1);

namespace Tests\Generator;

use Moka\Generator\ProxyGenerator;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Tests\FooTestClass;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyGeneratorTest extends TestCase
{
    /**
     * @var ProxyGenerator
     */
    private $proxyGenerator;

    public function setUp()
    {
        $mock = $this->getMockBuilder(FooTestClass::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var MockingStrategyInterface|MockObject $mockingStrategy */
        $mockingStrategy = $this->getMockBuilder(MockingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockingStrategy
            ->method('build')
            ->willReturn($mock);

        $mockingStrategy
            ->method('get')
            ->willReturn($mock);

        $mockingStrategy
            ->method('call')
            ->willReturnCallback(function ($target, $name, $arguments) {
                return $target->$name(...$arguments);
            });

        $this->proxyGenerator = new ProxyGenerator($mockingStrategy);
    }

    public function testGet()
    {
        $proxy = $this->proxyGenerator->get(FooTestClass::class);

        $this->assertInstanceOf(ProxyInterface::class, $proxy);
        $this->assertInstanceOf(FooTestClass::class, $proxy);
    }

    public function testCall()
    {
        /** @var ProxyInterface|MockObject|FooTestClass $proxy */
        $proxy = $this->proxyGenerator->get(FooTestClass::class);

        $proxy->method('getInt')
            ->willReturn(1138);

        $this->assertInstanceOf(ProxyInterface::class, $proxy);
        $this->assertEquals(1138, $proxy->getInt());
    }
}
