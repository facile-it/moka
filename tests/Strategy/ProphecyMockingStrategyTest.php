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

    public function testGetCustomMockFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->get(new ObjectProphecy(new LazyDouble(new Doubler())));
    }

    public function testCallMissingMethodFailure()
    {
        $mock = $this->strategy->build(TestClass::class);

        $this->expectException(\Throwable::class);
        $this->strategy->get($mock)->getSelf();
    }
}
