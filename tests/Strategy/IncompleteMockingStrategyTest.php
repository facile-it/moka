<?php

declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\NotImplementedException;
use PHPUnit\Framework\TestCase;

class IncompleteMockingStrategyTest extends TestCase
{
    public function testGetMockTypeFailure()
    {
        $strategy = new IncompleteMockingStrategy();

        $this->expectException(NotImplementedException::class);
        $strategy->getMockType();
    }
}
