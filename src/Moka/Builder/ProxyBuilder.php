<?php
declare(strict_types=1);

namespace Moka\Builder;

use Moka\Exception\MockNotCreatedException;
use Moka\Factory\ProxyFactory;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyContainer;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;

/**
 * Class ProxyBuilder
 * @package Moka\Builder
 */
class ProxyBuilder
{
    /**
     * @var ProxyContainer
     */
    protected $container;

    /**
     * @var MockGenerator
     */
    protected $generator;

    /**
     * ProxyBuilder constructor.
     * @param MockGenerator $generator
     */
    public function __construct(MockGenerator $generator)
    {
        $this->generator = $generator;
        $this->clean();
    }

    /**
     * @return void
     */
    public function clean()
    {
        $this->container = new ProxyContainer();
    }

    /**
     * @param string $fqcn
     * @param string|null $key
     * @return Proxy
     */
    public function getProxy(string $fqcn, string $key = null): Proxy
    {
        $key = $key ?: $fqcn;

        if (!$this->container->has($key)) {
            $this->container->set($key, $this->buildProxy($fqcn));
        }

        return $this->container->get($key);
    }

    /**
     * @param string $fqcn
     * @return Proxy
     *
     * @throws MockNotCreatedException
     */
    protected function buildProxy(string $fqcn): Proxy
    {
        try {
            $mock = $this->getGenerator()->getMock(
                $fqcn,
                $methods = [],
                $arguments = [],
                $mockClassName = '',
                $callOriginalConstructor = false
            );
        } catch (\Exception $exception) {
            throw new MockNotCreatedException(sprintf('Unable to create a mock object for "$fqcn": %s', $fqcn));
        }

        return ProxyFactory::get($mock);
    }

    /**
     * @return MockGenerator
     */
    protected function getGenerator(): MockGenerator
    {
        return $this->generator;
    }
}
