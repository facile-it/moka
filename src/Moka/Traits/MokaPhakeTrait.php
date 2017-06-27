<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;

/**
 * Trait MokaPhakeTrait
 * @package Moka\Traits
 */
trait MokaPhakeTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return Proxy
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::phake($fqcnOrAlias, $alias);
    }
}
