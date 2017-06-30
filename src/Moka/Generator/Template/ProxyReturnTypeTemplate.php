<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Class ProxyReturnTypeTemplate
 * @package Moka\Generator\Template
 */
class ProxyReturnTypeTemplate implements ProxyTemplateInterface
{
    const TEMPLATE = ': %s%s';

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
