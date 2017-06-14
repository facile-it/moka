<?php
declare(strict_types=1);

namespace Moka;

use PHPUnit_Framework_MockObject_Generator as MockGenerator;

class BuilderFactory
{
    /**
     * @var Builder
     */
    private static $mockBuilder;

    /**
     * @return Builder
     */
    public static function get(): Builder
    {
        if (!self::$mockBuilder instanceof Builder) {
            self::$mockBuilder = self::build();
        }

        return self::$mockBuilder;
    }

    protected static function build(): Builder
    {
        return new Builder(new MockGenerator());
    }
}
