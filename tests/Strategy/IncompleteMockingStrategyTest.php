<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\NotImplementedException;
use PHPUnit\Framework\TestCase;
use Tests\IncompleteMockingStrategy;

class IncompleteMockingStrategyTest extends TestCase
{
    public function testCheckMockTypeFailure()
    {
        $strategy = new IncompleteMockingStrategy();

        $this->expectException(NotImplementedException::class);
        $strategy->get(new \stdClass());
    }
}
