<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Exception\MockNotCreatedException;
use Moka\Moka;
use Moka\Proxy\Proxy;
use Moka\Proxy\ProxyInterface;

/**
 * Trait MokaTrait
 * @package Moka\Traits
 */
trait MokaTrait
{
    /**
     * @param string $fqcn
     * @param string|null $key
     * @return ProxyInterface
     *
     * @throws MockNotCreatedException
     */
    protected function mock(string $fqcn, string $key = null): ProxyInterface
    {
        return Moka::get($fqcn, $key);
    }
}
