<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Builder\ProxyBuilder;
use Moka\Generator\MockGeneratorInterface;
use Moka\Generator\PHPUnitGenerator;

/**
 * Class ProxyBuilderFactory
 * @package Moka\Factory
 */
class ProxyBuilderFactory
{
    /**
     * @var ProxyBuilder
     */
    private static $mockBuilder;

    /**
     * @return ProxyBuilder
     */
    public static function get(): ProxyBuilder
    {
        if (!self::$mockBuilder instanceof ProxyBuilder) {
            self::$mockBuilder = self::build(new PHPUnitGenerator());
        }

        return self::$mockBuilder;
    }

    /**
     * @param MockGeneratorInterface $generator
     * @return ProxyBuilder
     */
    protected static function build(MockGeneratorInterface $generator): ProxyBuilder
    {
        return new ProxyBuilder($generator);
    }
}
