<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyInterface;
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
     * @return ProxyInterface
     */
    public static function get(string $fqcn, MockingStrategyInterface $mockingStrategy): ProxyInterface
    {
        return self::build($fqcn, $mockingStrategy);
    }

    /**
     * @param string $fqcn
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyInterface
     */
    protected static function build(string $fqcn, MockingStrategyInterface $mockingStrategy): ProxyInterface
    {
        return new Proxy($fqcn, $mockingStrategy);
    }
}
