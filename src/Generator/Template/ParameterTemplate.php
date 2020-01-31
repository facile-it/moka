<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Class ParameterTemplate
 * @package Moka\Generator\Template
 */
class ParameterTemplate implements TemplateInterface
{
    private const TEMPLATE = '%s %s%s%s%s';

    /**
     * @param \Reflector|\ReflectionParameter $parameter
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
        } catch (\Exception $exception) {
            $defaultValue = '';
        }

        $type = $parameter->getType()
            ? $parameter->getType()->getName()
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

        $name = '$' . $parameter->name;

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
