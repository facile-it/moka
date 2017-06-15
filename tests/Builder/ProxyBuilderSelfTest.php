<?php
declare(strict_types=1);

namespace Tests\Builder;

use Moka\Builder\ProxyBuilder;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Traits\MokaCleanerTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use Tests\Proxy\MockFakeClass;
use Tests\TestTrait;

class ProxyBuilderSelfTest extends TestCase
{
    use TestTrait;
    use MokaCleanerTrait;

    /**
     * @var ProxyBuilder
     */
    private $proxyBuilder;

    public function setUp()
    {
        $this->proxyBuilder = new ProxyBuilder(
            $this->mock(MockingStrategyInterface::class)->serve()
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

    private function decorateSuccessfulMockGenerator()
    {
        $this->mock(MockingStrategyInterface::class)->stub([
            'get' => $this->mock(MockFakeClass::class)->serve()
        ]);
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
}
