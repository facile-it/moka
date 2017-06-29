<?php
declare(strict_types=1);

namespace Tests;

use Moka\Generator\ProxyGenerator;
use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Moka\Proxy\ProxyInterface;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    /**
     * @var ProxyGenerator
     */
    private $proxyGenerator;

    public function setUp()
    {
        $this->proxyGenerator = new ProxyGenerator(
            new PHPUnitMockingStrategy()
        );
    }

    public function testCreation()
    {
        /** @var ProxyInterface|FooTestClass $proxy */
        $proxy = $this->proxyGenerator->get(FooTestClass::class);

        $this->assertInstanceOf(ProxyInterface::class, $proxy);
    }

    public function testUseMockEngineMethod()
    {

        /** @var ProxyInterface|FooTestClass $proxy */
        $proxy = $this->proxyGenerator->get(FooTestClass::class);

        $proxy->expects($this->any())
            ->method('something')
            ->willReturn(new \stdClass());

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
