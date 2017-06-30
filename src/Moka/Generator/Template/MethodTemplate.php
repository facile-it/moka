<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Class MethodTemplate
 * @package Moka\Generator\Template
 */
class MethodTemplate implements TemplateInterface
{
    const TEMPLATE = '
        public %s function %s(%s)%s
        {
            %s $this->__call("%s", func_get_args());
        }
    ';

    /**
     * @param \ReflectionMethod $method
     * @return string
     */
    public static function generate(\Reflector $method): string
    {
        return static::doGenerate($method);
    }

    /**
     * @param \ReflectionMethod $method
     * @return string
     */
    protected static function doGenerate(\ReflectionMethod $method): string
    {
        $static = $method->isStatic() ? 'static' : '';

        $parameters = $method->getParameters();
        $parametersCode = [];
        if (is_array($parameters)) {
            foreach ($parameters as $parameter) {
                $parametersCode[] = ParameterTemplate::generate($parameter);
            }
        }

        $originalReturnType = $method->getReturnType();
        $returnType = $originalReturnType
            ? ReturnTypeTemplate::generate($method)
            : '';

        $returnStatement = null === $originalReturnType || 'void' !== (string)$originalReturnType
            ? 'return'
            : '';

        $methodName = $method->name;

        return sprintf(
            self::TEMPLATE,
            $static,
            $methodName,
            implode(',', $parametersCode),
            $returnType,
            $returnStatement,
            $methodName
        );
    }
}
