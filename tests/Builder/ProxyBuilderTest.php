<?php
declare(strict_types=1);

namespace Tests\Builder;

use Moka\Builder\ProxyBuilder;
use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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

    public function testGetProxySuccess()
    {
        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class);
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class);

        $this->assertInstanceOf(ProxyInterface::class, $proxy1);

        $this->assertNotSame($proxy1, $proxy2);
    }

    public function testGetProxySuccessWithAlias()
    {
        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class, 'bar');
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class, 'foo');

        $this->assertInstanceOf(ProxyInterface::class, $proxy1);
        $this->assertInstanceOf(ProxyInterface::class, $proxy2);

        $this->assertNotSame($proxy1, $proxy2);
    }

    public function testGetProxySuccessWithSameAlias()
    {
        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class, 'foo');
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class, 'foo');

        $this->assertInstanceOf(ProxyInterface::class, $proxy1);
        $this->assertInstanceOf(ProxyInterface::class, $proxy2);

        $this->assertSame($proxy1, $proxy2);
    }

    public function testGetProxySuccessWithSameAliasOnly()
    {
        $proxy = $this->proxyBuilder->getProxy(\stdClass::class, 'bar');

        $this->assertInstanceOf(ProxyInterface::class, $proxy);

        $this->assertSame($proxy, $this->proxyBuilder->getProxy('bar'));
    }

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

        $this->mockingStrategy = new PHPUnitMockingStrategy();

        $this->proxyBuilder = new ProxyBuilder($this->mockingStrategy);
    }
}
