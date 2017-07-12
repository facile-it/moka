<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\ClassTemplate;
use PHPUnit\Framework\TestCase;

class ClassTemplateTest extends TestCase
{
    public function testGenerate()
    {
        $code = ClassTemplate::generate(
            new \ReflectionClass(\stdClass::class)
        );

        $this->assertRegExp('/class *Moka_stdClass_\d/', $code);
    }
}
