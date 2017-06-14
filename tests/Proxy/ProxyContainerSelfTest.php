<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 14/06/2017
 * Time: 13:17
 */

namespace Tests\Proxy;


use Moka\Exception\InvalidIdentifierException;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyContainer;
use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaTrait;

class ProxyContainerSelfTest extends \PHPUnit_Framework_TestCase
{
    use MokaTrait;
    use MokaCleanerTrait;

    /**
     * @var ProxyContainer
     */
    private $proxyContainer;

    public function setUp()
    {
        $this->proxyContainer = new ProxyContainer();

        $this->proxyContainer->set(
            'foo',
            $this->mock(Proxy::class)->serve()
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
        $this->proxyContainer->set('acme', $this->mock(Proxy::class)->serve());
        $this->assertInstanceOf(
            Proxy::class,
            $this->proxyContainer->get('acme')
        );
    }
}
