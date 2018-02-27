<?php
declare(strict_types=1);

namespace Moka\Plugin\PHPUnit;

use Moka\Exception\MissingDependencyException;
use Moka\Exception\NotImplementedException;
use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @param string $fqcnOrAlias
 * @param string|null $alias
 * @return MockObject|ProxyInterface
 * @throws MissingDependencyException
 * @throws NotImplementedException
 */
function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
{
    return Moka::phpunit($fqcnOrAlias, $alias);
}
