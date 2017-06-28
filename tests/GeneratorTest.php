<?php
declare(strict_types=1);

namespace Tests;

use Coffee\Generator\ProxyClassGenerator;
use Moka\Proxy\ProxyInterface;
use PHPUnit\Framework\TestCase;

class CoffeeTest extends TestCase
{
    /**
     * @var \Coffee\Generator\ProxyClassGenerator
     */
    private $proxyGenerator;

    public function setUp()
    {
        $this->proxyGenerator = new ProxyClassGenerator();
    }

    public function testCreation()
    {
        /** @var ProxyInterface|FooTestClass $proxy */
        $proxy = $this->proxyGenerator->generate($this->getMockBuilder(FooTestClass::class)->getMock());

        $this->assertInstanceOf(ProxyInterface::class, $proxy);
    }

    public function testForwardPHPUnit()
    {
        $mock = $this->getMockBuilder(FooTestClass::class)->getMock();

        $mock->expects($this->any())
            ->method('something')
            ->willReturn(new \stdClass());

        /** @var ProxyInterface|FooTestClass $proxy */
        $proxy = $this->proxyGenerator->generate($mock);

        $this->assertInstanceOf(\stdClass::class, $proxy->something());

        $this->assertInstanceOf(ProxyInterface::class, $proxy);

    }

    public function testUseMockEngineMethod()
    {
        $mock = $this->getMockBuilder(FooTestClass::class)->getMock();

        /** @var ProxyInterface|FooTestClass $proxy */
        $proxy = $this->proxyGenerator->generate($mock);

        $proxy->expects($this->any())
            ->method('something')
            ->willReturn(new \stdClass());

        $this->assertInstanceOf(\stdClass::class, $proxy->something());

        $this->assertInstanceOf(ProxyInterface::class, $proxy);
    }

    public function testForwardProphecy()
    {
        $mock = $this->prophesize(FooTestClass::class);
//        $mock = $this->getMockBuilder(FooTestClass::class)->getMock();

        $mock->something()->willReturn(new \stdClass());

        /** @var ProxyInterface|FooTestClass $proxy */
        $proxy = $this->proxyGenerator->generate($mock);

        $this->assertInstanceOf(\stdClass::class, $proxy->something());

        $this->assertInstanceOf(ProxyInterface::class, $proxy);

    }

//    public function testUseMockEngineMethodProphecy()
//    {
//        $mock = $this->prophesize(FooTestClass::class);
//        /** @var ProxyInterface|FooTestClass $proxy */
//        $proxy = $this->proxyGenerator->generate($mock);
//
//        $proxy->something()->willReturn(new \stdClass());
//
//        $this->assertInstanceOf(\stdClass::class, $proxy->something());
//
//        $this->assertInstanceOf(ProxyInterface::class, $proxy);
//    }
}
