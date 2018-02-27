<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use PhpParser\Builder\Method;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use ReflectionMethod;
use RuntimeException;

/**
 * @param \Reflector|\ReflectionMethod $method
 * @param string $methodToCalls
 * @param bool $forceReturn
 * @return Node\Stmt\ClassMethod
 * @throws RuntimeException
 * @throws InvalidArgumentException
 */
function createMethod(
    ReflectionMethod $method,
    string $methodToCalls,
    bool $forceReturn = false
): Node\Stmt\ClassMethod {
    $factory = new BuilderFactory();

    $parameters = $method->getParameters();
    $parameterNodes = [];
    if (\is_array($parameters)) {
        foreach ($parameters as $parameter) {
            $parameterNodes[] = createParameter($parameter);
        }
    }

    $originalReturnType = $method->getReturnType();

    $willReturn = null === $originalReturnType || 'void' !== (string)$originalReturnType || $forceReturn;

    $methodName = $method->name;

    $visibility = getVisibility($method);
    $makeVisibility = 'make' . ucfirst($visibility);

    /** @var Method $node */
    $node = $factory->method($methodName)->$makeVisibility();

    if ($method->isStatic()) {
        $node->makeStatic();
    }

    $node->addParams($parameterNodes);

    $args = [
        new Node\Scalar\String_($methodName),
        new Node\Expr\FuncCall(new Node\Name('func_get_args'))
    ];

    if (\in_array($methodName, ['__call', '__get'], $strict = true)) {
        $args = [
            new Node\Expr\Variable($parameters[0]->name),
            new Node\Expr\Variable($parameters[1]->name)
        ];
    }

    $stmt = new Node\Expr\MethodCall(
        new Node\Expr\Variable(new Node\Name('this')),
        $methodToCalls,
        $args
    );

    if ($willReturn) {
        $stmt = new Node\Stmt\Return_($stmt);
    }

    $node->addStmt($stmt);

    $returnType = $originalReturnType
        ? createReturnType($method)
        : null;

    if (null !== $returnType) {
        $node->setReturnType($returnType);
    }

    return $node->getNode();
}
