<?php
declare(strict_types=1);

namespace Tests\Generator;

use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Tests\FooTestClass;
use Tests\ProxyTestClass;

class ProxyTraitTest extends TestCase
{
    /**
     * @var ProxyInterface|ProxyTestClass
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
        $this->proxy = new ProxyTestClass();

        $this->mockingStrategy = $this->getMockBuilder(MockingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mock = $this->getMockBuilder(FooTestClass::class)
            ->disableOriginalConstructor()
            ->getMock();

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
        $this->mockingStrategy
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo($this->mock),
                'getSelf',
                []
            );

        $this->proxy->__moka_setMockingStrategy($this->mockingStrategy);

        $this->proxy->__call('getSelf', []);
    }

    public function testCallFailure()
    {
        $this->assertNull($this->proxy->__call('getSelf', []));
    }
}
