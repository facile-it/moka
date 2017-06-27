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
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;
use Prophecy\Prophecy\ObjectProphecy;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;
use Phake_IMock as PhakeMock;
use Mockery\MockInterface;

/**
 * Class Moka
 * @package Moka
 *
 * @method static Proxy|MockInterface mockery(string $fqcnOrAlias, string $alias = null)
 * @method static Proxy|PhakeMock phake(string $fqcnOrAlias, string $alias = null)
 * @method static Proxy|MockObject phpunit(string $fqcnOrAlias, string $alias = null)
 * @method static Proxy|ObjectProphecy prophecy(string $fqcnOrAlias, string $alias = null)
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
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface
     *
     * @throws NotImplementedException
     * @throws InvalidIdentifierException
     * @throws MockNotCreatedException
     * @throws MissingDependencyException
     *
     * @deprecated since 1.2.0
     */
    public static function brew(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return self::phpunit($fqcnOrAlias, $alias);
    }

    /**
     * @return void
     */
    public static function clean()
    {
        ProxyBuilderFactory::reset();
    }
}
