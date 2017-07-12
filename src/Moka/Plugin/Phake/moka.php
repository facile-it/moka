<?php
declare(strict_types=1);

namespace Moka\Plugin\Phake;

use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use Phake_IMock as PhakeMock;

/**
 * @param string $fqcnOrAlias
 * @param string|null $alias
 * @return PhakeMock|ProxyInterface
 */
function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
{
    return Moka::phake($fqcnOrAlias, $alias);
}
