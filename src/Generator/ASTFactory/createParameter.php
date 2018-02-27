<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use ReflectionParameter;

/**
 * @param ReflectionParameter $parameter
 * @return Node
 */
function createParameter(ReflectionParameter $parameter): Node
{
    $factory = new BuilderFactory();
    $param = $factory->param($parameter->name);

    try {
        $defaultValue = createParameterDefaultValue($parameter);
        $hasDefaultValue = true;
    } catch (\Exception $exception) {
        $hasDefaultValue = false;
    }

    $type = (string)$parameter->getType();
    if (!empty($type)) {
        $param->setTypeHint($type);
    }

    if ($parameter->isPassedByReference()) {
        $param->makeByRef();
    }

    if ($parameter->isVariadic()) {
        $param->makeVariadic();
    }

    if (true === $hasDefaultValue && !$parameter->isVariadic()) {
        $param->setDefault($defaultValue);
    }

    return $param->getNode();
}
