<?php
declare(strict_types=1);

namespace Tests\Generator\ASTFactory;

use function Moka\Generator\ASTFactory\createMethod;
use function Moka\Generator\ASTFactory\createMethodCall;
use function Moka\Generator\ASTFactory\createMethodGet;
use Moka\Tests\FooTestClass;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;

class CreateMethodTest extends TestCase
{
    const METHOD_NAME = 'getCallable';

    public function testCreateMethod()
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

    public function testCreateMethodCall()
    {
        $node = createMethodCall($forceReturn = true);

        $this->assertInstanceOf(ClassMethod::class, $node);
        $this->assertEquals('__call', $node->name);
        $this->assertTrue($node->isPublic());
    }

    public function testCreateMethodGet()
    {
        $node = createMethodGet($forceReturn = true);

        $this->assertInstanceOf(ClassMethod::class, $node);
        $this->assertEquals('__get', $node->name);
        $this->assertTrue($node->isPublic());
    }
}
