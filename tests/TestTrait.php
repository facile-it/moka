<?php
declare(strict_types=1);

namespace Tests;

use Moka\Moka;
use Moka\Proxy\Proxy;

/**
 * Trait TestTrait
 * @package Tests
 */
trait TestTrait
{

    /**
     * @param string $fqcn
     * @param string|null $alias
     * @return Proxy
     */
    protected function mock(string $fqcn, string $alias = null): Proxy
    {
        $builder = getenv('MOCKING_STRATEGY') ?: 'get';

        return Moka::$builder($fqcn, $alias);
    }
}
