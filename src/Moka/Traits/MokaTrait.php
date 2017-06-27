<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyInterface;

/**
 * Trait MokaTrait
 * @package Moka\Traits
 *
 * @deprecated since v1.2.0
 */
trait MokaTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface
     *
     * @deprecated since v1.2.0
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return Moka::phpunit($fqcnOrAlias, $alias);
    }
}
