<?php
declare(strict_types=1);

namespace Tests\Builder;

use Moka\Builder\ProxyBuilder;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyBuilderTest extends TestCase
{
    /**
     * @var ProxyBuilder
     */
    private $proxyBuilder;

    /**
     * @var MockingStrategyInterface|MockObject
     */
    private $mockingStrategy;

    protected function setUp()
    {
        $this->mockingStrategy = $this->getMockBuilder(MockingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockingStrategy
            ->method('get')
            ->willReturn(
                $this->getMockBuilder(\stdClass::class)->getMock()
            );

        $this->proxyBuilder = new ProxyBuilder($this->mockingStrategy);
    }

    public function testGetProxySuccess()
    {
        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class);
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class);

        $this->assertInstanceOf(Proxy::class, $proxy1);

        $this->assertNotSame($proxy1, $proxy2);
    }

    public function testGetProxySuccessWithAlias()
    {
        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class, 'bar');
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class, 'foo');

        $this->assertInstanceOf(Proxy::class, $proxy1);
        $this->assertInstanceOf(Proxy::class, $proxy2);

        $this->assertNotSame($proxy1, $proxy2);
    }
}
