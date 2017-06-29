<?php
declare(strict_types=1);

namespace Moka;

use Mockery\MockInterface;
use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Plugin\PluginHelper;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;
use Phake_IMock as PhakeMock;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class Moka
 * @package Moka
 *
 * @method static ProxyInterface|MockInterface mockery(string $fqcnOrAlias, string $alias = null)
 * @method static ProxyInterface|PhakeMock phake(string $fqcnOrAlias, string $alias = null)
 * @method static ProxyInterface|MockObject phpunit(string $fqcnOrAlias, string $alias = null)
 * @method static ProxyInterface|ObjectProphecy prophecy(string $fqcnOrAlias, string $alias = null)
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
     * @return ProxyInterface
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

        $fqcnOrAlias = $arguments[0];
        $alias = $arguments[1] ?? null;
        $mockingStrategy = self::$mockingStrategies[$name];

        return ProxyBuilderFactory::get($mockingStrategy)->getProxy($fqcnOrAlias, $alias);
    }

    /**
     * @return void
     */
    public static function clean()
    {
        ProxyBuilderFactory::reset();
    }
}
