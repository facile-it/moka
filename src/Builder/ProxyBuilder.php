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
use function Moka\Factory\buildProxy;
use function Moka\Factory\getProxyGenerator;

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
     * @throws \ReflectionException
     */
    public function getProxy(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        if ($this->container->has($fqcnOrAlias)) {
            return $this->container->get($fqcnOrAlias);
        }

        if (null === $alias) {
            return buildProxy($fqcnOrAlias, $this->mockingStrategy);
        }

        if (!$this->container->has($alias)) {
            $this->container->set($alias, buildProxy($fqcnOrAlias, $this->mockingStrategy));
        }

        return $this->container->get($alias);
    }
}
