<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Builder\ProxyBuilder;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class ProxyBuilderFactory
 * @package Moka\Factory
 */
class ProxyBuilderFactory
{
    /**
     * @var array|ProxyBuilder[]
     */
    private static $mockBuilders = [];

    /**
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyBuilder
     */
    public static function get(MockingStrategyInterface $mockingStrategy): ProxyBuilder
    {
        $key = static::key($mockingStrategy);
        if (!array_key_exists($key, static::$mockBuilders) || !static::$mockBuilders[$key] instanceof ProxyBuilder) {
            static::$mockBuilders[$key] = static::build(new $mockingStrategy);
        }

        return static::$mockBuilders[$key];
    }

    /**
     * @param MockingStrategyInterface $mockingStrategy
     * @return string
     */
    private static function key(MockingStrategyInterface $mockingStrategy): string
    {
        return get_class($mockingStrategy);
    }

    /**
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyBuilder
     */
    protected static function build(MockingStrategyInterface $mockingStrategy): ProxyBuilder
    {
        return new ProxyBuilder($mockingStrategy);
    }

    public static function reset()
    {
        foreach (static::$mockBuilders as $mockBuilder) {
            $mockBuilder->reset();
        }
    }
}
