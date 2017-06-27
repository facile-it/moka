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
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return Proxy
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::prophecy($fqcnOrAlias, $alias);
    }
}
