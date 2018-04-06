<?php
declare(strict_types=1);

namespace Moka\Plugin\Prophecy;

use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Moka\Exception\InvalidArgumentException;
use Moka\Exception\InvalidIdentifierException;
use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @param string $fqcnOrAlias
 * @param string|null $alias
 * @return MockObject|ProxyInterface
 * @throws MissingDependencyException
 * @throws NotImplementedException
 * @throws \ReflectionException
 */
function moka(string $fqcnOrAlias, string $alias = null): ProxyInterface
{
    return Moka::prophecy($fqcnOrAlias, $alias);
}
