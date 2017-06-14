<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 12/06/2017
 * Time: 12:05
 */

namespace Moka;


/**
 * Class MockBuilderFactory
 * @package Phantom\PhantomBundle\Test\Mock
 */
class BuilderFactory
{
    /**
     * @var Builder|null
     */
    private static $mockBuilder;

    /**
     * @return Builder
     */
    public static function get()
    {
        if (!self::$mockBuilder instanceof Builder) {
            self::$mockBuilder = new Builder(new \PHPUnit_Framework_MockObject_Generator());
        }

        return self::$mockBuilder;
    }
}