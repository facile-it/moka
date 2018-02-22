<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Exception\InvalidArgumentException;
use PhpParser\BuilderFactory;
use PhpParser\Node;

/**
 * Class ParameterCreatpr
 */
class ParameterCreator implements NodeCreator
{
    /**
     * @param \Reflector|\ReflectionParameter $parameter
     * @return Node
     */
    public static function create(\Reflector $parameter): Node
    {
        return static::doGenerate($parameter);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return Node
     */
    protected static function doGenerate(\ReflectionParameter $parameter): Node
    {
        $factory = new BuilderFactory();
        $param = $factory->param($parameter->name);

        try {
            $defaultValue = static::getDefaultValue($parameter);
            $hasDefaultValue = true;
        } catch (\Exception $exception) {
            $hasDefaultValue = false;
        }

        $type = $parameter->getType()
            ? (string)$parameter->getType()
            : null;

        if (null !== $type) {
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

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws InvalidArgumentException
     */
    private static function getDefaultValue(\ReflectionParameter $parameter)
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
            if ($isArray && (!\is_array($defaultValue) || null !== $defaultValue)) {
                throw new InvalidArgumentException(sprintf(
                    'Default value for parameters with array type can only be an array or NULL. %s given',
                    \gettype($defaultValue)
                ));
            }
        }

        $defaultValue = var_export($defaultValue, true);
        $defaultValue = $defaultValue !== 'NULL'
            ? $defaultValue
            : null;

        return $defaultValue;
    }
}
