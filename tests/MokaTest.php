<?php
declare(strict_types=1);

namespace Tests;

use Moka\Exception\NotImplementedException;
use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use PHPUnit\Framework\TestCase;

class MokaTest extends TestCase
{
    /**
     * Moka::brew() is deprecated, but it still needs to be tested.
     */
    const METHODS = [
        'brew',
        'mockery',
        'phake',
        'phpunit',
        'prophecy'
    ];

    public function testBrewSuccess()
    {
        foreach (self::METHODS as $method) {
            $this->assertInstanceOf(
                ProxyInterface::class,
                Moka::$method(\stdClass::class)
            );
        }
    }

    public function testBrewFailure()
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
}
