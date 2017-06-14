<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class ProxyFactory
 * @package Moka\Factory
 */
class ProxyFactory
{
    /**
     * @param MockObject $mock
     * @return ProxyInterface
     */
    public static function get(MockObject $mock): ProxyInterface
    {
        return self::build($mock);
    }

    /**
     * @param MockObject $mock
     * @return ProxyInterface
     */
    protected static function build(MockObject $mock): ProxyInterface
    {
        return new Proxy($mock);
    }
}
