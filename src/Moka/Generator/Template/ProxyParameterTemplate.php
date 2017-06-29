<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Class ProxyParameterTemplate
 * @package Moka\Generator\Template
 */
class ProxyParameterTemplate implements ProxyTemplateInterface
{
    const TEMPLATE = '%s %s%s%s%s';

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    public static function generate(\Reflector $parameter): string
    {
        return static::doGenerate($parameter);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    protected static function doGenerate(\ReflectionParameter $parameter): string
    {
        try {
            $defaultValue = !$parameter->allowsNull()
                ? var_export($parameter->getDefaultValue(), true)
                : 'null';

            $defaultValue = '=' . $defaultValue;
        } catch (\ReflectionException $exception) {
            $defaultValue = '';
        }

        $type = $parameter->getType()
            ? (string)$parameter->getType()
            : '';

        $isPassedByReference = $parameter->isPassedByReference()
            ? '&'
            : '';

        $isVariadic = '';
        if ($parameter->isVariadic()) {
            $isVariadic = '...';
            $isPassedByReference = '';
            $defaultValue = '';
        }

        $name = '$' . $parameter->getName();

        return sprintf(
            self::TEMPLATE,
            $type,
            $isVariadic,
            $isPassedByReference,
            $name,
            $defaultValue
        );
    }
}
