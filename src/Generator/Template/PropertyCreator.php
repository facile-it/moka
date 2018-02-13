<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Exception\InvalidArgumentException;
use PhpParser\BuilderFactory;
use PhpParser\Node;

/**
 * Class CreateProperty
 */
class PropertyCreator implements NodeCreator
{
    use VisibilityTrait;

    /**
     * @param \Reflector|\ReflectionProperty $property
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    public static function create(\Reflector $property): Node
    {
        return static::doGenerate($property);
    }

    /**
     * @param \ReflectionProperty $property
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    protected static function doGenerate(\ReflectionProperty $property): Node
    {
        $visibility = static::getVisibility($property);

        $factory = new BuilderFactory();
        $propertyNode = $factory->property($property->name);

        if ($property->isStatic()) {
            $propertyNode->makeStatic();
        }

        $visibilityMethodName = 'make' . ucfirst($visibility);
        $propertyNode->$visibilityMethodName();

        return $propertyNode->getNode();
    }
}
