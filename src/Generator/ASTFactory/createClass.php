<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Generator\Template\MethodCreator;
use Moka\Generator\Template\PropertyInitializationCreator;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use ReflectionException;

const EXCLUDED_METHODS = [
    '__construct',
    '__destruct',
    '__clone'
];

/**
 * @param \ReflectionClass $class
 * @param string $proxyClassName
 * @return Node
 * @throws \RuntimeException
 */
function createClass(ReflectionClass $class, string $proxyClassName): Node
{
    $factory = new BuilderFactory();

    $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
    $propertiesNodes = [];

    $constructorNodes = [];

    $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
    $methodNodes = [];

    foreach ($properties as $property) {
        if ($property->isStatic()) {
            continue;
        }

        $propertiesNodes[] = createProperty($property);
        $constructorNodes[] = PropertyInitializationCreator::create($property);
    }

    foreach ($methods as $method) {
        if ($method->isFinal()
            || \in_array($method->name, EXCLUDED_METHODS, $strict = true)
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
        $callMethodExists = true;
    } catch (ReflectionException $e) {
        $callMethodExists = false;
    }

    if (false === $callMethodExists) {
        $methodNodes[] = MethodCreator::createCallMethod($forceReturn = true);
    }

    try {
        $class->getMethod('__get');
        $getMethodExists = true;
    } catch (ReflectionException $e) {
        $getMethodExists = false;
    }

    if (false === $getMethodExists) {
        $methodNodes[] = MethodCreator::createGetMethod($forceReturn = true);
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
