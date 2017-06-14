<?php
declare(strict_types=1);

namespace Moka\Builder;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Factory\ProxyFactory;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyContainer;
use Moka\Proxy\ProxyInterface;
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
     * @param string|null $alias
     * @return ProxyInterface
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     */
    public function getProxy(string $fqcn, string $alias = null): ProxyInterface
    {
        $alias = $alias ?: $fqcn;

        if (!$this->container->has($alias)) {
            $this->container->set($alias, $this->buildProxy($fqcn));
        }

        return $this->container->get($alias);
    }

    /**
     * @param string $fqcn
     * @return ProxyInterface
     *
     * @throws MockNotCreatedException
     */
    protected function buildProxy(string $fqcn): ProxyInterface
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
