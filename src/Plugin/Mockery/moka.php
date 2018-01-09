<?php
declare(strict_types=1);

namespace Moka\Plugin\Mockery;

use Mockery\MockInterface;
use Moka\Moka;
use Moka\Proxy\ProxyInterface;

/**
 * @param string $fqcnOrAlias
 * @param string|null $alias
 * @return MockInterface|ProxyInterface
 */
function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
{
    return Moka::mockery($fqcnOrAlias, $alias);
}
