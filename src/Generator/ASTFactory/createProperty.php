<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Generator\Template\MethodCreator;
use Moka\Generator\Template\PropertyCreator;
use Moka\Generator\Template\PropertyInitializationCreator;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use ReflectionException;

const EXCLUDED_METHODS = [
    '__construct',
    '__destruct',
    '__clone'
];

function createProperty(ReflectionProperty $property): Node
{
    $visibility = static::getVisibility($property);

    $factory = new BuilderFactory();
    $propertyNode = $factory->property($property->name);

    if ($property->isStatic()) {
        $propertyNode->makeStatic();
    }

    $visibilityMethodName = 'make' . ucfirst($visibility);
    $propertyNode->$visibilityMethodName();

    return $propertyNode->getNode();
}
