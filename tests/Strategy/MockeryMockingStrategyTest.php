<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\MockeryMockingStrategy;
use Tests\TestClass;

class MockeryMockingStrategyTest extends MockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new MockeryMockingStrategy());
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

    public function testBuildMultipleFQCNSuccess()
    {
        $this->strategy->build(TestClass::class . ', ' . \stdClass::class);

        $this->assertTrue(true);
    }

    public function testBuildMultipleFakeFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('foo, bar');
    }

    public function testCallMissingMethodFailure()
    {
        $mock = $this->strategy->build(TestClass::class);

        $this->expectException(\Throwable::class);
        $this->strategy->get($mock)->getSelf();
    }

    public function testGetFakeFQCNSuccess()
    {
        $mock = $this->strategy->build('foo');

        $this->assertTrue(is_a($this->strategy->get($mock), 'foo'));
    }

    public function testGetMultipleFQCNPartialSuccess()
    {
        $mock = $this->strategy->build(TestClass::class . ', ' . \stdClass::class);

        $this->assertFalse(is_a($this->strategy->get($mock), TestClass::class));
        $this->assertTrue(is_a($this->strategy->get($mock), \stdClass::class));
    }
}
