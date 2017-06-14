<?php
declare(strict_types=1);

namespace Moka;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ProxyFactory
{
    public static function get(MockObject $mock): Proxy
    {
        return self::build($mock);
    }

    protected static function build(MockObject $mock): Proxy
    {
        return new Proxy($mock);
    }
}
