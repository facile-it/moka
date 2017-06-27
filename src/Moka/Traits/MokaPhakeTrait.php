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
    protected function moka(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::phake($fqcnOrAlias, $alias);
    }

    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return Proxy|MockInterface
     *
     * @deprecated since v.1.4.0
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return $this->moka($fqcnOrAlias, $alias);
    }
}
