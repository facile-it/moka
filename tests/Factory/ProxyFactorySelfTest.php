<?php
declare(strict_types=1);

namespace Tests\Factory;

use Moka\Factory\ProxyFactory;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Traits\MokaCleanerTrait;
use PHPUnit\Framework\TestCase;
use Tests\TestTrait;

class ProxyFactorySelfTest extends TestCase
{
    use TestTrait;
    use MokaCleanerTrait;

    public function testGet()
    {
        $mock1 = ProxyFactory::get(
            \stdClass::class,
            $this->getMockBuilder(MockingStrategyInterface::class)->getMock()
        );

        $mock2 = ProxyFactory::get(
            \stdClass::class,
            $this->getMockBuilder(MockingStrategyInterface::class)->getMock()
        );

        $this->assertInstanceOf(Proxy::class, $mock1);
        $this->assertInstanceOf(Proxy::class, $mock2);

        $this->assertNotSame($mock1, $mock2);
    }
}
