<?php
declare(strict_types=1);

namespace Tests\Builder;

use Moka\Builder\ProxyBuilder;
use Moka\Exception\MockNotCreatedException;
use Moka\Generator\MockGeneratorInterface;
use Moka\Proxy\Proxy;
use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyBuilderSelfTest extends TestCase
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
            $this->mock(MockGeneratorInterface::class)->stub([
                'generate' => $this->mock(\stdClass::class)->serve()
            ])
                ->serve()
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
        $this->mock(MockGenerator::class)->stub([
            'getMock' => $this->mock(\stdClass::class)->serve()
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

    public function testGetProxyThrowException()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->mock(MockGeneratorInterface::class)->stub([
            'generate' => new \Exception()
        ]);

        $this->proxyBuilder->getProxy(\stdClass::class, 'acme');
    }
}
