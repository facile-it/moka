<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr;

/**
 * Class PropertyInitializationCreatpr
 */
class PropertyInitializationCreator implements NodeCreator
{
    private const TEMPLATE = '
            unset(%s%s);
    ';

    /**
     * @param \Reflector|\ReflectionProperty $property
     * @return Node
     */
    public static function create(\Reflector $property): Node
    {
        return self::doGenerate($property);
    }

    /**
     * @param \ReflectionProperty $property
     * @return Node
     */
    protected static function doGenerate(\ReflectionProperty $property): Node
    {
        $propertyName = $property->name;
        $fetch = $property->isStatic()
            ? new Node\Expr\StaticPropertyFetch(new Node\Name('self'), $propertyName)
            : new Node\Expr\PropertyFetch(new Node\Expr\Variable('this'), $propertyName);

        $node = new Node\Expr\Assign($fetch, new Expr\Variable(new Node\Stmt\Nop()));

        return $node;
    }
}
