<?php
declare(strict_types=1);

namespace Tests\Generator\ASTFactory;

use Moka\Tests\FooTestClass;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;
use function Moka\Generator\ASTFactory\createMethod;
use function Moka\Generator\ASTFactory\createMethodCall;
use function Moka\Generator\ASTFactory\createMethodGet;

class CreateMethodTest extends TestCase
{
    public const METHOD_NAME = 'getCallable';

    public function testCreateMethod(): void
    {
        $node = createMethod(
            new \ReflectionMethod(FooTestClass::class, self::METHOD_NAME),
            '__call',
            $forceReturn = true
        );

        $this->assertInstanceOf(ClassMethod::class, $node);
        $this->assertEquals(self::METHOD_NAME, $node->name);
        $this->assertEquals('callable', $node->getReturnType());
        $this->assertTrue($node->isPublic());
    }

    public function testCreateMethodCall(): void
    {
        $node = createMethodCall($forceReturn = true);

        $this->assertInstanceOf(ClassMethod::class, $node);
        $this->assertEquals('__call', $node->name);
        $this->assertTrue($node->isPublic());
    }

    public function testCreateMethodGet(): void
    {
        $node = createMethodGet($forceReturn = true);

        $this->assertInstanceOf(ClassMethod::class, $node);
        $this->assertEquals('__get', $node->name);
        $this->assertTrue($node->isPublic());
    }
}
