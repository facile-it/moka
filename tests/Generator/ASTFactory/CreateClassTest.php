<?php
declare(strict_types=1);

namespace Tests\Generator\ASTFactory;

use function Moka\Generator\ASTFactory\createClass;
use Moka\Proxy\ProxyInterface;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;

class CreateClassTest extends TestCase
{
    private const CLASSNAME = 'Moka_Test_stdClass';
    public function testCreateClass(): void
    {
        $node = createClass(
            new \ReflectionClass(\stdClass::class),
            self::CLASSNAME
        );

        $this->assertInstanceOf(Class_::class, $node);
        $this->assertEquals(self::CLASSNAME, $node->name);
        $this->assertNotEmpty($node->extends->parts);
        $this->assertEquals(ProxyInterface::class, implode('\\', $node->implements));
    }
}
