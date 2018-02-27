<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use PhpParser\Node;
use ReflectionProperty;
use RuntimeException;

/**
 * @param ReflectionProperty $property
 * @return Node\Stmt\Unset_
 * @throws RuntimeException
 * @throws InvalidArgumentException
 */
function createPropertyUnset(ReflectionProperty $property): Node\Stmt\Unset_
{
    $propertyName = $property->name;
    $fetch = $property->isStatic()
        ? new Node\Expr\StaticPropertyFetch(new Node\Name('self'), $propertyName)
        : new Node\Expr\PropertyFetch(new Node\Expr\Variable('this'), $propertyName);

    return new Node\Stmt\Unset_([$fetch]);
}
