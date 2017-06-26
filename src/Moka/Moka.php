<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Plugin\PluginHelper;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class Moka
 * @package Moka
 *
 * @method static Proxy mockery(string $fqcn, string $alias = null)
 * @method static Proxy phake(string $fqcn, string $alias = null)
 * @method static Proxy phpunit(string $fqcn, string $alias = null)
 * @method static Proxy prophecy(string $fqcn, string $alias = null)
 */
class Moka
{
    /**
     * @var array|MockingStrategyInterface[]
     */
    private static $mockingStrategies = [];

    /**
     * @param string $name
     * @param array $arguments
     * @return Proxy
     *
     * @throws NotImplementedException
     * @throws InvalidIdentifierException
     * @throws MockNotCreatedException
     * @throws MissingDependencyException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (!isset(self::$mockingStrategies[$name])) {
            self::$mockingStrategies[$name] = PluginHelper::load($name);
        }

        $mockFQCN = $arguments[0];
        $alias = $arguments[1] ?? null;
        $mockingStrategy = self::$mockingStrategies[$name];

        return ProxyBuilderFactory::get($mockingStrategy)->getProxy($mockFQCN, $alias);
    }

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @param MockingStrategyInterface|null $mockingStrategy
     * @return Proxy
     *
     * @throws NotImplementedException
     * @throws InvalidIdentifierException
     * @throws MockNotCreatedException
     * @throws MissingDependencyException
     *
     * @deprecated since 1.2.0
     */
    public static function brew(string $fqcn, string $alias = null, MockingStrategyInterface $mockingStrategy = null): Proxy
    {
        return self::phpunit($fqcn, $alias);
    }

    /**
     * @return void
     */
    public static function clean()
    {
        ProxyBuilderFactory::reset();
    }
}
