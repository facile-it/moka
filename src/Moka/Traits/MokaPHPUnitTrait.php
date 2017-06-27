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
    protected function mock(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::phpunit($fqcnOrAlias, $alias);
    }
}
