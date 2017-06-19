<?php
declare(strict_types=1);

namespace Tests;

use Moka\Exception\NotImplementedException;
use Moka\Moka;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockeryMockingStrategy;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Strategy\PHPUnitMockingStrategy;
use Moka\Strategy\ProphecyMockingStrategy;
use PHPUnit\Framework\TestCase;

class MokaTest extends TestCase
{
    const BUILDERS = ['mockery', 'prophecy', 'phpunit'];

    public function testBrewSuccess()
    {
        $builders = [
            new MockeryMockingStrategy(),
            new PHPUnitMockingStrategy(),
            new ProphecyMockingStrategy()
        ];

        foreach ($builders as $builder) {
            $this->brewWithBuilder($builder);
        }
    }

    public function testBrewWithBuilderSuccess()
    {
        foreach (self::BUILDERS as $builder) {
            $this->assertInstanceOf(
                Proxy::class,
                Moka::$builder(\stdClass::class)
            );
        }
    }

    public function testBrewWithBuilderFailure()
    {
        $this->expectException(NotImplementedException::class);

        Moka::foo(\stdClass::class);
    }

    public function testGetSuccess()
    {
        $this->assertInstanceOf(
            Proxy::class,
            Moka::get(\stdClass::class)
        );
    }

    public function testReset()
    {
        $this->reset();
    }

    public function testClean()
    {
        $this->reset('clean');
    }

    protected function brewWithBuilder(MockingStrategyInterface $builder)
    {
        $this->assertInstanceOf(
            Proxy::class,
            Moka::brew(\stdClass::class, null, $builder)
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
