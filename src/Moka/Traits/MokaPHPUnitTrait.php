<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;

trait MokaPHPUnitTrait
{
    protected function mock(string $fqcn, string $alias = null): Proxy
    {
        return Moka::phpunit($fqcn, $alias);
    }
}
