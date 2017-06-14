<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 14/06/2017
 * Time: 13:48
 */

namespace Tests\Proxy;


use Moka\Moka;
use Moka\Proxy\Proxy;
use Moka\Traits\MokaTrait;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    use MokaTrait;
    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        Moka::clean();
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
