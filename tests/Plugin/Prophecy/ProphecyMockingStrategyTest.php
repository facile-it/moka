<?php
declare(strict_types=1);

namespace Tests\Plugin\Prophecy;

use Moka\Exception\MockNotCreatedException;
use Moka\Plugin\Prophecy\ProphecyMockingStrategy;
use Moka\Tests\FooTestClass;
use Moka\Tests\MokaMockingStrategyTestCase;
use Prophecy\Doubler\Doubler;
use Prophecy\Doubler\LazyDouble;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class ProphecyMockingStrategyTest extends MokaMockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new ProphecyMockingStrategy());
    }

    public function testGetCustomMockFailure(): void
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->get(new ObjectProphecy(new LazyDouble(new Doubler())));
    }

    public function testCallObjectProphecy(): void
    {
        $mock = $this->strategy->build(FooTestClass::class);

        $this->assertInstanceOf(
            MethodProphecy::class,
            $this->strategy->call($mock, 'withArgument')->set()
        );
    }
}
