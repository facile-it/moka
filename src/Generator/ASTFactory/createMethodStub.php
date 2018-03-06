<?php
declare(strict_types=1);

namespace Moka\Generator\ASTFactory;

use Moka\Proxy\ProxyInterface;
use PhpParser\BuilderFactory;
use PhpParser\Node;

/**
 * @return Node\Stmt\ClassMethod
 */
function createMethodStub(): Node\Stmt\ClassMethod
{
    $factory = new BuilderFactory();
    $method = $factory->method('stub')->makePublic();
    $method->addParams([
        new Node\Param(
            $variable = new Node\Expr\Variable(new Node\Name('namesWithValues')),
            $noDefault = null,
            $type = 'array'
        )
    ]);

    $args = [
        new Node\Expr\Variable('namesWithValues')
    ];

    $stmt = new Node\Expr\MethodCall(
        new Node\Expr\Variable(new Node\Name('this')),
        'doStub',
        $args
    );

    $return = new Node\Stmt\Return_(new Node\Expr\Variable(new Node\Name('this')));


    $method->addStmt($stmt);
    $method->addStmt($return);
    $method->setReturnType(ProxyInterface::class);

    return $method->getNode();
}
