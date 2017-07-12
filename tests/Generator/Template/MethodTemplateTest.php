<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\MethodTemplate;
use PHPUnit\Framework\TestCase;
use Tests\FooTestClass;

class MethodTemplateTest extends TestCase
{
    public function testGenerate()
    {
        $code = MethodTemplate::generate(
            new \ReflectionMethod(FooTestClass::class, 'getCallable')
        );

        $this->assertRegExp('/public +function getCallable\(/', $code);
    }
}
