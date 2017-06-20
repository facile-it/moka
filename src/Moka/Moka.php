<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockeryMockingStrategy;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Strategy\PHPUnitMockingStrategy;
use Moka\Strategy\ProphecyMockingStrategy;

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
    const MOCKING_STRATEGIES = [
        'mockery' => MockeryMockingStrategy::class,
        'phpunit' => PHPUnitMockingStrategy::class,
        'prophecy' => ProphecyMockingStrategy::class
    ];

    const MOCKING_STRATEGY_DEFAULT = PHPUnitMockingStrategy::class;

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     */
    public static function brew(string $fqcn, string $alias = null, MockingStrategyInterface $mockingStrategy = null): Proxy
    {
        if (!$mockingStrategy instanceof MockingStrategyInterface) {
            $defaultMockingStrategy = static::MOCKING_STRATEGY_DEFAULT;
            $mockingStrategy = new $defaultMockingStrategy();
        }

        return ProxyBuilderFactory::get($mockingStrategy)->getProxy($fqcn, $alias);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return Proxy
     *
     * @throws NotImplementedException
     */
    public static function __callStatic($name, $arguments)
    {
        if (!isset(static::MOCKING_STRATEGIES[$name])) {
            throw new NotImplementedException(
                sprintf(
                    'Mocking strategy "%s" does not exist',
                    $name
                )
            );
        }

        $fqcn = $arguments[0];
        $alias = $arguments[1] ?? null;
        $mockingStrategy = static::MOCKING_STRATEGIES[$name];

        return static::brew($fqcn, $alias, new $mockingStrategy());
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
