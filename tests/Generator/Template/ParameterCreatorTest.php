<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\ParameterCreator;
use Moka\Generator\Template\ParameterTemplate;
use Moka\Tests\FooTestClass;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\TestCase;

class ParameterCreatorTest extends TestCase
{
    public function testGenerate()
    {
        $method = new \ReflectionMethod(FooTestClass::class, 'withArguments');
        $parameters = $method->getParameters();

        /** @var Param $code */
        $code = ParameterCreator::create(
            $parameters[2]
        );

        $this->assertInstanceOf(Param::class, $code);
        $this->assertEquals('string', $code->type);
        $this->assertEquals('byReference', $code->var->name);
        /** @var String_ $default */
        $default = $code->default;

        $this->assertEquals(PHP_EOL, $default->value);
    }
}
