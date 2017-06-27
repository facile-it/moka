<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Trait MokaProphecyTrait
 * @package Moka\Traits
 */
trait MokaProphecyTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return Proxy|ObjectProphecy
     */
    protected function moka(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::prophecy($fqcnOrAlias, $alias);
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
