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
        $key = self::key($mockingStrategy);
        if (!array_key_exists($key, self::$mockBuilders) || !self::$mockBuilders[$key] instanceof ProxyBuilder) {
            self::$mockBuilders[$key] = static::build($mockingStrategy);
        }

        return self::$mockBuilders[$key];
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

    /**
     * @return void
     */
    public static function reset()
    {
        foreach (self::$mockBuilders as $mockBuilder) {
            $mockBuilder->reset();
        }
    }
}
