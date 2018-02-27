<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use PhpParser\BuilderFactory;
use PhpParser\Node;

/**
 * @param bool $forceReturn
 * @return Node\Stmt\ClassMethod
 */
function createMethodGet(bool $forceReturn = false): Node\Stmt\ClassMethod
{
    $factory = new BuilderFactory();
    $method = $factory->method('__get')->makePublic();
    $method->addParams([
        new Node\Param(new Node\Expr\Variable(new Node\Name('name')))
    ]);

    $args = [
        new Node\Expr\Variable('name')
    ];

    $stmt = new Node\Expr\MethodCall(
        new Node\Expr\Variable(new Node\Name('this')),
        'doGet',
        $args
    );

    if ($forceReturn) {
        $stmt = new Node\Stmt\Return_($stmt);
    }

    $method->addStmt($stmt);

    return $method->getNode();
}
