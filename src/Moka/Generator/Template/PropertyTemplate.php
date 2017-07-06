<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Class PropertyTemplate
 * @package Moka\Generator\Template
 */
class PropertyTemplate implements TemplateInterface
{
    use VisibilityTrait;

    const TEMPLATE = '
        %s %s $%s;
    ';

    /**
     * @param \ReflectionProperty $property
     * @return string
     */
    public static function generate(\Reflector $property): string
    {
        return static::doGenerate($property);
    }

    /**
     * @param \ReflectionProperty $property
     * @return string
     */
    protected static function doGenerate(\ReflectionProperty $property): string
    {
        $visibility = static::getVisibility($property);

        $static = $property->isStatic() ? 'static' : '';

        $name = $property->getName();

        return sprintf(
            self::TEMPLATE,
            $visibility,
            $static,
            $name
        );
    }
}
