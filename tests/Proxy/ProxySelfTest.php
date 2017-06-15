<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Tests\TestTrait;

class ProxySelfTest extends TestCase
{
    use TestTrait;
    use MokaCleanerTrait;

    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        $this->proxy = new Proxy(
            \stdClass::class,
            $this->mock(MockingStrategyInterface::class)->stub([
                    'get' => $this->mock(MockFakeClass::class)->serve(),
                ])
                ->serve()
        );
    }

    public function testServe()
    {
        $this->assertInstanceOf(MockFakeClass::class, $this->proxy->serve());
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
