<?php
declare(strict_types=1);

namespace Moka\Generator;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Generator\Template\ClassCreator;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;
use Moka\Strategy\MockingStrategyInterface;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;

/**
 * Class ProxyGenerator
 * @package Moka\Generator
 */
class ProxyGenerator
{
    private const TEMPLATE_FQCN = 'Moka_%s_%s';

    /**
     * @var MockingStrategyInterface
     */
    private $mockingStrategy;

    /**
     * @var callable
     */
    private $proxyClassNameGenerator;

    /**
     * @var Standard
     */
    private $prettyPrinter;

    /**
     * ProxyGenerator constructor.
     * @param MockingStrategyInterface $mockingStrategy
     */
    public function __construct(MockingStrategyInterface $mockingStrategy)
    {
        $this->mockingStrategy = $mockingStrategy;
        $this->proxyClassNameGenerator = function (string $mockClassName) {
            return sprintf(
                self::TEMPLATE_FQCN,
                preg_replace('/\\\/', '__', $mockClassName),
                random_int($min = 0, $max = PHP_INT_MAX)
            );
        };
        $this->prettyPrinter = new Standard();
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

        // Call the proxy class name generator
        $proxyClassName = ($this->proxyClassNameGenerator)($mockClass->name);
        // Create nodes with return statement at the end of the tree
        $proxyNodes[] = ClassCreator::createWithName($mockClass, $proxyClassName);
        $proxyNodes[] = new Return_(new String_($proxyClassName));
        
        $proxyCode = $this->prettyPrinter->prettyPrint($proxyNodes);

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
