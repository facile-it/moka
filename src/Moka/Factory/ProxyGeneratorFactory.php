<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Generator\ProxyGenerator;
use Moka\Strategy\MockingStrategyInterface;

class ProxyGeneratorFactory
{
    /**
     * @var array|ProxyGenerator[]
     */
    private static $proxyGenerators = [];

    /**
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyGenerator
     */
    public static function get(MockingStrategyInterface $mockingStrategy): ProxyGenerator
    {
        $key = self::key($mockingStrategy);
        if (!array_key_exists($key, self::$proxyGenerators) || !self::$proxyGenerators[$key] instanceof ProxyGenerator) {
            self::$proxyGenerators[$key] = static::build($mockingStrategy);
        }

        return self::$proxyGenerators[$key];
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
     * @return ProxyGenerator
     */
    protected static function build(MockingStrategyInterface $mockingStrategy): ProxyGenerator
    {
        return new ProxyGenerator($mockingStrategy);
    }
}
