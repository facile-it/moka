<?php
declare(strict_types=1);

namespace Tests\Plugin\PHPUnit;

use Moka\Exception\MockNotCreatedException;
use Moka\Factory\StubFactory;
use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Tests\BarTestClass;
use Tests\FooTestClass;
use Tests\Strategy\MockingStrategyTestCase;
use Tests\TestInterface;

class PHPUnitMockingStrategyTest extends MockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new PHPUnitMockingStrategy());
        $this->setMethodName('expects');
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

    public function testBuildFakeFQCNSuccess()
    {
        $mock = $this->strategy->build($this->getRandomFQCN());

        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    public function testBuildMultipleFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build(FooTestClass::class . ', ' . BarTestClass::class);
    }

    public function testDecorateFakeMethodFailure()
    {
        $this->expectException(\Exception::class);

        $this->strategy->decorate($this->mock, StubFactory::fromArray([
            'fakeMethod' => true
        ]));
    }

    public function testCallMissingMethodSuccess()
    {
        $mock = $this->strategy->build(FooTestClass::class);

        $this->assertInstanceOf(TestInterface::class, $this->strategy->get($mock)->getSelf());
    }

    public function testGetFakeFQCNSuccess()
    {
        $fqcn = $this->getRandomFQCN();
        $mock = $this->strategy->build($fqcn);

        $this->assertTrue(is_a($this->strategy->get($mock), $fqcn));
    }
}
