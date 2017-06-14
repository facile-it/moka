<?php
declare(strict_types=1);

namespace Moka;

use Moka\Factory\BuilderFactory;
use Moka\Proxy\Proxy;

/**
 * Trait MokaBuilderTrait
 * @package Moka
 */
trait MokaBuilderTrait
{
    /**
     * @param string $fqcn
     * @param string|null $key
     * @return Proxy
     */
    protected function mock(string $fqcn, string $key = null): Proxy
    {
        return BuilderFactory::get()->getProxy($fqcn, $key);
    }
}
