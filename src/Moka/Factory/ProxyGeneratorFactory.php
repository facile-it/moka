<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Generator\ProxyGenerator;

class ProxyGeneratorFactory
{
    /**
     * @param string $fqcn
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyInterface
     */
    public static function get(string $fqcn, MockingStrategyInterface $mockingStrategy)
    {
        return (new ProxyGenerator($mockingStrategy));
    }

    /**
     * @param string $fqcn
     * @param MockingStrategyInterface $mockingStrategy
     * @return ProxyInterface
     */
    protected static function build(string $fqcn, MockingStrategyInterface $mockingStrategy)
    {
        return new Proxy($fqcn, $mockingStrategy);
    }
}
