<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Exception\MockNotCreatedException;
use Moka\Factory\StubFactory;
use Moka\Strategy\MockeryMockingStrategy;
use Tests\BarTestClass;
use Tests\FooTestClass;
use Tests\TestInterface;

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
        $mock = $this->strategy->build(FooTestClass::class . ', ' . BarTestClass::class);

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testBuildMultipleFakeFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build($this->getRandomFQCN() . ', ' . $this->getRandomFQCN());
    }

    public function testDecorateFakeMethodSuccess()
    {
        $this->strategy->decorate($this->mock, StubFactory::fromArray([
            'fakeMethod' => true
        ]));

        $this->assertSame(true, $this->strategy->get($this->mock)->fakeMethod());
    }

    public function testCallMissingMethodFailure()
    {
        $mock = $this->strategy->build(FooTestClass::class);

        $this->expectException(\Throwable::class);
        $this->strategy->get($mock)->getSelf();
    }

    public function testGetFakeFQCNSuccess()
    {
        $fqcn = $this->getRandomFQCN();
        $mock = $this->strategy->build($fqcn);

        $this->assertTrue(is_a($this->strategy->get($mock), $fqcn));
    }

    public function testGetMultipleClassInterfaceSuccess()
    {
        $mock = $this->strategy->build(FooTestClass::class . ', ' . TestInterface::class);

        $this->assertTrue(is_a($this->strategy->get($mock), FooTestClass::class));
        $this->assertTrue(is_a($this->strategy->get($mock), TestInterface::class));
    }

    public function testGetMultipleFQCNPartialSuccess()
    {
        $mock = $this->strategy->build(FooTestClass::class . ', ' . BarTestClass::class);

        $this->assertFalse(is_a($this->strategy->get($mock), FooTestClass::class));
        $this->assertTrue(is_a($this->strategy->get($mock), BarTestClass::class));
    }
}
