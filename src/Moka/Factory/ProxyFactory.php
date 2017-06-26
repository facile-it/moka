<?php

declare(strict_types=1);

namespace Moka\Factory;

use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;

/**
 * Class ProxyFactory
 * @package Moka\Factory
 */
class ProxyFactory
{
    /**
     * @param string $fqcn
     * @param MockingStrategyInterface $mockingStrategy
     * @return Proxy
     */
    public static function get(string $fqcn, MockingStrategyInterface $mockingStrategy): Proxy
    {
        return self::build($fqcn, $mockingStrategy);
    }

    /**
     * @param string $fqcn
     * @param MockingStrategyInterface $mockingStrategy
     * @return Proxy
     */
    protected static function build(string $fqcn, MockingStrategyInterface $mockingStrategy): Proxy
    {
        return new Proxy($fqcn, $mockingStrategy);
    }
}
