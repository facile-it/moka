<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;
use Phake_IMock as PhakeMock;

/**
 * Trait MokaPhakeTrait
 * @package Moka\Traits
 */
trait MokaPhakeTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return Proxy|PhakeMock
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::phake($fqcnOrAlias, $alias);
    }
}
