<?php
declare(strict_types=1);

namespace Tests\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use function Moka\Generator\ASTFactory\createReturnType;
use Moka\Tests\FooTestClass;
use Moka\Tests\PHP71TestClass;
use PhpParser\Node;
use PHPUnit\Framework\TestCase;

class CreateReturnTypeTest extends TestCase
{
    public function testCreateReturnType()
    {
        $node = createReturnType(
            new \ReflectionMethod(FooTestClass::class, 'getCallable')
        );

        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('callable', $node->parts[0]);
    }

    public function testGenerateWithSelfNullable()
    {
        $node = createReturnType(
            new \ReflectionMethod(PHP71TestClass::class, 'getSelfNullable')
        );

        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('self', implode('\\', $node->type->parts));
    }

    public function testGenerateWithoutReturType()
    {
        $this->expectException(InvalidArgumentException::class);
        createReturnType(
            new \ReflectionMethod(FooTestClass::class, 'abstractMethod')
        );
    }
}
