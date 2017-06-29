<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\ProxyParameterTemplate;
use PHPUnit\Framework\TestCase;
use Tests\FooTestClass;

class ProxyParameterTemplateTest extends TestCase
{
    public function testGenerate()
    {
        $method = new \ReflectionMethod(FooTestClass::class, 'withArguments');
        $parameters = $method->getParameters();

        $code = ProxyParameterTemplate::generate(
            $parameters[2]
        );

        $this->assertRegExp('/string +&\$byReference *= */', $code);
    }
}
