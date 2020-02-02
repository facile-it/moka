<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\MethodTemplate;
use Moka\Tests\FooTestClass;
use PHPUnit\Framework\TestCase;

class MethodTemplateTest extends TestCase
{
    public function testGenerate()
    {
        $code = MethodTemplate::generate(
            new \ReflectionMethod(FooTestClass::class, 'getCallable')
        );

        $this->assertRegExp('/public +function +getCallable\(/', $code);
    }
}
