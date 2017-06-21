<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Exception\PluginNotRegisteredException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Moka\Plugin\PluginHelper;
use Moka\Plugin\PluginInterface;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class Moka
 * @package Moka
 *
 * @method static Proxy mockery(string $fqcn, string $alias = null)
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
     * @throws PluginNotRegisteredException
     */
    public static function __callStatic($name, $arguments)
    {
        if (!isset(self::$mockingStrategies[$name])) {
            /** @var PluginInterface $pluginFQCN */
            $pluginFQCN = PluginHelper::load($name);

            self::$mockingStrategies[$name] = $pluginFQCN::getStrategy();
        }

        $mockFQCN = $arguments[0];
        $alias = $arguments[1] ?? null;
        $mockingStrategy = self::$mockingStrategies[$name];

        return static::brew($mockFQCN, $alias, $mockingStrategy);
    }

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @param MockingStrategyInterface|null $mockingStrategy
     * @return Proxy
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     */
    public static function brew(string $fqcn, string $alias = null, MockingStrategyInterface $mockingStrategy = null): Proxy
    {
        if (!$mockingStrategy instanceof MockingStrategyInterface) {
            $mockingStrategy = new PHPUnitMockingStrategy();
        }

        return ProxyBuilderFactory::get($mockingStrategy)->getProxy($fqcn, $alias);
    }

    /**
     * @return void
     */
    public static function clean()
    {
        ProxyBuilderFactory::reset();
    }
}
