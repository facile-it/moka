<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyTest extends TestCase
{
    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        $mockingStrategy = $this->getMockBuilder(MockingStrategyInterface::class)
            ->getMock();
        $mockingStrategy->method('get')->willReturn(
            $this->getMockBuilder(MockFakeClass::class)->getMock()
        );
        $this->proxy = new Proxy(
            MockFakeClass::class,
            $mockingStrategy
        );
    }

    public function testServe()
    {
        $this->assertInstanceOf(MockObject::class, $this->proxy->serve());
    }

//    public function testStubScalarValue()
//    {
//        $this->proxy->stub([
//            'isValid' => true
//        ]);
//
//        $this->assertTrue($this->proxy->serve()->isValid());
//    }
//
//    public function testStubThrowException()
//    {
//        $this->expectException(\Exception::class);
//
//        $this->proxy->stub([
//            'throwException' => new \Exception()
//        ]);
//
//        $this->proxy->serve()->throwException();
//    }
}
