<?php
declare(strict_types=1);

namespace Tests\Generator\ASTFactory;

use Moka\Exception\InvalidArgumentException;
use Moka\Tests\FooTestClass;
use Moka\Tests\PHP71TestClass;
use PhpParser\Node;
use PHPUnit\Framework\TestCase;
use function Moka\Generator\ASTFactory\createReturnType;

class CreateReturnTypeTest extends TestCase
{
    public function testCreateReturnType(): void
    {
        $node = createReturnType(
            new \ReflectionMethod(FooTestClass::class, 'getCallable')
        );

        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('callable', $node->parts[0]);
    }

    public function testGenerateWithSelfNullable(): void
    {
        $node = createReturnType(
            new \ReflectionMethod(PHP71TestClass::class, 'getSelfNullable')
        );

        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('self', implode('\\', $node->type->parts));
    }

    public function testGenerateWithoutReturType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        createReturnType(
            new \ReflectionMethod(FooTestClass::class, 'abstractMethod')
        );
    }
}
