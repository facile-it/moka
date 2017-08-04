<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Tests\FooTestClass;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyTraitTest extends TestCase
{
    /**
     * @var ProxyInterface|TestProxy
     */
    private $proxy;

    /**
     * @var MockingStrategyInterface|MockObject
     */
    private $mockingStrategy;

    /**
     * @var FooTestClass|MockObject
     */
    private $mock;

    protected function setUp()
    {
        $this->proxy = new TestProxy();

        $this->mockingStrategy = $this->getMockBuilder(MockingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mock = $this->getMockBuilder(FooTestClass::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mock->property = null;

        $this->proxy->__moka_setMock($this->mock);
    }

    public function testStub()
    {
        $this->mockingStrategy
            ->expects($this->once())
            ->method('decorate')
            ->with(
                $this->equalTo($this->mock),
                ['getInt' => 1138]
            );

        $this->proxy->__moka_setMockingStrategy($this->mockingStrategy);

        $this->proxy->stub([
            'getInt' => 1138
        ]);
    }

    public function testCallSuccess()
    {
        $this->mock
            ->expects($this->once())
            ->method('getSelf')
            ->willReturn($this->mock);

        $this->mockingStrategy
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->mock);

        $this->proxy->__moka_setMockingStrategy($this->mockingStrategy);

        $this->assertSame($this->mock, $this->proxy->getSelf());
    }

    public function testCallFailure()
    {
        $this->mockingStrategy
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->mock);

        $this->proxy->__moka_setMockingStrategy($this->mockingStrategy);

        $this->expectException(\Error::class);

        $this->proxy->fakeMethod();
    }

    public function testCallWithoutStrategyFailure()
    {
        $this->assertNull($this->proxy->getSelf());
    }

    public function testGetSuccess()
    {
        $this->proxy->__moka_setMockingStrategy($this->mockingStrategy);

        $this->mockingStrategy
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->mock);

        $this->mockingStrategy
            ->expects($this->never())
            ->method('call');

        $this->proxy->stub([
            '$property' => true
        ]);

        $this->proxy->property;
    }

    public function testGetFailure()
    {
        $this->mockingStrategy
            ->expects($this->once())
            ->method('call')
            ->willThrowException(new \Exception());

        $this->proxy->__moka_setMockingStrategy($this->mockingStrategy);

        $this->expectException(\Exception::class);

        $this->proxy->getSelf;
    }

    public function testGetWithoutStrategyFailure()
    {
        $this->assertNull($this->proxy->getSelf);
    }
}
