<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Exception\InvalidArgumentException;
use PhpParser\Builder\Method;
use PhpParser\BuilderFactory;
use PhpParser\Node;

/**
 * Class MethodCreator
 */
class MethodCreator implements NodeCreator
{
    use VisibilityTrait;

    /**
     * @param \Reflector|\ReflectionMethod $method
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    public static function create(\Reflector $method): Node
    {
        return static::doGenerate($method);
    }

    /**
     * @param \Reflector|\ReflectionMethod $method
     * @param string $methodToCalls
     * @param bool $forceReturn
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    public static function createWithParams(
        \ReflectionMethod $method,
        string $methodToCalls, bool
        $forceReturn = false
    ): Node
    {
        return static::doGenerate($method, $methodToCalls, $forceReturn);
    }

    /**
     * @param \Reflector|\ReflectionMethod $method
     * @param string $methodToCalls
     * @param bool $forceReturn
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    protected static function doGenerate(
        \ReflectionMethod $method,
        string $methodToCalls = '__call',
        bool $forceReturn = false
    ): Node
    {
        $factory = new BuilderFactory();

        $parameters = $method->getParameters();
        $parameterNodes = [];
        if (\is_array($parameters)) {
            foreach ($parameters as $parameter) {
                $parameterNodes[] = ParameterCreator::create($parameter);
            }
        }

        $originalReturnType = $method->getReturnType();

        $willReturn = null === $originalReturnType || 'void' !== (string)$originalReturnType || $forceReturn;

        $methodName = $method->name;

        $visibility = static::getVisibility($method);
        $makeVisibility = 'make' . ucfirst($visibility);

        /** @var Method $node */
        $node = $factory->method($methodName)->$makeVisibility();

        if ($method->isStatic()) {
            $node->makeStatic();
        }

        $node->addParams($parameterNodes);

        $funcGetArgs = new Node\Expr\FuncCall(new Node\Name('func_get_args'));
        $params = new Node\Expr\Assign(
            new Node\Expr\Variable(new Node\Name('params')),
            $funcGetArgs
        );

        $args = [
            new Node\Scalar\String_($methodName),
            new Node\Expr\Variable('params')
        ];

        $stmt = new Node\Expr\MethodCall(
            new Node\Expr\Variable(new Node\Name('this')),
            $methodToCalls,
            $args
        );

        if ($willReturn) {
            $stmt = new Node\Stmt\Return_($stmt);
        }

        $node->addStmt($params);
        $node->addStmt($stmt);

        $returnType = $originalReturnType
            ? ReturnTypeCreator::create($method)
            : null;

        if (null !== $returnType) {
            $node->setReturnType($returnType);
        }

        return $node->getNode();
    }

    /**
     * @param bool $forceReturn
     * @return Node
     */
    public static function createCallMethod(bool $forceReturn = false): Node
    {
        $factory = new BuilderFactory();
        $method = $factory->method('__call')->makePublic();
        $method->addParams([
            new Node\Param(new Node\Expr\Variable(new Node\Name('name'))),
            new Node\Param(new Node\Expr\Variable(new Node\Name('params')))
        ]);

        $args = [
            new Node\Expr\Variable('name'),
            new Node\Expr\Variable('params')
        ];

        $stmt = new Node\Expr\MethodCall(
            new Node\Expr\Variable(new Node\Name('this')),
            'doCall',
            $args
        );

        if ($forceReturn) {
            $stmt = new Node\Stmt\Return_($stmt);
        }

        $method->addStmt($stmt);

        return $method->getNode();
    }
}
