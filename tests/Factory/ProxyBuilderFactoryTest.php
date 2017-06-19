<?php
declare(strict_types=1);

namespace Tests\Factory;

use Moka\Builder\ProxyBuilder;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;

class ProxyBuilderFactoryTest extends TestCase
{
    public function testGet()
    {
        $proxyBuilder1 = ProxyBuilderFactory::get(
            $this->getMockBuilder(MockingStrategyInterface::class)->getMock()
        );
        $proxyBuilder2 = ProxyBuilderFactory::get(
            $this->getMockBuilder(MockingStrategyInterface::class)->getMock()
        );

        $this->assertInstanceOf(ProxyBuilder::class, $proxyBuilder1);
        $this->assertInstanceOf(ProxyBuilder::class, $proxyBuilder2);

        $this->assertSame($proxyBuilder1, $proxyBuilder2);
    }
}
