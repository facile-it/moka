<?php
declare(strict_types=1);

namespace Tests\Generator\ASTFactory;

use function Moka\Generator\ASTFactory\createParameter;
use Moka\Generator\Template\ParameterCreator;
use Moka\Generator\Template\ParameterTemplate;
use Moka\Tests\FooTestClass;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;

class CreateParameterTest extends TestCase
{
    /**
     * @var \ReflectionParameter[]
     */
    private $reflectionParameters;

    /**
     * @var Standard
     */
    private $prettyPrinter;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->reflectionParameters = (new \ReflectionMethod(FooTestClass::class, 'withArguments'))
            ->getParameters();

        $this->prettyPrinter = new Standard();
    }

    public function testGenerateNotNullableInteger(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[0]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals('int', $param->type);
        $this->assertEquals('required', $param->var->name);

        $this->assertNull($param->default);

        $this->verifyPrint($param, 'int $required');
    }

    public function testGenerateNullable(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[1]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals(null, $param->type);
        $this->assertEquals('nullable', $param->var->name);
        /** @var ConstFetch $default */
        $default = $param->default;
        $this->assertInstanceOf(Name::class, $default->name);

        $this->assertEquals('null', $default->name->parts[0]);

        $this->verifyPrint($param, '$nullable = null');
    }

    public function testGenerateNullableStringByReferenceWithConstantAsDefault(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[2]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals('string', $param->type);
        $this->assertEquals('byReference', $param->var->name);
        /** @var String_ $default */
        $default = $param->default;

        $this->assertEquals(PHP_EOL, $default->value);

        $this->verifyPrint($param, sprintf('string &$byReference = \'%s\'', PHP_EOL));
    }

    public function testGenerateStringAsDefaultValue(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[3]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals('string', $param->type);
        $this->assertEquals('string', $param->var->name);
        /** @var String_ $default */
        $default = $param->default;

        $this->assertEquals('string', $default->value);

        $this->verifyPrint($param, 'string $string = \'string\'');
    }

    public function testGenerateNullableObject(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[4]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals(FooTestClass::class, implode('\\', $param->type->parts));
        $this->assertEquals('class', $param->var->name);
        /** @var ConstFetch $default */
        $default = $param->default;

        $this->assertEquals('null', $default->name->parts[0]);

        $this->verifyPrint($param, sprintf('%s $class = null', FooTestClass::class));
    }

    public function testGenerateArrayWithArrayAsDefaultValue(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[5]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals('array', $param->type);
        $this->assertEquals('array', $param->var->name);
        /** @var Array_ $default */
        $default = $param->default;

        $this->assertCount(1, $default->items);
        $this->assertEquals(3, $default->items[0]->value->value);

        $this->verifyPrint($param, 'array $array = array(3)');
    }

    public function testGenerateNullableCallable(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[6]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals('callable', $param->type);
        $this->assertEquals('callable', $param->var->name);
        /** @var ConstFetch $default */
        $default = $param->default;

        $this->assertEquals('null', $default->name->parts[0]);

        $this->verifyPrint($param, 'callable $callable = null');
    }

    public function testGenerateVariadicTypedAsString(): void
    {
        /** @var Param $param */
        $param = createParameter(
            $this->reflectionParameters[7]
        );

        $this->assertInstanceOf(Param::class, $param);
        $this->assertEquals('string', $param->type->name);
        $this->assertEquals('variadic', $param->var->name);
        /** @var ConstFetch $default */
        $default = $param->default;

        $this->assertNull($default);

        $this->verifyPrint($param, 'string ...$variadic');
    }

    private function verifyPrint(Param $param, string $test): void
    {
        $this->assertEquals($test, $this->prettyPrinter->prettyPrint([$param]));
    }
}
