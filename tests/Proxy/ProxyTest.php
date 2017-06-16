<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;
use Tests\TestClass;

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
            $this->getMockBuilder(TestClass::class)->getMock()
        );

        $this->proxy = new Proxy(
            TestClass::class,
            $mockingStrategy
        );
    }

    public function testServe()
    {
        $this->assertInstanceOf(TestClass::class, $this->proxy->serve());
    }
}
