<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Mockery\MockInterface;
use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\MockeryMockingStrategy;
use Tests\TestClass;

class MockeryMockingStrategyTest extends MockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new MockeryMockingStrategy());
        $this->setMockType(MockInterface::class);
    }

    public function testBuildEmptyFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('');
    }

    public function testBuildParseFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('foo bar');
    }

    public function testCallMissingMethodFailure()
    {
        $mock = $this->strategy->build(TestClass::class);

        $this->expectException(\Throwable::class);
        $this->strategy->get($mock)->getSelf();
    }
}
