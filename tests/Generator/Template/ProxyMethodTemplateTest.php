<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\ProxyMethodTemplate;
use PHPUnit\Framework\TestCase;
use Tests\FooTestClass;

class ProxyMethodTemplateTest extends TestCase
{
    public function testGenerate()
    {
        $code = ProxyMethodTemplate::generate(
            new \ReflectionMethod(FooTestClass::class, 'getCallable')
        );

        $this->assertRegExp('/public +function getCallable\(/', $code);
    }
}
