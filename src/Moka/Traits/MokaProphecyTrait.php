<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Trait MokaProphecyTrait
 * @package Moka\Traits
 *
 * @deprecated since v2.0.0
 */
trait MokaProphecyTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface|ObjectProphecy
     *
     * @deprecated since v2.0.0
     */
    protected function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return Moka::prophecy($fqcnOrAlias, $alias);
    }

    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface|ObjectProphecy
     *
     * @deprecated since v1.4.0
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return $this->moka($fqcnOrAlias, $alias);
    }
}
