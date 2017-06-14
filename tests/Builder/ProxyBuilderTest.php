<?php

namespace Tests\Builder;


use Moka\Builder\ProxyBuilder;
use Moka\Exception\MockNotCreatedException;
use Moka\Proxy\Proxy;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProxyBuilder
     */
    private $proxyBuilder;

    /**
     * @var MockGenerator|MockObject
     */
    private $mockGenerator;

    public function setUp()
    {
        $this->mockGenerator = $this->getMockBuilder(MockGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockGenerator
            ->method('getMock')
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
            ->method('getMock')
            ->willThrowException(new \Exception());

        $this->proxyBuilder->getProxy(\stdClass::class, 'acme');
    }


}
