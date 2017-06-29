<?php
declare(strict_types=1);

namespace Tests\Generator\Template;

use Moka\Generator\Template\ProxyClassTemplate;
use PHPUnit\Framework\TestCase;

class ProxyClassTemplateTests extends TestCase
{
    public function testGenerate()
    {
        $code = ProxyClassTemplate::generate(
            new \ReflectionClass(\stdClass::class)
        );

        $this->assertRegExp('/class *Moka_stdClass_\d/', $code);
    }
}
