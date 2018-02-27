<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\MissingDependencyException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use PHPUnit\Framework\TestCase;

class MockingStrategyTest extends TestCase
{
    public function testConstructFailure(): void
    {
        $this->expectException(MissingDependencyException::class);

        new FakeMockingStrategy();
    }

    public function testGetMockTypeFailure(): void
    {
        $strategy = new IncompleteMockingStrategy();

        $this->expectException(NotImplementedException::class);
        $strategy->getMockType();
    }

    public function testBuildFailure(): void
    {
        $strategy = new BareMockingStrategy();

        $this->expectException(MockNotCreatedException::class);
        $strategy->build(\stdClass::class);
    }

    public function testCallFailure(): void
    {
        $strategy = new BareMockingStrategy();

        $this->expectException(\Error::class);
        $strategy->call(new \stdClass(), 'foo');
    }
}
