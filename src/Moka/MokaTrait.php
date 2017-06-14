<?php
declare(strict_types=1);

namespace Moka;

use Moka\Proxy\Proxy;

/**
 * Trait MokaTrait
 * @package Moka
 */
trait MokaTrait
{
    /**
     * @param string $fqcn
     * @param string|null $key
     * @return Proxy
     */
    protected function mock(string $fqcn, string $key = null): Proxy
    {
        return Moka::get($fqcn, $key);
    }
}
