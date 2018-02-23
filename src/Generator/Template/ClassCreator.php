<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Exception\InvalidArgumentException;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;

/**
 * Class ClassCreator
 */
class ClassCreator implements NodeCreator
{
    private const UNSAFE_METHODS = [
        '__construct',
        '__destruct',
        '__clone'
    ];

    /**
     * @param \Reflector|\ReflectionClass $class
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    public static function create(\Reflector $class): Node
    {
        return static::doGenerate($class);
    }

    public static function createWithName(\Reflector $class, string $proxyClassName): Node
    {
        return static::doGenerate($class, $proxyClassName);
    }

    /**
     * @param \ReflectionClass $class
     * @param string $proxyClassName
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    protected static function doGenerate(\ReflectionClass $class, string $proxyClassName): Node
    {
        $factory = new BuilderFactory();

        $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
        $propertiesNodes = [];

        $constructorNodes = [];

        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodNodes = [];

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $propertiesNodes[] = PropertyCreator::create($property);
            $constructorNodes[] = PropertyInitializationCreator::create($property);
        }

        foreach ($methods as $method) {
            if ($method->isFinal()
                || \in_array($method->name, self::UNSAFE_METHODS, $strict = true)
            ) {
                continue;
            }

            $methodToCalls = '__call';
            $forceReturn = false;

            if ('__call' === $method->name) {
                $methodToCalls = 'doCall';
                $forceReturn = true;
            }

            if ('__get' === $method->name) {
                $methodToCalls = 'doGet';
                $forceReturn = true;
            }

            $methodNodes[] = MethodCreator::createWithParams($method, $methodToCalls, $forceReturn);
        }

        try {
            $class->getMethod('__call');
            $callMethodExsists = true;
        } catch (\ReflectionException $e) {
            $callMethodExsists = false;
        }

        if (false === $callMethodExsists) {
            $methodNodes[] = MethodCreator::createCallMethod($forceReturn = true);
        }

        $mockClassName = $class->name;

        $node = $factory
            ->class($proxyClassName)
            ->extend(new Node\Name($mockClassName))
            ->implement(...[new Node\Name(ProxyInterface::class)])
            ->addStmt(new Node\Stmt\TraitUse([new Node\Name(ProxyTrait::class)]))
            ->addStmts($propertiesNodes)
            ->addStmt(
                $factory->method('__construct')
                    ->makePublic()
                    ->addStmts($constructorNodes)
            )
            ->addStmts($methodNodes);

        return $node->getNode();
    }
}
