<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;
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
     * @return Proxy|MockObject
     */
    protected function moka(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::phpunit($fqcnOrAlias, $alias);
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
