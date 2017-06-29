<?php
declare(strict_types=1);

namespace Moka\Traits;

use Mockery\MockInterface;
use Moka\Moka;
use Moka\Proxy\ProxyInterface;

/**
 * Trait MokaMockeryTrait
 * @package Moka\Traits
 *
 * @deprecated since v2.0.0
 */
trait MokaMockeryTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface|MockInterface
     *
     * @deprecated since v2.0.0
     */
    protected function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return Moka::mockery($fqcnOrAlias, $alias);
    }

    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface|MockInterface
     *
     * @deprecated since v1.4.0
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return $this->moka($fqcnOrAlias, $alias);
    }
}
