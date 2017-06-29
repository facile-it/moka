<?php
declare(strict_types=1);

namespace Moka\Generator;

use Moka\Exception\MockNotCreatedException;
use Moka\Generator\Template\ProxyClassTemplate;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class ProxyGenerator
 * @package Moka\Generator
 */
class ProxyGenerator
{
    /**
     * @var MockingStrategyInterface
     */
    private $mockingStrategy;

    /**
     * ProxyGenerator constructor.
     * @param MockingStrategyInterface $mockingStrategy
     */
    public function __construct(MockingStrategyInterface $mockingStrategy)
    {
        $this->mockingStrategy = $mockingStrategy;
    }

    /**
     * @param string $fqcn
     * @return ProxyInterface
     *
     * @throws MockNotCreatedException
     */
    public function get(string $fqcn): ProxyInterface
    {
        $mock = $this->mockingStrategy->build($fqcn);
        $mockFQCN = get_class($this->mockingStrategy->get($mock));
        $mockClass = new \ReflectionClass($mockFQCN);

        $proxyCode = ProxyClassTemplate::generate($mockClass);
        $proxyFQCN = eval($proxyCode);

        return $this->getInstance($proxyFQCN)
            ->_moka_setMock($mock)
            ->_moka_setMockingStrategy($this->mockingStrategy);
    }

    /**
     * @param string $proxyFQCN
     * @return ProxyInterface|ProxyTrait
     */
    protected function getInstance(string $proxyFQCN): ProxyInterface
    {
        $proxyClass = new \ReflectionClass($proxyFQCN);

        /** @var ProxyInterface|ProxyTrait $proxy */
        $proxy = $proxyClass->newInstanceWithoutConstructor();

        return $proxy;
    }
}
