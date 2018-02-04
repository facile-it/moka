<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Class PropertyInitializationTemplate
 * @package Moka\Generator\Template
 */
class PropertyInitializationTemplate implements TemplateInterface
{
    private const TEMPLATE = '
            unset(%s%s);
    ';

    /**
     * @param \Reflector|\ReflectionProperty $property
     * @return string
     */
    public static function generate(\Reflector $property): string
    {
        return self::doGenerate($property);
    }

    /**
     * @param \ReflectionProperty $property
     * @return string
     */
    protected static function doGenerate(\ReflectionProperty $property): string
    {
        $scope = $property->isStatic() ? 'self::$' : '$this->';

        $name = $property->name;

        return sprintf(
            self::TEMPLATE,
            $scope,
            $name
        );
    }
}
