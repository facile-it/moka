<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use PhpParser\BuilderFactory;
use PhpParser\Node;

/**
 * @param bool $forceReturn
 * @return Node\Stmt\ClassMethod
 */
function createMethodCall(bool $forceReturn = false): Node\Stmt\ClassMethod
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
