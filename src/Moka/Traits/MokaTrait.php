<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Exception\MockNotCreatedException;
use Moka\Moka;
use Moka\Proxy\Proxy;

/**
 * Trait MokaTrait
 * @package Moka\Traits
 */
trait MokaTrait
{
    /**
     * @param string $fqcn
     * @param string|null $key
     * @return Proxy
     *
     * @throws MockNotCreatedException
     */
    protected function mock(string $fqcn, string $key = null): Proxy
    {
        return Moka::get($fqcn, $key);
    }
}
