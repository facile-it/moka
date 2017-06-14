<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\MockNotCreatedException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Proxy\Proxy;

/**
 * Class Moka
 * @package Moka
 */
class Moka
{
    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     *
     * @throws MockNotCreatedException
     */
    public static function get(string $fqcn, string $alias = null): Proxy
    {
        return ProxyBuilderFactory::get()->getProxy($fqcn, $alias);
    }

    /**
     * @return void
     */
    public static function clean()
    {
        ProxyBuilderFactory::get()->clean();
    }
}
