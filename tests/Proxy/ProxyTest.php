<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 14/06/2017
 * Time: 13:48
 */

namespace Tests\Proxy;


use Moka\Proxy\Proxy;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        $this->proxy = new Proxy(
            $this->getMockBuilder(MockFakeClass::class)
                ->disableOriginalConstructor()
                ->getMock()
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
