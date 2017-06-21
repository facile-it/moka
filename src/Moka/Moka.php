<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Exception\PluginNotRegisteredException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
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
     * The base namespace with plugin must be declared
     */
    const PLUGIN_NAMESPACE_TEMPLATE = 'Moka\\Plugin\\%s\\%sPlugin';

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
            $pluginFQCN = self::loadPlugin($name);

            self::registerStrategy($name, $pluginFQCN::getStrategy());
        }

        $mockFQCN = $arguments[0];
        $alias = $arguments[1] ?? null;
        $mockingStrategy = self::$mockingStrategies[$name];

        return static::brew($mockFQCN, $alias, $mockingStrategy);
    }

    /**
     * @param string $pluginName
     * @return string
     * @throws NotImplementedException
     */
    private static function loadPlugin(string $pluginName): string
    {
        $pluginFQCN = self::generatePluginFQCN($pluginName);

        if (!class_exists($pluginFQCN) || !in_array(PluginInterface::class, class_implements($pluginFQCN), true)) {
            throw new NotImplementedException(
                sprintf(
                    'Mocking strategy "%s" does not exist',
                    $pluginName
                )
            );
        }

        return $pluginFQCN;
    }

    /**
     * @param string $pluginName
     * @return string
     */
    public static function generatePluginFQCN(string $pluginName): string
    {
        return sprintf(
            self::PLUGIN_NAMESPACE_TEMPLATE,
            ucfirst($pluginName),
            ucfirst($pluginName)
        );
    }

    /**
     * @param string $strategyName
     * @param MockingStrategyInterface $mockingStrategy
     */
    public static function registerStrategy(string $strategyName, MockingStrategyInterface $mockingStrategy)
    {
        self::$mockingStrategies[$strategyName] = $mockingStrategy;
    }

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @param MockingStrategyInterface $mockingStrategy
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

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     *
     * @deprecated since 0.3.0
     */
    public static function get(string $fqcn, string $alias = null): Proxy
    {
        return static::brew($fqcn, $alias);
    }
}
