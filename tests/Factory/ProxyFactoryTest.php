<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 14/06/2017
 * Time: 13:40
 */

namespace Tests\Factory;


use Moka\Factory\ProxyFactory;
use Moka\Proxy\Proxy;

class ProxyFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder(\stdClass::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGet()
    {
        $mock1 = ProxyFactory::get($this->mock);
        $mock2 = ProxyFactory::get($this->mock);

        $this->assertInstanceOf(Proxy::class, $mock1);
        $this->assertInstanceOf(Proxy::class, $mock2);
        
        $this->assertNotSame($mock1, $mock2);
    }
}
