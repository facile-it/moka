<?php
declare(strict_types=1);

namespace Tests\Plugin\Prophecy;

use Moka\Exception\MockNotCreatedException;
use Moka\Factory\StubFactory;
use Moka\Plugin\Prophecy\ProphecyMockingStrategy;
use Moka\Tests\MokaMockingStrategyTestCase;
use Prophecy\Doubler\Doubler;
use Prophecy\Doubler\LazyDouble;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\FooTestClass;
use Tests\TestInterface;

class ProphecyMockingStrategyTest extends MokaMockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new ProphecyMockingStrategy());
    }

    public function testBuildEmptyFQCNSuccess()
    {
        $mock = $this->strategy->build('');

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testBuildFakeFQCNSuccess()
    {
        $mock = $this->strategy->build($this->getRandomFQCN());

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testBuildMultipleFQCNSuccess()
    {
        $mock = $this->strategy->build($this->getRandomFQCN() . ', ' . $this->getRandomFQCN());

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testDecorateFakeMethodFailure()
    {
        $this->expectException(\Exception::class);

        $this->strategy->decorate($this->mock, [
            'fakeMethod' => true
        ]);
    }

    public function testCallMissingMethodFailure()
    {
        $mock = $this->strategy->build(FooTestClass::class);

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
        $fqcn = $this->getRandomFQCN();
        $mock = $this->strategy->build($fqcn);

        $this->assertFalse(is_a($this->strategy->get($mock), $fqcn));
    }

    public function testGetMultipleClassInterfaceFailure()
    {
        $mock = $this->strategy->build(FooTestClass::class . ', ' . TestInterface::class);

        $this->assertFalse(is_a($this->strategy->get($mock), FooTestClass::class));
        $this->assertFalse(is_a($this->strategy->get($mock), TestInterface::class));
    }

    public function testGetMultipleFakeFQCNFailure()
    {
        $fqcn1 = $this->getRandomFQCN();
        $fqcn2 = $this->getRandomFQCN();
        $mock = $this->strategy->build($fqcn1 . ', ' . $fqcn2);

        $this->assertFalse(is_a($this->strategy->get($mock), $fqcn1));
        $this->assertFalse(is_a($this->strategy->get($mock), $fqcn2));
    }
}
