<?php
declare(strict_types=1);

namespace Tests;

use Moka\Moka;
use Moka\Proxy\Proxy;
use PHPUnit\Framework\TestCase;

class MokaTest extends TestCase
{
    public function testGetSuccess()
    {
        $this->assertInstanceOf(
            Proxy::class,
            Moka::get(\stdClass::class)
        );
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
}
