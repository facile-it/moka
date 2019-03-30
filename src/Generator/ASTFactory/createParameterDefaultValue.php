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

    $defaultValue = $defaultValue !== 'NULL'
        ? $defaultValue
        : null;

    return $defaultValue;
}
