<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use ReflectionProperty;
use RuntimeException;

/**
 * @param ReflectionProperty $property
 * @return Node\Stmt\Property
 * @throws RuntimeException
 * @throws InvalidArgumentException
 */
function createProperty(ReflectionProperty $property): Node\Stmt\Property
{
    $visibility = getVisibility($property);

    $factory = new BuilderFactory();
    $propertyNode = $factory->property($property->name);

    if ($property->isStatic()) {
        $propertyNode->makeStatic();
    }

    $visibilityMethodName = 'make' . ucfirst($visibility);
    $propertyNode->$visibilityMethodName();

    return $propertyNode->getNode();
}
