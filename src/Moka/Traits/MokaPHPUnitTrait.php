<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Trait MokaPHPUnitTrait
 * @package Moka\Traits
 */
trait MokaPHPUnitTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface|MockObject
     */
    protected function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return Moka::phpunit($fqcnOrAlias, $alias);
    }

    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return ProxyInterface|MockObject
     *
     * @deprecated since v1.4.0
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): ProxyInterface
    {
        return $this->moka($fqcnOrAlias, $alias);
    }
}
