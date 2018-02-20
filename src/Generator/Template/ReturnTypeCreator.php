<?php
declare(strict_types=1);

namespace Moka\Generator\Template;
use Moka\Exception\InvalidArgumentException;
use PhpParser\Node;

/**
 * Class ReturnTypeCreator
 */
class ReturnTypeCreator implements NodeCreator
{
    /**
     * @param \Reflector|\ReflectionMethod $method
     * @return Node
     */
    public static function create(\Reflector $method): Node
    {
        return static::doGenerate($method);
    }

    /**
     * @param \ReflectionMethod $method
     * @return Node
     * @throws InvalidArgumentException
     */
    protected static function doGenerate(\ReflectionMethod $method): Node
    {
        $originalReturnType = $method->getReturnType();
        if (null === $originalReturnType) {
            throw new InvalidArgumentException(sprintf(
                'The method with name %s has not a return type',
                $method->name
            ));
        }

        $returnType = 'self' === (string)$originalReturnType
            ? $method->getDeclaringClass()->name
            : (string)$originalReturnType;

        $node = new Node\Name($returnType);
        if (false !== $originalReturnType->allowsNull()) {
            $node = new Node\NullableType($node);
        }

        return $node;
    }
}
