<?php
declare(strict_types=1);

namespace Moka\Builder;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Factory\ProxyGeneratorFactory;
use Moka\Proxy\ProxyContainer;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;

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
    public function reset(): void
    {
        $this->container = new ProxyContainer();
    }

    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     * @throws InvalidArgumentException
     */
    public function getProxy(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        if ($this->container->has($fqcnOrAlias)) {
            return $this->container->get($fqcnOrAlias);
        }

        if (null === $alias) {
            return $this->buildProxy($fqcnOrAlias);
        }

        if (!$this->container->has($alias)) {
            $this->container->set($alias, $this->buildProxy($fqcnOrAlias));
        }

        return $this->container->get($alias);
    }

    /**
     * @param string $fqcn
     * @return ProxyInterface
     *
     * @throws \ReflectionException
     */
    protected function buildProxy(string $fqcn): ProxyInterface
    {
        return ProxyGeneratorFactory::get($this->mockingStrategy)->get($fqcn);
    }
}
