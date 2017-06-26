<?php

declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;

/**
 * Trait MokaPHPUnitTrait
 * @package Moka\Traits
 */
trait MokaPHPUnitTrait
{
    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     */
    protected function mock(string $fqcn, string $alias = null): Proxy
    {
        return Moka::phpunit($fqcn, $alias);
    }
}
