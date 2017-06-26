<?php

declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;

/**
 * Trait MokaProphecyTrait
 * @package Moka\Traits
 */
trait MokaProphecyTrait
{
    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     */
    protected function mock(string $fqcn, string $alias = null): Proxy
    {
        return Moka::prophecy($fqcn, $alias);
    }
}
