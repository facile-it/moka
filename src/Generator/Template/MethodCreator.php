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

    private const TEMPLATE = '
        %s %s function %s(%s)%s
        {
            %s $this->__call("%s", func_get_args());
        }
    ';

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
     * @param \ReflectionMethod $method
     * @return Node
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    protected static function doGenerate(\ReflectionMethod $method): Node
    {
        $factory = new BuilderFactory();

        $parameters = $method->getParameters();
        $parametersCode = [];
        if (\is_array($parameters)) {
            foreach ($parameters as $parameter) {
                $parametersCode[] = ParameterTemplate::generate($parameter);
            }
        }

        $originalReturnType = $method->getReturnType();
        $returnType = $originalReturnType
            ? ReturnTypeTemplate::generate($method)
            : '';

        $willReturn = null === $originalReturnType || 'void' !== (string)$originalReturnType;

        $methodName = $method->name;

        $visibility = static::getVisibility($method);
        $makeVisbility = 'make' . ucfirst($visibility);

        /** @var Method $node */
        $node = $factory->method($methodName)->$makeVisbility();
        if ($method->isStatic()) {
            $node->makeStatic();
        }

        $node->setReturnType($returnType);

        $stmt = new Node\Expr\MethodCall(
            new Node\Expr\Variable('this'),
            '__call',
            new Node\Expr\FuncCall(new Node\Name('func_get_args'))
        );

        if ($willReturn) {
            $stmt = new Node\Stmt\Return_($stmt);
        }

        $node->addStmt($stmt);

        return $node->getNode();

        /*        return sprintf(
                    self::TEMPLATE,
                    $visibility,
                    $static,
                    $methodName,
                    implode(',', $parametersCode),
                    $returnType,
                    $returnStatement,
                    $methodName
                );*/
    }
}
