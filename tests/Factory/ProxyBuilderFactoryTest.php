<?php

namespace Tests\Factory;


use Moka\Builder\ProxyBuilder;
use Moka\Factory\ProxyBuilderFactory;

class ProxyBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $proxyBuilder1 = ProxyBuilderFactory::get();
        $proxyBuilder2 = ProxyBuilderFactory::get();

        $this->assertInstanceOf(ProxyBuilder::class, $proxyBuilder1);
        $this->assertInstanceOf(ProxyBuilder::class, $proxyBuilder2);

        $this->assertSame($proxyBuilder1, $proxyBuilder2);
    }
}
