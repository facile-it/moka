<?php

namespace Tests\Builder;


use Moka\Builder\ProxyBuilder;
use Moka\Exception\MockNotCreatedException;
use Moka\Proxy\Proxy;
use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaTrait;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;

class ProxyBuilderSelfTest extends \PHPUnit_Framework_TestCase
{
    use MokaTrait;
    use MokaCleanerTrait;

    /**
     * @var ProxyBuilder
     */
    private $proxyBuilder;

    public function setUp()
    {
        $this->proxyBuilder = new ProxyBuilder(
            $this->mock(MockGenerator::class)->serve()
        );
    }

    public function testGetProxySuccess()
    {
        $this->decorateSuccessfulMockGenerator();

        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class);
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class);

        $this->assertInstanceOf(Proxy::class, $proxy1);

        $this->assertSame($proxy1, $proxy2);
    }

    public function testGetProxySuccessWithAlias()
    {
        $this->decorateSuccessfulMockGenerator();

        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class, 'bar');
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class, 'foo');

        $this->assertInstanceOf(Proxy::class, $proxy1);
        $this->assertInstanceOf(Proxy::class, $proxy2);

        $this->assertNotSame($proxy1, $proxy2);
    }

    public function testGetProxyThrowException()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->mock(MockGenerator::class)->stub([
            'getMock' => new \Exception()
        ]);

        $this->proxyBuilder->getProxy(\stdClass::class, 'acme');
    }

    private function decorateSuccessfulMockGenerator()
    {
        $this->mock(MockGenerator::class)->stub([
            'getMock' => $this->mock(\stdClass::class)->serve()
        ]);
    }
}
