<?php
declare(strict_types=1);

namespace Tests\Builder;

use Moka\Builder\ProxyBuilder;
use Moka\Exception\MockNotCreatedException;
use Moka\Generator\MockGeneratorInterface;
use Moka\Proxy\Proxy;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyBuilderTest extends TestCase
{
    /**
     * @var ProxyBuilder
     */
    private $proxyBuilder;

    /**
     * @var MockGeneratorInterface|MockObject
     */
    private $mockGenerator;

    public function setUp()
    {
        $this->mockGenerator = $this->getMockBuilder(MockGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockGenerator
            ->method('generate')
            ->willReturn(
                $this->getMockBuilder(\stdClass::class)->getMock()
            );

        $this->proxyBuilder = new ProxyBuilder($this->mockGenerator);
    }

    public function testGetProxySuccess()
    {
        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class);
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class);

        $this->assertInstanceOf(Proxy::class, $proxy1);

        $this->assertSame($proxy1, $proxy2);
    }

    public function testGetProxySuccessWithAlias()
    {
        $proxy1 = $this->proxyBuilder->getProxy(\stdClass::class, 'bar');
        $proxy2 = $this->proxyBuilder->getProxy(\stdClass::class, 'foo');

        $this->assertInstanceOf(Proxy::class, $proxy1);
        $this->assertInstanceOf(Proxy::class, $proxy2);

        $this->assertNotSame($proxy1, $proxy2);
    }

    public function testGetProxyThrowException()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->mockGenerator
            ->expects($this->at(0))
            ->method('generate')
            ->willThrowException(new \Exception());

        $this->proxyBuilder->getProxy(\stdClass::class, 'acme');
    }
}
