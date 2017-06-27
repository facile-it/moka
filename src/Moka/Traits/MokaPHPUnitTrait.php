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
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return Proxy
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::phpunit($fqcnOrAlias, $alias);
    }
}
