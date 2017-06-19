<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Moka;
use Moka\Proxy\Proxy;

trait MokaProphecyTrait
{
    protected function mock(string $fqcn, string $alias = null): Proxy
    {
        return Moka::prophecy($fqcn, $alias);
    }
}
