<?php
declare(strict_types=1);

namespace Moka\Plugin\Prophecy;

use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @param string $fqcnOrAlias
 * @param string|null $alias
 * @return ObjectProphecy|ProxyInterface
 */
function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
{
    return Moka::prophecy($fqcnOrAlias, $alias);
}
