<?php
declare(strict_types=1);

namespace Tests;

use Moka\Moka;
use Moka\Proxy\Proxy;
use PHPUnit\Framework\TestCase;

class MokaTest extends TestCase
{
    const BUILDERS = ['phpunit', 'prophecy'];

    public function testGetSuccess()
    {
        $this->getWithBuilder('get');
        foreach (self::BUILDERS as $builder) {
            $this->getWithBuilder($builder);
        }
    }

    public function testReset()
    {
        $proxy1 = Moka::get(\stdClass::class);
        Moka::reset();
        $proxy2 = Moka::get(\stdClass::class);

        $this->assertNotSame($proxy1, $proxy2);
    }

    public function testClean()
    {
        $this->testReset();
    }

    protected function getWithBuilder(string $builder)
    {
        $this->assertInstanceOf(
            Proxy::class,
            Moka::$builder(\stdClass::class)
        );
    }
}
