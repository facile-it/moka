<?php
declare(strict_types=1);

namespace Moka\Traits;

use Moka\Exception\InvalidIdentifierException;
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
     * @param string|null $alias
     * @return Proxy
     *
     * @throws MockNotCreatedException
     * @throws InvalidIdentifierException
     */
    protected function mock(string $fqcn, string $alias = null): Proxy
    {
        return Moka::brew($fqcn, $alias);
    }
}
