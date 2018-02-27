<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use ReflectionParameter;

/**
 * @param ReflectionParameter $parameter
 * @return mixed
 * @throws InvalidArgumentException
 */
function createParameterDefaultValue(\ReflectionParameter $parameter)
{
    if (false === $parameter->isDefaultValueAvailable()) {
        throw new InvalidArgumentException(sprintf(
            'A default value fot parameter %s is not available',
            $parameter->name
        ));
    }

    $defaultValue = $parameter->getDefaultValue();
    $parameterType = $parameter->getType();
    if ($parameterType instanceof \ReflectionType) {
        $isArray = $parameterType->getName() === 'array';
        if ($isArray && (!\is_array($defaultValue) && null !== $defaultValue)) {
            throw new InvalidArgumentException(sprintf(
                'Default value for parameters with array type can only be an array or NULL. %s given',
                \gettype($defaultValue)
            ));
        }
    }

    $defaultValue = $defaultValue !== 'NULL'
        ? $defaultValue
        : null;

    return $defaultValue;
}
