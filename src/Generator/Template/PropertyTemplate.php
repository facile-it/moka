<?php
declare(strict_types=1);

namespace Moka\Generator\Template;
use Moka\Exception\InvalidArgumentException;

/**
 * Class PropertyTemplate
 * @package Moka\Generator\Template
 */
class PropertyTemplate implements TemplateInterface
{
    use VisibilityTrait;

    private const TEMPLATE = '
        %s %s $%s;
    ';

    /**
     * @param \Reflector|\ReflectionProperty $property
     * @return string
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    public static function generate(\Reflector $property): string
    {
        return static::doGenerate($property);
    }

    /**
     * @param \ReflectionProperty $property
     * @return string
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    protected static function doGenerate(\ReflectionProperty $property): string
    {
        $visibility = static::getVisibility($property);

        $static = $property->isStatic() ? 'static' : '';

        $name = $property->name;

        return sprintf(
            self::TEMPLATE,
            $visibility,
            $static,
            $name
        );
    }
}
