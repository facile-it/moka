<?php
declare(strict_types=1);

namespace Tests;

use Moka\Moka;
use Moka\Proxy\Proxy;
use PHPUnit\Framework\TestCase;

class MokaTest extends TestCase
{
    const BUILDERS = ['mockery', 'phpunit', 'prophecy'];

    public function testGetSuccess()
    {
        $this->getWithBuilder('get');
        foreach (self::BUILDERS as $builder) {
            $this->getWithBuilder($builder);
        }
    }

    public function testClean()
    {
        $this->reset('clean');
    }

    protected function getWithBuilder(string $builder)
    {
        $this->assertInstanceOf(
            Proxy::class,
            Moka::$builder(\stdClass::class)
        );
    }

    protected function reset(string $method = 'clean')
    {
        $proxy1 = Moka::brew(\stdClass::class);
        Moka::$method();
        $proxy2 = Moka::brew(\stdClass::class);

        $this->assertNotSame($proxy1, $proxy2);
    }
}
