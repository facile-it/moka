<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Generator\ProxyGenerator;
use Moka\Proxy\ProxyInterface;
use Moka\Strategy\MockingStrategyInterface;
use PhpParser\PrettyPrinter\Standard as ASTPrinter;

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
    return (new ProxyGenerator($mockingStrategy, new ASTPrinter()))->get($fqcn);
}
