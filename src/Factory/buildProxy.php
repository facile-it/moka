<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Generator\ProxyGenerator;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;

/**
 * @param string $fqcn
 * @param MockingStrategyInterface $mockingStrategy
 * @return ProxyInterface
 *
 * @throws \ReflectionException
 * @throws InvalidArgumentException
 * @throws MockNotCreatedException
 */
function buildProxy(string $fqcn, MockingStrategyInterface $mockingStrategy): ProxyInterface
{
    return (new ProxyGenerator($mockingStrategy))->get($fqcn);
}
