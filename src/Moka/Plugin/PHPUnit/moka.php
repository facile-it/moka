<?php
declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @param string $fqcnOrAlias
 * @param string|null $alias
 * @return ProxyInterface|MockObject;
 */
function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
{
    return Moka::phpunit($fqcnOrAlias, $alias);
}
