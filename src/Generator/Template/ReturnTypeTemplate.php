<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Class ReturnTypeTemplate
 * @package Moka\Generator\Template
 */
class ReturnTypeTemplate implements TemplateInterface
{
    private const TEMPLATE = ': %s%s';

    /**
     * @param \Reflector|\ReflectionMethod $method
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
        $originalReturnType = $method->getReturnType();
        $allowsNull = $originalReturnType->allowsNull()
            ? '?'
            : '';

        $returnType = 'self' === (string)$originalReturnType
            ? $method->getDeclaringClass()->name
            : (string)$originalReturnType;

        return sprintf(
            self::TEMPLATE,
            $allowsNull,
            $returnType
        );
    }
}
