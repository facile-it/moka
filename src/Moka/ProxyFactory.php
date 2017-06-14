<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 12/06/2017
 * Time: 18:25
 */

namespace Moka;


class ProxyFactory
{
    public static function getProxy(\PHPUnit_Framework_MockObject_MockObject $mock): Proxy
    {
        return new Proxy($mock);
    }
}