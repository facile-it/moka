<?php
declare(strict_types=1);

namespace Moka\Generator\Template;
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
        try {
            $defaultValue = !$parameter->allowsNull()
                ? var_export($parameter->getDefaultValue(), true)
                : null;
            $hasDefaultValue = true;
        } catch (\Exception $exception) {
            $hasDefaultValue = false;
        }

        if (true === $hasDefaultValue) {
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
}
