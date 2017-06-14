<?php
declare(strict_types=1);

namespace Moka;


use Moka\Factory\BuilderFactory;
use Moka\Proxy\Proxy;

/**
 * Class Moka
 * @package Moka
 */
class Moka
{
    /**
     * @param string $fqcn
     * @param string|null $key
     * @return Proxy
     */
    public static function get(string $fqcn, string $key = null): Proxy
    {
        return BuilderFactory::get()->getProxy($fqcn, $key);
    }

    /**
     * @return void
     */
    public static function clean()
    {
        BuilderFactory::get()->clean();
    }
}
