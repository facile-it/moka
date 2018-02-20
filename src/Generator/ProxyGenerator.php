<?php
declare(strict_types=1);

namespace Moka\Generator;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Generator\Template\ClassCreator;
use Moka\Generator\Template\ClassTemplate;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;
use Moka\Strategy\MockingStrategyInterface;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;

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
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function get(string $fqcn): ProxyInterface
    {
        $mock = $this->mockingStrategy->build($fqcn);
        $mockFQCN = \get_class($this->mockingStrategy->get($mock));
        $mockClass = new \ReflectionClass($mockFQCN);

        $proxyNode = ClassCreator::create($mockClass);
        $proxyCode = (new Standard())->prettyPrint($proxyNode);
        $proxyFQCN = eval($proxyCode);

        return $this->getInstance($proxyFQCN)
            ->__moka_setMock($mock)
            ->__moka_setMockingStrategy($this->mockingStrategy);
    }

    /**
     * @param string $proxyFQCN
     * @return ProxyInterface|ProxyTrait
     * @throws \ReflectionException
     */
    protected function getInstance(string $proxyFQCN): ProxyInterface
    {
        $proxyClass = new \ReflectionClass($proxyFQCN);

        /** @var ProxyInterface|ProxyTrait $proxy */
        $proxy = $proxyClass->newInstance();

        return $proxy;
    }
}
