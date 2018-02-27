<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use PhpParser\Node;
use ReflectionMethod;

/**
 * @param ReflectionMethod $method
 * @return Node|Node\Name|Node\NullableType
 * @throws InvalidArgumentException
 */
function createReturnType(ReflectionMethod $method): Node
{
    $originalReturnType = $method->getReturnType();
    if (null === $originalReturnType) {
        throw new InvalidArgumentException(sprintf(
            'The method with name %s has not a return type',
            $method->name
        ));
    }

    $returnType = (string)$originalReturnType;

    $node = new Node\Name($returnType);
    if (false !== $originalReturnType->allowsNull()) {
        $node = new Node\NullableType($node);
    }

    return $node;
}
