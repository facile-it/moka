<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Builder\ProxyBuilder;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;

/**
 * Class BuilderFactory
 * @package Moka\Factory
 */
class BuilderFactory
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
            self::$mockBuilder = self::build();
        }

        return self::$mockBuilder;
    }

    /**
     * @return ProxyBuilder
     */
    protected static function build(): ProxyBuilder
    {
        return new ProxyBuilder(new MockGenerator());
    }
}
