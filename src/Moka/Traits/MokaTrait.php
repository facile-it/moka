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
 *
 * @deprecated since v1.2.0
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
     *
     * @deprecated since v1.2.0
     */
    protected function mock(string $fqcn, string $alias = null): Proxy
    {
        return Moka::phpunit($fqcn, $alias);
    }
}
