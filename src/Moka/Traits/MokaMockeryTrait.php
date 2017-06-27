<?php
declare(strict_types=1);

namespace Moka\Traits;

use Mockery\MockInterface;
use Moka\Moka;
use Moka\Proxy\Proxy;

/**
 * Trait MokaMockeryTrait
 * @package Moka\Traits
 */
trait MokaMockeryTrait
{
    /**
     * @param string $fqcnOrAlias
     * @param string|null $alias
     * @return Proxy|MockInterface
     */
    protected function mock(string $fqcnOrAlias, string $alias = null): Proxy
    {
        return Moka::mockery($fqcnOrAlias, $alias);
    }
}
