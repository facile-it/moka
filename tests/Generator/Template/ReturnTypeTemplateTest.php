<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\ReturnTypeTemplate;
use PHPUnit\Framework\TestCase;
use Tests\FooTestClass;
use Tests\NewTestClass;

class ReturnTypeTemplateTest extends TestCase
{
    public function testGenerate()
    {
        $code = ReturnTypeTemplate::generate(
            new \ReflectionMethod(FooTestClass::class, 'getCallable')
        );

        $this->assertRegExp('/: *callable/', $code);
    }

    /**
     * @requires PHP 7.1
     */
    public function testGenerateWithSelf()
    {
        $code = ReturnTypeTemplate::generate(
            new \ReflectionMethod(NewTestClass::class, 'getSelfNew')
        );

        $this->assertRegExp('/: *\? *Tests\\\NewTestClass/', $code);
    }
}
