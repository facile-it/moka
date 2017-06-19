<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\ProphecyMockingStrategy;
use Prophecy\Doubler\Doubler;
use Prophecy\Doubler\LazyDouble;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestClass;

class ProphecyMockingStrategyTest extends MockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new ProphecyMockingStrategy());
    }

    public function testBuildMultipleFQCNSuccess()
    {
        $this->strategy->build('foo, bar');

        $this->assertTrue(true);
    }

    public function testCallMissingMethodFailure()
    {
        $mock = $this->strategy->build(TestClass::class);

        $this->expectException(\Throwable::class);
        $this->strategy->get($mock)->getSelf();
    }

    public function testGetCustomMockFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->get(new ObjectProphecy(new LazyDouble(new Doubler())));
    }

    public function testGetFakeFQCNFailure()
    {
        $mock = $this->strategy->build('foo');

        $this->assertFalse(is_a($this->strategy->get($mock), 'foo'));
    }

    public function testGetMultipleFQCNPartialSuccess()
    {
        $mock = $this->strategy->build(TestClass::class . ', ' . \stdClass::class);

        $this->assertFalse(is_a($this->strategy->get($mock), TestClass::class));
        $this->assertTrue(is_a($this->strategy->get($mock), \stdClass::class));
    }

    public function testGetMultipleFakeFQCNFailure()
    {
        $mock = $this->strategy->build('foo, bar');

        $this->assertFalse(is_a($this->strategy->get($mock), 'foo'));
        $this->assertFalse(is_a($this->strategy->get($mock), 'bar'));
    }
}
