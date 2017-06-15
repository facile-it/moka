<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MockNotCreatedException;
use Moka\Factory\ProxyBuilderFactory;
use Moka\Proxy\Proxy;
use Moka\Strategy\PHPUnitMockingStrategy;
use Moka\Strategy\ProphecyMockingStrategy;

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
     * @throws InvalidIdentifierException
     */
    public static function get(string $fqcn, string $alias = null): Proxy
    {
        return static::phpunit($fqcn, $alias);
    }

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     */
    public static function phpunit(string $fqcn, string $alias = null): Proxy
    {
        return ProxyBuilderFactory::get(new PHPUnitMockingStrategy())->getProxy($fqcn, $alias);
    }

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     */
    public static function prophecy(string $fqcn, string $alias = null): Proxy
    {
        return ProxyBuilderFactory::get(new ProphecyMockingStrategy())->getProxy($fqcn, $alias);
    }

    /**
     * @return void
     *
     * @deprecated since 0.3.0
     */
    public static function clean()
    {
        ProxyBuilderFactory::reset();
    }

    /**
     * @return void
     */
    public static function reset()
    {
        ProxyBuilderFactory::reset();
    }
}
