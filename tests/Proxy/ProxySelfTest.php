<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\Proxy;
use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxySelfTest extends TestCase
{
    use MokaTrait;
    use MokaCleanerTrait;

    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        $this->proxy = new Proxy(
            $this->mock(MockFakeClass::class)->serve()
        );
    }

    public function testServe()
    {
        $this->assertInstanceOf(MockObject::class, $this->proxy->serve());
    }

    public function testStubScalarValue()
    {
        $this->proxy->stub([
            'isValid' => true
        ]);

        $this->assertTrue($this->proxy->serve()->isValid());
    }

    public function testStubThrowException()
    {
        $this->expectException(\Exception::class);

        $this->proxy->stub([
            'throwException' => new \Exception()
        ]);

        $this->proxy->serve()->throwException();
    }
}
