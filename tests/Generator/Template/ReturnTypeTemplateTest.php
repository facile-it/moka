<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\ReturnTypeTemplate;
use Moka\Tests\FooTestClass;
use Moka\Tests\NewTestClass;
use PHPUnit\Framework\TestCase;

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

        $this->assertRegExp('/: *\? *Moka\\\Tests\\\NewTestClass/', $code);
    }
}
