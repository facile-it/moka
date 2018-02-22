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
    private const TEMPLATE = '%s %s%s%s%s';

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
        $hasDefaultValue = true;
        try {
            $defaultValue = !$parameter->allowsNull()
                ? static::getDefaultValue($parameter)
                : null;
        } catch (\Exception $exception) {
            $hasDefaultValue = false;
        }

        if (true === $hasDefaultValue && isset($defaultValue)) {
            $param->setDefault($defaultValue);
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

        return $param->getNode();
    }

    private static function getDefaultValue(\ReflectionParameter $parameter)
    {
        if ($parameter->isVariadic()) {
            throw new InvalidArgumentException(
                'Variadic parameter cannot have a default value'
            );
        }

        $defaultValue = $parameter->getDefaultValue();
        $parameterType = $parameter->getType();
        if ($parameterType instanceof \ReflectionType) {
            $isArray = $parameterType->getName() === 'array';
            if ($isArray && (!\is_array($defaultValue) || null !== $defaultValue)) {
                throw new InvalidArgumentException(sprintf(
                    'Default value for parameters with array type can only be an array or NULL. %s given',
                    gettype($defaultValue)
                ));
            }
        }


        return var_export($defaultValue, true);
    }
}
