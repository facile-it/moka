<?php
declare(strict_types=1);

namespace Tests;

use Moka\Exception\NotImplementedException;
use Moka\Moka;
use Moka\Plugin\Mockery\MockeryMockingStrategy;
use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Moka\Plugin\Prophecy\ProphecyMockingStrategy;
use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;

class MokaTest extends TestCase
{
    const BUILDERS = ['mockery', 'phake', 'prophecy', 'phpunit'];

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

    public function testBrewWithFakeBuilderFailure()
    {
        $this->expectException(NotImplementedException::class);

        Moka::foo(\stdClass::class);
    }

    public function testClean()
    {
        $proxy1 = Moka::phpunit(\stdClass::class);
        Moka::clean();
        $proxy2 = Moka::phpunit(\stdClass::class);

        $this->assertNotSame($proxy1, $proxy2);
    }

    protected function brewWithBuilder(MockingStrategyInterface $builder)
    {
        $this->assertInstanceOf(
            Proxy::class,
            Moka::phpunit(\stdClass::class, null, $builder)
        );
    }
}
