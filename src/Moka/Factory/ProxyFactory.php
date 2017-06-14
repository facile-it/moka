<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Proxy\Proxy;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class ProxyFactory
 * @package Moka\Factory
 */
class ProxyFactory
{
    /**
     * @param MockObject $mock
     * @return Proxy
     */
    public static function get(MockObject $mock): Proxy
    {
        return self::build($mock);
    }

    /**
     * @param MockObject $mock
     * @return Proxy
     */
    protected static function build(MockObject $mock): Proxy
    {
        return new Proxy($mock);
    }
}
