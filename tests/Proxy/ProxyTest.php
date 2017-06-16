<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Tests\TestClass;

class ProxyTest extends TestCase
{
    /**
     * @var Proxy
     */
    private $proxy;

    /**
     * @var MockingStrategyInterface|MockObject
     */
    private $mockingStrategy;

    public function setUp()
    {
        $this->mockingStrategy = $this->getMockBuilder(MockingStrategyInterface::class)
            ->getMock();

        $this->proxy = new Proxy(
            TestClass::class,
            $this->mockingStrategy
        );
    }

    public function testServeSuccess()
    {
        $this->mockingStrategy->method('get')->willReturn(
            $this->getMockBuilder(TestClass::class)->getMock()
        );

        $this->assertInstanceOf(TestClass::class, $this->proxy->serve());
    }

    public function testServeFailure()
    {
        $this->mockingStrategy->method('get')
            ->willThrowException(new MockNotCreatedException());

        $this->expectException(MockNotServedException::class);
        $this->proxy->serve();
    }
}
