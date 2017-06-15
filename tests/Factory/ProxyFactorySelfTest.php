<?php

namespace Tests\Factory;

use Moka\Factory\ProxyFactory;
use Moka\Proxy\Proxy;
use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaTrait;
use PHPUnit\Framework\TestCase;

class ProxyFactorySelfTest extends TestCase
{
    use MokaTrait;
    use MokaCleanerTrait;

    public function testGet()
    {
        $mock1 = ProxyFactory::get($this->mock(\stdClass::class)->serve());
        $mock2 = ProxyFactory::get($this->mock(\stdClass::class)->serve());

        $this->assertInstanceOf(Proxy::class, $mock1);
        $this->assertInstanceOf(Proxy::class, $mock2);

        $this->assertNotSame($mock1, $mock2);
    }
}
