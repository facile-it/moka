<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Exception\InvalidIdentifierException;
use Moka\Proxy\ProxyContainer;
use Moka\Proxy\ProxyInterface;
use PHPUnit\Framework\TestCase;

class ProxyContainerTest extends TestCase
{
    /**
     * @var ProxyContainer
     */
    private $proxyContainer;

    /**
     * @var ProxyInterface
     */
    private $proxy;

    protected function setUp(): void
    {
        $this->proxyContainer = new ProxyContainer();
        /** @var ProxyInterface $proxy */
        $this->proxy = $this->getMockBuilder(ProxyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->proxyContainer->set(
            'foo',
            $this->proxy
        );
    }

    public function testGetSuccess()
    {
        $proxy = $this->proxyContainer->get('foo');

        $this->assertInstanceOf(ProxyInterface::class, $proxy);
    }

    public function testGetFailure()
    {
        $this->expectException(InvalidIdentifierException::class);
        $this->proxyContainer->get('bar');
    }

    public function testHasSuccess()
    {
        $this->assertTrue(
            $this->proxyContainer->has('foo')
        );
    }

    public function testHasFailure()
    {
        $this->assertFalse(
            $this->proxyContainer->has('bar')
        );
    }

    public function testSet()
    {
        $this->proxyContainer->set('acme', $this->proxy);
        $this->assertInstanceOf(
            ProxyInterface::class,
            $this->proxyContainer->get('acme')
        );
    }
}
