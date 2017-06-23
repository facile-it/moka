<?php
declare(strict_types=1);

namespace Tests\Strategy\Fake;

use Moka\Exception\MissingDependencyException;
use PHPUnit\Framework\TestCase;
use Tests\Strategy\FakeMockingStrategy;

class FakeMockingStrategyTest extends TestCase
{
    public function testConstruction()
    {
        $this->expectException(MissingDependencyException::class);

        new FakeMockingStrategy();
    }
}
