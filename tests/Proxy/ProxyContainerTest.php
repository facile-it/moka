<?php

namespace Tests\Proxy;


use Moka\Exception\InvalidIdentifierException;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyContainer;

class ProxyContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProxyContainer
     */
    private $proxyContainer;

    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        $this->proxyContainer = new ProxyContainer();
        /** @var Proxy $proxy */
        $this->proxy = $this->getMockBuilder(Proxy::class)
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

        $this->assertInstanceOf(Proxy::class, $proxy);
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
            Proxy::class,
            $this->proxyContainer->get('acme')
        );
    }
}
