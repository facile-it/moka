<?php
declare(strict_types=1);

namespace Moka\Builder;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Factory\ProxyFactory;
use Moka\Generator\MockGeneratorInterface;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyContainer;
use Moka\Strategy\MockingStrategyInterface;
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
     * @var MockingStrategyInterface
     */
    protected $mockingStrategy;

    /**
     * ProxyBuilder constructor.
     * @param MockingStrategyInterface $mockingStrategy
     */
    public function __construct(MockingStrategyInterface $mockingStrategy)
    {
        $this->mockingStrategy = $mockingStrategy;
        $this->reset();
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->container = new ProxyContainer();
    }

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     */
    public function getProxy(string $fqcn, string $alias = null): Proxy
    {
        $alias = $alias ?: $fqcn;

        if (!$this->container->has($alias)) {
            $this->container->set($alias, $this->buildProxy($fqcn));
        }

        return $this->container->get($alias);
    }

    /**
     * @param string $fqcn
     * @return Proxy
     */
    protected function buildProxy(string $fqcn): Proxy
    {
        return ProxyFactory::get($fqcn, $this->mockingStrategy);
    }
}
