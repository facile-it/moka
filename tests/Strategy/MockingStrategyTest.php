<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\MissingDependencyException;
use Moka\Exception\NotImplementedException;
use PHPUnit\Framework\TestCase;

class MockingStrategyTest extends TestCase
{
    public function testConstructFailure()
    {
        $this->expectException(MissingDependencyException::class);

        new FakeMockingStrategy();
    }

    public function testGetMockTypeFailure()
    {
        $strategy = new IncompleteMockingStrategy();

        $this->expectException(NotImplementedException::class);
        $strategy->getMockType();
    }

    public function testCallFailure()
    {
        $strategy = new BareMockingStrategy();

        $this->expectException(\Error::class);

        $strategy->call(new \stdClass(), 'foo');
    }
}
