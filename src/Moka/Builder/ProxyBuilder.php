<?php
declare(strict_types=1);

namespace Moka\Builder;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Factory\ProxyFactory;
use Moka\Generator\MockGeneratorInterface;
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
     * @param MockGeneratorInterface $generator
     */
    public function __construct(MockGeneratorInterface $generator)
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
     *
     * @throws MockNotCreatedException
     */
    protected function buildProxy(string $fqcn): Proxy
    {
        try {
            $mock = $this->getGenerator()->generate($fqcn);
        } catch (\Exception $exception) {
            throw new MockNotCreatedException(sprintf('Unable to create a mock object for "$fqcn": %s', $fqcn));
        }

        return ProxyFactory::get($mock);
    }

    /**
     * @return MockGeneratorInterface
     */
    protected function getGenerator(): MockGeneratorInterface
    {
        return $this->generator;
    }
}
