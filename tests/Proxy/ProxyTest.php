<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Tests\BarTestClass;
use Tests\FooTestClass;

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

    protected function setUp()
    {
        $mock = $this->getMockBuilder(BarTestClass::class)
            ->getMock();

        $mock->method('getInt')
            ->willReturn(1138);

        $this->mockingStrategy = $this->getMockBuilder(MockingStrategyInterface::class)
            ->getMock();

        $this->mockingStrategy
            ->method('build')
            ->willReturn($mock);

        $this->proxy = new Proxy(
            FooTestClass::class,
            $this->mockingStrategy
        );
    }

    public function testMethodForwardSuccess()
    {
        $this->assertSame(1138, $this->proxy->getInt());
    }

    public function testMethodForwardFailure()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp('/^Call to undefined method/');

        $this->proxy->fakeMethod();
    }

    public function testStubSuccess()
    {
        $this->assertSame($this->proxy, $this->proxy->stub([]));
    }

    public function testStubAndDecorateSuccess()
    {
        $this->proxy->serve();

        $this->assertSame($this->proxy, $this->proxy->stub([]));
    }

    public function testServeSuccess()
    {
        $this->mockingStrategy->method('get')->willReturn(
            $this->getMockBuilder(FooTestClass::class)->getMock()
        );

        $this->assertInstanceOf(FooTestClass::class, $this->proxy->serve());
    }

    public function testServeFailure()
    {
        $this->mockingStrategy->method('get')
            ->willThrowException(new MockNotCreatedException());

        $this->expectException(MockNotServedException::class);
        $this->proxy->serve();
    }
}
